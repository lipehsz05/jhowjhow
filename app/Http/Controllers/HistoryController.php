<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Exibe o histórico de vendas com filtros avançados
     */
    public function index(Request $request)
    {
        $query = Venda::with(['cliente', 'usuario', 'itens.produto']);
        
        // Filtros
        if ($request->has('periodo')) {
            $periodo = $request->periodo;
            
            switch($periodo) {
                case 'hoje':
                    $query->whereDate('data', Carbon::today());
                    break;
                case 'ontem':
                    $query->whereDate('data', Carbon::yesterday());
                    break;
                case 'semana':
                    $query->whereBetween('data', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'mes':
                    $query->whereBetween('data', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
                    break;
                case 'ano':
                    $query->whereYear('data', Carbon::now()->year);
                    break;
            }
        }
        
        if ($request->has('data_inicio') && $request->data_inicio) {
            $query->whereDate('data', '>=', Carbon::parse($request->data_inicio));
        }
        
        if ($request->has('data_fim') && $request->data_fim) {
            $query->whereDate('data', '<=', Carbon::parse($request->data_fim));
        }
        
        if ($request->has('cliente_id') && $request->cliente_id) {
            $query->where('cliente_id', $request->cliente_id);
        }
        
        if ($request->has('forma_pagamento') && $request->forma_pagamento) {
            $query->where('forma_pagamento', $request->forma_pagamento);
        }
        
        if ($request->has('valor_min') && $request->valor_min) {
            $query->where('valor_total', '>=', $request->valor_min);
        }
        
        if ($request->has('valor_max') && $request->valor_max) {
            $query->where('valor_total', '<=', $request->valor_max);
        }
        
        if ($request->has('produto_id') && $request->produto_id) {
            $query->whereHas('itens', function($q) use ($request) {
                $q->where('produto_id', $request->produto_id);
            });
        }
        
        // Ordenação
        $orderBy = $request->input('order_by', 'data');
        $orderDir = $request->input('order_dir', 'desc');
        
        // Total de vendas e valores
        $totalVendas = $query->count();
        $somaTotal = $query->sum('valor_total');
        
        // Paginação
        $vendas = $query->orderBy($orderBy, $orderDir)->paginate(15);
        
        // Dados para filtros
        $clientes = DB::table('clientes')->orderBy('nome')->get();
        $produtos = DB::table('produtos')->orderBy('nome')->get();
        $formasPagamento = DB::table('vendas')->select('forma_pagamento')
            ->distinct()->pluck('forma_pagamento');
        
        return view('history.index', compact(
            'vendas', 
            'clientes', 
            'produtos', 
            'formasPagamento',
            'totalVendas',
            'somaTotal'
        ));
    }
    
    /**
     * Exibe os detalhes de uma venda específica do histórico
     */
    public function show(Venda $venda)
    {
        $venda->load(['cliente', 'usuario', 'itens.produto']);
        return view('history.show', compact('venda'));
    }
}
