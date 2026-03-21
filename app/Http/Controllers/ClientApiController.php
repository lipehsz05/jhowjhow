<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

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
            
            // Processar telefone (limpar caracteres não numéricos)
            $telefone = $request->telefone;
            if ($telefone) {
                $telefone = preg_replace('/[^0-9]/', '', $telefone);
                $telefone = substr($telefone, 0, 11);  // Limitar a 11 dígitos
            }
            
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
