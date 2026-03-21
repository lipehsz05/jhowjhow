<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * Exibe o formulário de criação de administrador.
     */
    public function create()
    {
        // Lista de cargos disponíveis
        $cargos = [
            'administrador' => 'Administrador (Acesso Total)',
            'vendedor' => 'Vendedor (Acesso a Vendas)',
            'estoquista' => 'Estoquista (Acesso ao Estoque)'
        ];
        
        // Adiciona a opção 'dono' apenas se o usuário logado for dono
        // Administradores não podem criar donos, apenas o dono pode criar outro dono
        if (Auth::user() && Auth::user()->nivel_acesso === 'dono') {
            $cargos['dono'] = 'Dono (Acesso Máximo ao Sistema)';
        }
        
        return view('admin.create', compact('cargos'));
    }

    /**
     * Armazena um novo administrador no banco de dados.
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        // Verificar se está tentando criar um usuário dono e tem permissão
        if ($request->cargo == 'dono' && (!$currentUser || $currentUser->nivel_acesso != 'dono')) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Apenas o dono pode criar usuários com este nível de acesso.');
        }
        
        // Verificar se está tentando criar um administrador sem ter permissão adequada
        // Administradores podem criar outros administradores
        if ($request->cargo == 'administrador' && (!$currentUser || !in_array($currentUser->nivel_acesso, ['dono', 'administrador']))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Você não tem permissão para criar administradores.');
        }
        
        // Determinar os cargos permitidos com base no nível de acesso do usuário
        $cargos_permitidos = $currentUser->nivel_acesso === 'dono' 
            ? 'administrador,vendedor,estoquista,dono' 
            : 'administrador,vendedor,estoquista';
            
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'cargo' => 'required|in:' . $cargos_permitidos,
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Deve conter pelo menos uma letra maiúscula
                'regex:/[0-9]/',      // Deve conter pelo menos um número
                'regex:/[^A-Za-z0-9]/', // Deve conter pelo menos um caractere especial
            ],
        ], [
            'name.required' => 'O nome do administrador é obrigatório.',
            'username.unique' => 'Este nome de usuário já está em uso.',
            'username.required' => 'O nome de usuário é obrigatório.',
            'cargo.required' => 'Por favor, selecione um cargo.',
            'cargo.in' => 'O cargo selecionado não é válido.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.regex' => 'A senha não atende aos requisitos de complexidade.',
            'password.confirmed' => 'As senhas não conferem.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Criar o novo usuário (nome do administrador mantendo maiúsculas/minúsculas, nome de usuário em minúsculas)
        User::create([
            'name' => $request->name, // Nome do administrador com maiúsculas e minúsculas preservadas
            'username' => strtolower($request->username), // Nome de usuário convertido para minúsculas
            'password' => Hash::make($request->password),
            'nivel_acesso' => $request->cargo,
        ]);

        return redirect()->route('dashboard')->with('success', 'Usuário cadastrado com sucesso!');
    }
    
    /**
     * Exibe a lista de todos os usuários.
     */
    public function index()
    {
        $users = User::orderBy('nivel_acesso', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        $currentUser = Auth::user();
        return view('admin.users.index', compact('users', 'currentUser'));
    }
    
    /**
     * Exibe o formulário para edição de um usuário.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Atualiza os dados de um usuário específico.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();
        
        // Verificar se tem permissão para editar
        if ($user->nivel_acesso == 'administrador' && $currentUser->nivel_acesso != 'dono' && $user->id != $currentUser->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas o dono do sistema pode editar outros administradores.');
        }
        
        // Verificar se está tentando mudar o próprio cargo
        if ($user->id == $currentUser->id && $request->cargo != $user->nivel_acesso) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode alterar seu próprio cargo.');
        }
        
        // Verificar se está tentando definir alguém como dono
        if ($request->cargo == 'dono' && $currentUser->nivel_acesso != 'dono') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas o dono pode atribuir este nível de acesso.');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
        ];
        
        // Adicionar validação para cargo apenas se não estiver editando o próprio usuário
        if ($user->id != $currentUser->id) {
            $rules['cargo'] = 'required|string|in:administrador,vendedor,estoquista,dono';
        }
        
        $messages = [
            'name.required' => 'O nome é obrigatório',
            'cargo.required' => 'O cargo é obrigatório',
            'cargo.in' => 'O cargo selecionado é inválido',
        ];
        
        // Se a senha for fornecida, adicione as regras de validação para ela
        if ($request->filled('password')) {
            $rules['password'] = ['string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'];
            $messages['password.min'] = 'A senha deve ter pelo menos 8 caracteres';
            $messages['password.regex'] = 'A senha deve conter pelo menos uma letra maiúscula, um número e um caractere especial';
            $messages['password.confirmed'] = 'A confirmação da senha não corresponde';
        }
        
        $request->validate($rules, $messages);
        
        $userData = [
            'name' => $request->name,
        ];
        
        // Atualizar cargo apenas se não estiver editando o próprio usuário
        if ($user->id != $currentUser->id && isset($request->cargo)) {
            $userData['nivel_acesso'] = $request->cargo;
        }
        
        // Atualiza a senha apenas se ela foi fornecida
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        
        $user->update($userData);
        
        // Redirecionar para a lista de usuários com mensagem de sucesso (moderna com SweetAlert2)
        return redirect()->route('admin.users.index')->with('success', ['title' => 'Usuário atualizado com sucesso!', 'icon' => 'success']);
    }
    
    /**
     * Remove um usuário específico.
     */
    public function destroy(User $user)
    {
        $currentUser = Auth::user();
        
        // Impedir a exclusão do usuário logado
        if ($user->id == $currentUser->id) {
            return redirect()->route('admin.users.index')->with('error', 'Você não pode excluir seu próprio usuário!');
        }
        
        // Verificar se tem permissão para excluir
        if ($user->nivel_acesso == 'administrador' && $currentUser->nivel_acesso != 'dono') {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas o dono do sistema pode excluir administradores.');
        }
        
        // Ninguém pode excluir o dono
        if ($user->nivel_acesso == 'dono') {
            return redirect()->route('admin.users.index')
                ->with('error', 'O usuário dono do sistema não pode ser excluído.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Usuário removido com sucesso!');
    }
}
