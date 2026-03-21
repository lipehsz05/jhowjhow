<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Exibe a lista de clientes
     */
    public function index(Request $request)
    {
        $query = Cliente::query();
        
        // Filtros
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telefone', 'like', "%{$search}%")
                  ->orWhere('cpf_cnpj', 'like', "%{$search}%");
            });
        }
        
        $clientes = $query->orderBy('nome')->paginate(15);
        return view('clients.index', compact('clientes'));
    }
    
    /**
     * Mostra o formulário para criar um novo cliente
     */
    public function create()
    {
        return view('clients.create');
    }
    
    /**
     * Armazena um novo cliente
     */
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:20|unique:clientes,cpf_cnpj',
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
        ]);
        
        $data = $request->all();
        $data['data_cadastro'] = now();
        
        Cliente::create($data);
        
        return redirect()->route('clients.index')
            ->with('success', 'Cliente cadastrado com sucesso!');
    }
    
    /**
     * Mostra o formulário para editar um cliente existente
     */
    public function edit(Cliente $cliente)
    {
        return view('clients.edit', compact('cliente'));
    }
    
    /**
     * Atualiza um cliente existente
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'telefone' => 'nullable|string|max:20',
            'cpf_cnpj' => 'nullable|string|max:20|unique:clientes,cpf_cnpj,'.$cliente->id,
            'endereco' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'cep' => 'nullable|string|max:10',
        ]);
        
        $cliente->update($request->all());
        
        return redirect()->route('clients.index')
            ->with('success', 'Cliente atualizado com sucesso!');
    }
    
    /**
     * Remove um cliente
     */
    public function destroy(Cliente $cliente)
    {
        // Verificar se há vendas associadas a este cliente
        $vendasCount = $cliente->vendas()->count();
        
        if ($vendasCount > 0) {
            return redirect()->route('clients.index')
                ->with('error', 'Não é possível excluir este cliente pois existem vendas associadas a ele.');
        }
        
        $cliente->delete();
        
        return redirect()->route('clients.index')
            ->with('success', 'Cliente excluído com sucesso!');
    }
}
