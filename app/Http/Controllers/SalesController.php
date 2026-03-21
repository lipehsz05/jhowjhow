<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\ItemVenda;
use App\Models\Produto;
use App\Models\Cliente;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    /**
     * Exibe a lista de vendas
     */
    public function index(Request $request)
    {
        $query = Venda::query()->with(['cliente', 'usuario']);
        
        // Filtros
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('data', '<=', $request->data_fim);
        }
        
        if ($request->has('cliente_id') && $request->cliente_id) {
            $query->where('cliente_id', $request->cliente_id);
        }
        
        $vendas = $query->orderBy('data', 'desc')->paginate(15);
        $clientes = Cliente::orderBy('nome')->get();
        
        return view('sales.index', compact('vendas', 'clientes'));
    }
    
    /**
     * Mostra o formulário para criar uma nova venda
     */
    public function create()
    {
        $clientes = Cliente::orderBy('nome')->get();
        $produtos = Produto::where('ativo', true)
            ->where('quantidade_estoque', '>', 0)
            ->orderBy('nome')
            ->get();
            
        return view('sales.create', compact('clientes', 'produtos'));
    }
    
    /**
     * Armazena uma nova venda
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
            'produtos.*.preco_unitario' => 'required|numeric|min:0',
            'forma_pagamento' => 'required|string',
            'status' => 'required|string|in:concluida,provisoria',
            'desconto' => 'nullable|numeric|min:0',
        ]);
        
        DB::beginTransaction();
        
        try {
            $valor_total = 0;
            $produtos = $request->produtos;
            
            // Calcular valor total
            foreach ($produtos as $item) {
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                $valor_total += $subtotal;
            }
            
            // Aplicar desconto se houver
            $desconto = $request->desconto ?? 0;
            $valor_total = $valor_total - $desconto;
            
            // Garantir que user_id seja definido explicitamente
            $userId = null;
            if (Auth::check()) {
                $userId = Auth::id();
            } else {
                // Se não estiver autenticado, use 1 como fallback
                $userId = 1;
            }

            // Gerar código da venda baseado no próximo ID
            $proximoId = DB::table('vendas')->max('id') + 1;
            $codigoVenda = str_pad($proximoId, 6, '0', STR_PAD_LEFT); // Formato: 000001, 000002, etc.
            
            // Criar a venda
            $venda = new Venda();
            $venda->cliente_id = $request->cliente_id;
            $venda->user_id = $userId;
            $venda->data = now();
            $venda->valor_total = $valor_total;
            $venda->desconto = $desconto;
            $venda->forma_pagamento = $request->forma_pagamento;
            $venda->status = $request->status ?? 'provisoria'; // Status padrão: provisória
            $venda->observacao = $request->observacao;
            $venda->codigo = $codigoVenda; // Adicionar código da venda
            $venda->save();
            
            // Criar itens da venda e atualizar estoque
            foreach ($produtos as $item) {
                $produto = Produto::findOrFail($item['id']);
                $subtotal = $item['quantidade'] * $item['preco_unitario'];
                
                // Criar item da venda
                ItemVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $item['quantidade'],
                    'preco_unitario' => $item['preco_unitario'],
                    'subtotal' => $subtotal,
                ]);
                
                // Atualizar estoque
                $produto->quantidade_estoque -= $item['quantidade'];
                $produto->save();
            }
            
            // Atualizar última compra do cliente
            if ($request->cliente_id) {
                $cliente = Cliente::find($request->cliente_id);
                $cliente->data_ultima_compra = now();
                $cliente->valor_ultima_compra = $valor_total;
                $cliente->save();
            }
            
            DB::commit();
            
            return redirect()->route('sales.show', $venda->id)
                ->with('success', 'Venda registrada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Erro ao registrar venda: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Exibe os detalhes de uma venda
     */
    public function show(Venda $venda)
    {
        $venda->load(['cliente', 'usuario', 'itens.produto']);
        return view('sales.show', compact('venda'));
    }
    
    /**
     * Atualiza o status da venda (provisoria/concluida)
     */
    public function updateStatus(Request $request, Venda $venda)
    {
        try {
            $status = $request->status;
            
            if (!in_array($status, ['provisoria', 'concluida'])) {
                return back()->withErrors(['error' => 'Status inválido']);
            }
            
            $venda->status = $status;
            $venda->save();
            
            $mensagem = $status == 'concluida' 
                ? 'Venda marcada como paga com sucesso!' 
                : 'Venda marcada como provisória com sucesso!';
            
            return redirect()->route('sales.show', $venda->id)
                ->with('success', $mensagem);
                
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao alterar status da venda: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Remove uma venda
     */
    public function destroy(Venda $venda)
    {
        DB::beginTransaction();
        
        try {
            // Estornar produtos ao estoque
            foreach ($venda->itens as $item) {
                $produto = $item->produto;
                $produto->quantidade_estoque += $item->quantidade;
                $produto->save();
            }
            
            // Excluir venda e itens relacionados (por meio da constraint CASCADE)
            $venda->delete();
            
            DB::commit();
            
            return redirect()->route('sales.index')
                ->with('success', 'Venda cancelada com sucesso!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors(['error' => 'Erro ao cancelar venda: ' . $e->getMessage()]);
        }
    }
}
