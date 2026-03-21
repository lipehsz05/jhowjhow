<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Support\BrFormat;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    /**
     * Cria um novo cliente via API
     */
    public function store(Request $request)
    {
        // Remover a tentativa de desativar CSRF - não é necessário com o token que já estamos enviando
        
        try {
            // Log dos dados recebidos para debug
            \Illuminate\Support\Facades\Log::info('Dados recebidos no cadastro de cliente:', $request->all());
            
            // Validação simplificada
            $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'telefone' => 'nullable|string|max:20',
            ], [
                'nome.required' => 'O nome do cliente é obrigatório',
                'email.required' => 'O email do cliente é obrigatório',
                'email.email' => 'Digite um email válido'
            ]);
            
            $telefone = BrFormat::normalizeTelefone($request->telefone);
            
            // Criar o cliente com valores explícitos
            $cliente = new Cliente();
            $cliente->nome = $request->nome;
            $cliente->email = $request->email;
            $cliente->telefone = $telefone;
            $cliente->data_cadastro = now();
            $cliente->save();
            
            return response()->json([
                'success' => true,
                'message' => 'Cliente cadastrado com sucesso',
                'cliente' => $cliente
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erros de validação',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log detalhado do erro
            \Illuminate\Support\Facades\Log::error('Erro ao cadastrar cliente: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao cadastrar cliente: ' . $e->getMessage()
            ], 500);
        }
    }
}
