<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MovimentacaoEstoque;
use App\Models\Produto;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class MovimentacaoEstoqueController extends Controller
{
    /**
     * Constructor para aplicar verificação de acesso
     */
    public function __construct()
    {
        // Controle de acesso é implementado em cada método diretamente
    }
    
    /**
     * Exibe o histórico de movimentações de estoque
     */
    public function index(Request $request)
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar as movimentações de estoque.');
        }
        
        $query = MovimentacaoEstoque::with(['produto', 'usuario']);
        
        // Filtros
        if ($request->has('produto_id') && $request->produto_id) {
            $query->where('produto_id', $request->produto_id);
        }
        
        if ($request->has('tipo') && $request->tipo) {
            $query->where('tipo', $request->tipo);
        }
        
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('data', '>=', $request->data_inicio);
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('data', '<=', $request->data_fim);
        }
        
        // Ordenação
        $movimentacoes = $query->orderBy('data', 'desc')->paginate(15);
        
        // Produtos para filtro
        $produtos = Produto::orderBy('nome')->get();
        
        return View::make('inventory.movimentacoes.index', compact('movimentacoes', 'produtos'));
    }
    
    /**
     * Mostra o formulário para registrar uma nova entrada de estoque
     */
    public function createEntrada()
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para registrar entradas de estoque.');
        }
        
        $produtos = Produto::where('ativo', true)->orderBy('nome')->get();
        return View::make('inventory.movimentacoes.entrada', compact('produtos'));
    }
    
    /**
     * Mostra o formulário para registrar uma nova saída de estoque
     */
    public function createSaida()
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para registrar saídas de estoque.');
        }
        
        $produtos = Produto::where('ativo', true)
            ->where('quantidade_estoque', '>', 0)
            ->orderBy('nome')
            ->get();
        return View::make('inventory.movimentacoes.saida', compact('produtos'));
    }
    
    /**
     * Armazena uma nova entrada de estoque
     */
    public function storeEntrada(Request $request)
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para registrar entradas de estoque.');
        }
        
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'motivo' => 'required|string',
            'documento_referencia' => 'nullable|string|max:50',
            'observacao' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Obter o produto
            $produto = Produto::findOrFail($request->produto_id);
            
            // Registrar movimentação
            MovimentacaoEstoque::create([
                'produto_id' => $produto->id,
                'user_id' => Auth::id(),
                'tipo' => 'entrada',
                'quantidade' => $request->quantidade,
                'data' => Carbon::now(),
                'motivo' => $request->motivo,
                'documento_referencia' => $request->documento_referencia,
                'observacao' => $request->observacao,
            ]);
            
            // Atualizar estoque do produto
            $produto->quantidade_estoque += $request->quantidade;
            $produto->save();
            
            DB::commit();
            
            return Redirect::route('inventory.movimentacoes.index')
                ->with('success', 'Entrada de estoque registrada com sucesso!');
        
        } catch (\Exception $e) {
            DB::rollBack();
            
            return Redirect::back()->withErrors(['error' => 'Erro ao registrar entrada: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Armazena uma nova saída de estoque
     */
    public function storeSaida(Request $request)
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para registrar saídas de estoque.');
        }
        
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'quantidade' => 'required|integer|min:1',
            'motivo' => 'required|string',
            'documento_referencia' => 'nullable|string|max:50',
            'observacao' => 'nullable|string',
        ]);
        
        DB::beginTransaction();
        
        try {
            // Obter o produto
            $produto = Produto::findOrFail($request->produto_id);
            
            // Verificar se há estoque suficiente
            if ($produto->quantidade_estoque < $request->quantidade) {
                return Redirect::back()->withErrors(['quantidade' => 'Quantidade insuficiente em estoque.']);
            }
            
            // Registrar movimentação
            MovimentacaoEstoque::create([
                'produto_id' => $produto->id,
                'user_id' => Auth::id(),
                'tipo' => 'saida',
                'quantidade' => $request->quantidade,
                'data' => Carbon::now(),
                'motivo' => $request->motivo,
                'documento_referencia' => $request->documento_referencia,
                'observacao' => $request->observacao,
            ]);
            
            // Atualizar estoque do produto
            $produto->quantidade_estoque -= $request->quantidade;
            $produto->save();
            
            DB::commit();
            
            return Redirect::route('inventory.movimentacoes.index')
                ->with('success', 'Saída de estoque registrada com sucesso!');
        
        } catch (\Exception $e) {
            DB::rollBack();
            
            return Redirect::back()->withErrors(['error' => 'Erro ao registrar saída: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Exibe os detalhes de uma movimentação de estoque
     */
    public function show(MovimentacaoEstoque $movimentacao)
    {
        // Verificar se o usuário é vendedor (não tem acesso às movimentações)
        if (Auth::user()->nivel_acesso === 'vendedor') {
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para visualizar movimentações de estoque.');
        }
        
        $movimentacao->load(['produto', 'usuario']);
        return View::make('inventory.movimentacoes.show', compact('movimentacao'));
    }
}
