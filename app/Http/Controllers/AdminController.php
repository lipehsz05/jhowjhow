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
        
        // Dono ou DEV podem criar outro dono; o cargo DEV nunca aparece aqui (só via sistema)
        if (Auth::user() && Auth::user()->hasDonoLevelAccess()) {
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

        if ($request->cargo === 'dev') {
            return redirect()->back()
                ->withInput()
                ->with('error', 'O cargo DEV não pode ser atribuído por esta tela. Use o comando `php artisan user:promote-dev` ou o banco de dados.');
        }
        
        // Verificar se está tentando criar um usuário dono e tem permissão
        if ($request->cargo == 'dono' && (!$currentUser || ! $currentUser->hasDonoLevelAccess())) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Apenas dono ou desenvolvedor podem criar usuários com este nível de acesso.');
        }
        
        // Verificar se está tentando criar um administrador sem ter permissão adequada
        // Administradores podem criar outros administradores
        if ($request->cargo == 'administrador' && (!$currentUser || !in_array($currentUser->nivel_acesso, ['dono', 'dev', 'administrador']))) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Você não tem permissão para criar administradores.');
        }
        
        // Determinar os cargos permitidos com base no nível de acesso do usuário
        $cargos_permitidos = $currentUser && $currentUser->hasDonoLevelAccess()
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
        $users = User::query()
            ->orderByRaw("CASE nivel_acesso WHEN 'dev' THEN 0 WHEN 'dono' THEN 1 WHEN 'administrador' THEN 2 WHEN 'vendedor' THEN 3 WHEN 'estoquista' THEN 4 ELSE 5 END")
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
        if ($user->isDev() && ! Auth::user()->isDev()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas desenvolvedores podem editar usuários com cargo DEV.');
        }

        return view('admin.users.edit', compact('user'));
    }
    
    /**
     * Atualiza os dados de um usuário específico.
     */
    public function update(Request $request, User $user)
    {
        $currentUser = Auth::user();

        if ($request->filled('cargo') && $request->cargo === 'dev') {
            return redirect()->route('admin.users.index')
                ->with('error', 'O cargo DEV não pode ser alterado por esta tela.');
        }
        
        // Verificar se tem permissão para editar
        if ($user->nivel_acesso == 'administrador' && ! $currentUser->hasDonoLevelAccess() && $user->id != $currentUser->id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas o dono ou desenvolvedor do sistema pode editar outros administradores.');
        }
        
        // Verificar se está tentando mudar o próprio cargo
        if ($user->id == $currentUser->id && $request->filled('cargo') && $request->cargo != $user->nivel_acesso) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Você não pode alterar seu próprio cargo.');
        }
        
        // Verificar se está tentando definir alguém como dono
        if ($request->cargo == 'dono' && ! $currentUser->hasDonoLevelAccess()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas dono ou desenvolvedor pode atribuir este nível de acesso.');
        }
        
        $rules = [
            'name' => 'required|string|max:255',
        ];
        
        // Cargo DEV permanece fixo; demais usuários seguem a lista abaixo
        if ($user->id != $currentUser->id && $user->nivel_acesso !== 'dev') {
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
        
        // Atualizar cargo apenas se não estiver editando o próprio usuário (DEV não muda pelo painel)
        if ($user->id != $currentUser->id && isset($request->cargo) && $user->nivel_acesso !== 'dev') {
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
        
        if ($user->nivel_acesso == 'dev' && ! $currentUser->isDev()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas desenvolvedores podem excluir usuários com cargo DEV.');
        }

        // Verificar se tem permissão para excluir
        if ($user->nivel_acesso == 'administrador' && ! $currentUser->hasDonoLevelAccess()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Apenas o dono ou desenvolvedor do sistema pode excluir administradores.');
        }
        
        // Dono não pode excluir outro dono; desenvolvedor pode (suporte / contingência)
        if ($user->nivel_acesso == 'dono' && ! $currentUser->isDev()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'O usuário dono do sistema não pode ser excluído por esta conta.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'Usuário removido com sucesso!');
    }
}
