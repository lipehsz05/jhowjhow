<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\Categoria;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Mostra a página do dashboard com dados
     */
    public function index(Request $request)
    {
        $periodo = $request->input('period', 'daily'); // Alterado de 'monthly' para 'daily' como período padrão
        $dataInicio = null;
        $dataFim = null;
        
        // Definir período de datas
        switch ($periodo) {
            case 'daily':
                $dataInicio = Carbon::today();
                $dataFim = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                $dataInicio = Carbon::now()->startOfWeek();
                $dataFim = Carbon::now()->endOfWeek();
                break;
            case 'monthly':
                // Por padrão, mostrar últimos 30 dias em vez de mês atual
                $dataInicio = Carbon::now()->subDays(29); // 30 dias incluindo hoje
                $dataFim = Carbon::now()->endOfDay();
                break;
            case 'quarterly':
                $dataInicio = Carbon::now()->startOfQuarter();
                $dataFim = Carbon::now()->endOfQuarter();
                break;
            case 'yearly':
                $dataInicio = Carbon::now()->startOfYear();
                $dataFim = Carbon::now()->endOfYear();
                break;
            case 'custom':
                $dataInicio = $request->input('data_inicio') ? Carbon::parse($request->input('data_inicio')) : Carbon::now()->subDays(29);
                $dataFim = $request->input('data_fim') ? Carbon::parse($request->input('data_fim')) : Carbon::now();
                break;
        }
        
        // Calcular valores totais
        $totalReceita = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->sum('valor_total') ?? 0;
        
        $totalDespesas = DB::table('produtos')
                        ->join('itens_venda', 'produtos.id', '=', 'itens_venda.produto_id')
                        ->join('vendas', 'itens_venda.venda_id', '=', 'vendas.id')
                        ->whereBetween('vendas.data', [$dataInicio, $dataFim])
                        ->where('vendas.status', 'concluida')
                        ->sum(DB::raw('produtos.preco_compra * itens_venda.quantidade')) ?? 0;
        
        $totalLucro = $totalReceita - $totalDespesas;
        $margemLucro = $totalReceita > 0 ? ($totalLucro / $totalReceita) * 100 : 0;
        
        // Calcular recebimentos por forma de pagamento
        $recebimentoPix = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'pix')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoDinheiro = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'dinheiro')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoDebito = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'debito')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoCredito = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'credito')
                            ->sum('valor_total') ?? 0;
                            
        // Calcular vendas provisórias
        $vendasProvisorias = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'provisoria')
                            ->sum('valor_total') ?? 0;
        
        // Usuários online no sistema (ativos nos últimos 5 minutos)
        $usuariosAtivos = User::where('is_online', true)
                          ->whereNotNull('last_activity')
                          ->where('last_activity', '>=', now()->subMinutes(5))
                          ->orderBy('last_activity', 'desc')
                          ->take(5)
                          ->get();
        
        // Produtos mais vendidos
        $produtosMaisVendidos = Produto::select('produtos.id', 'produtos.nome', 
                                DB::raw('SUM(itens_venda.quantidade) as quantidade_vendida'),
                                DB::raw('SUM(itens_venda.subtotal) as total_vendido'))
                                ->join('itens_venda', 'produtos.id', '=', 'itens_venda.produto_id')
                                ->join('vendas', 'itens_venda.venda_id', '=', 'vendas.id')
                                ->whereBetween('vendas.data', [$dataInicio, $dataFim])
                                ->where('vendas.status', 'concluida')
                                ->groupBy('produtos.id', 'produtos.nome')
                                ->orderByDesc('quantidade_vendida')
                                ->take(5)
                                ->get();
        
        // Vendas por categoria
        $vendasPorCategoria = Categoria::select('categorias.id', 'categorias.nome',
                                DB::raw('SUM(itens_venda.quantidade) as quantidade_vendida'),
                                DB::raw('SUM(itens_venda.subtotal) as total_vendido'))
                                ->join('produtos', 'categorias.id', '=', 'produtos.categoria_id')
                                ->join('itens_venda', 'produtos.id', '=', 'itens_venda.produto_id')
                                ->join('vendas', 'itens_venda.venda_id', '=', 'vendas.id')
                                ->whereBetween('vendas.data', [$dataInicio, $dataFim])
                                ->where('vendas.status', 'concluida')
                                ->groupBy('categorias.id', 'categorias.nome')
                                ->orderByDesc('total_vendido')
                                ->get();
        
        // Dados para o gráfico
        $chartData = $this->getChartData($periodo, $dataInicio, $dataFim);
        
        // Total de usuários ativos no sistema
        $totalUsuarios = User::count();
        
        return view('dashboard.index', compact(
            'totalReceita', 
            'totalDespesas', 
            'totalLucro', 
            'margemLucro', 
            'usuariosAtivos',
            'produtosMaisVendidos',
            'vendasPorCategoria',
            'chartData',
            'totalUsuarios',
            'recebimentoPix',
            'recebimentoDinheiro',
            'recebimentoDebito',
            'recebimentoCredito',
            'vendasProvisorias'
        ));
    }
    
    /**
     * Retorna dados do dashboard via AJAX com base no período selecionado
     */
    public function getDashboardData(Request $request)
    {
        $periodo = $request->input('period', 'daily'); // Alterado de 'monthly' para 'daily' como período padrão
        $dataInicio = null;
        $dataFim = null;
        
        // Definir período de datas
        switch ($periodo) {
            case 'daily':
                // Diário: do início do dia atual até o final do dia
                $dataInicio = Carbon::today(); 
                $dataFim = Carbon::today()->endOfDay();
                break;
            case 'weekly':
                // Semanal: últimos 7 dias
                $dataInicio = Carbon::now()->subDays(6)->startOfDay();
                $dataFim = Carbon::now()->endOfDay();
                break;
            case 'monthly':
                // Mensal: últimos 30 dias
                $dataInicio = Carbon::now()->subDays(29)->startOfDay();
                $dataFim = Carbon::now()->endOfDay();
                break;
            case 'yearly':
                // Anual: últimos 12 meses
                $dataInicio = Carbon::now()->subMonths(11)->startOfMonth();
                $dataFim = Carbon::now()->endOfDay();
                break;
            case 'custom':
                // Personalizado: datas específicas
                $dataInicio = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->subDays(29)->startOfDay();
                $dataFim = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfDay();
                break;
        }
        
        // Calcular valores totais
        $totalReceita = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->sum('valor_total') ?? 0;
        
        $totalDespesas = DB::table('produtos')
                        ->join('itens_venda', 'produtos.id', '=', 'itens_venda.produto_id')
                        ->join('vendas', 'itens_venda.venda_id', '=', 'vendas.id')
                        ->whereBetween('vendas.data', [$dataInicio, $dataFim])
                        ->where('vendas.status', 'concluida')
                        ->sum(DB::raw('produtos.preco_compra * itens_venda.quantidade')) ?? 0;
        
        $totalLucro = $totalReceita - $totalDespesas;
        $margemLucro = $totalReceita > 0 ? ($totalLucro / $totalReceita) * 100 : 0;
        
        // Calcular recebimentos por forma de pagamento
        $recebimentoPix = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'pix')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoDinheiro = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'dinheiro')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoDebito = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'debito')
                            ->sum('valor_total') ?? 0;
                            
        $recebimentoCredito = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', 'concluida')
                            ->where('forma_pagamento', 'credito')
                            ->sum('valor_total') ?? 0;
                            
        // Calcular vendas provisórias
        $vendasProvisorias = Venda::whereBetween('data', [$dataInicio, $dataFim])
                            ->where('status', '!=', 'concluida')
                            ->sum('valor_total') ?? 0;

        // Obter dados para o gráfico
        $chartData = $this->getChartData($periodo, $dataInicio, $dataFim);
        
        return response()->json([
            'totalReceita' => $totalReceita,
            'totalDespesas' => $totalDespesas,
            'totalLucro' => $totalLucro,
            'margemLucro' => $margemLucro,
            'recebimentoPix' => $recebimentoPix,
            'recebimentoDinheiro' => $recebimentoDinheiro,
            'recebimentoDebito' => $recebimentoDebito,
            'recebimentoCredito' => $recebimentoCredito,
            'vendasProvisorias' => $vendasProvisorias,
            'chartData' => $chartData
        ]);
    }

    /**
     * Obtém dados para o gráfico com base no período selecionado
     */
    private function getChartData($periodo, $dataInicio, $dataFim)
    {
        // Definir formato com base no período
        if ($periodo == 'daily') {
            // Para diário, usar formato de hora
            $format = '%Y-%m-%d %H:00';
        } else {
            // Para os demais períodos, usar formato diário
            $format = '%Y-%m-%d';
        }
        
        // Se não tiver dados para alguns dos intervalos, precisamos criar manualmente
        // um array com todos os intervalos no período (dias ou horas)
        $allDates = [];
        $currentDate = clone $dataInicio;
        
        if ($periodo == 'daily') {
            // Para período diário, criar um intervalo para cada hora
            while ($currentDate <= $dataFim) {
                $dateKey = $currentDate->format('Y-m-d H:00');
                $allDates[$dateKey] = [
                    'periodo' => $dateKey,
                    'receita' => 0,
                    'custo' => 0,
                    'liquido' => 0,
                    'provisorio' => 0 // Vendas provisórias
                ];
                $currentDate->addHour();
            }
        } else {
            // Para outros períodos, criar um intervalo para cada dia
            while ($currentDate <= $dataFim) {
                $dateKey = $currentDate->format('Y-m-d');
                $allDates[$dateKey] = [
                    'periodo' => $dateKey,
                    'receita' => 0,
                    'custo' => 0,
                    'liquido' => 0,
                    'provisorio' => 0 // Vendas provisórias
                ];
                $currentDate->addDay();
            }
        }
        
        // Consulta de vendas por período (concluídas)
        $vendas = DB::table('vendas')
            ->select(
                DB::raw("DATE_FORMAT(data, '{$format}') as periodo"),
                DB::raw('SUM(valor_total) as receita'),
                DB::raw('COUNT(*) as total_vendas')
            )
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->where('status', 'concluida')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();
            
        // Consulta de vendas provisórias (não concluídas)
        $vendasProvisorias = DB::table('vendas')
            ->select(
                DB::raw("DATE_FORMAT(data, '{$format}') as periodo"),
                DB::raw('SUM(valor_total) as receita_provisoria'),
                DB::raw('COUNT(*) as total_vendas_provisorias')
            )
            ->whereBetween('data', [$dataInicio, $dataFim])
            ->where('status', '!=', 'concluida')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();
            
        // Consulta de custos por período
        $custos = DB::table('vendas')
            ->select(
                DB::raw("DATE_FORMAT(vendas.data, '{$format}') as periodo"),
                DB::raw('SUM(produtos.preco_compra * itens_venda.quantidade) as custo_total')
            )
            ->join('itens_venda', 'vendas.id', '=', 'itens_venda.venda_id')
            ->join('produtos', 'itens_venda.produto_id', '=', 'produtos.id')
            ->whereBetween('vendas.data', [$dataInicio, $dataFim])
            ->where('vendas.status', 'concluida')
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();
        
        // Preencher dados de vendas
        foreach ($vendas as $venda) {
            if (isset($allDates[$venda->periodo])) {
                $allDates[$venda->periodo]['receita'] = floatval($venda->receita);
            }
        }
        
        // Preencher dados de vendas provisórias
        foreach ($vendasProvisorias as $vendaProvisoria) {
            if (isset($allDates[$vendaProvisoria->periodo])) {
                $allDates[$vendaProvisoria->periodo]['provisorio'] = floatval($vendaProvisoria->receita_provisoria);
            }
        }
        
        // Preencher dados de custos e calcular lucro líquido
        foreach ($custos as $custo) {
            if (isset($allDates[$custo->periodo])) {
                $allDates[$custo->periodo]['custo'] = floatval($custo->custo_total);
                // Calcular valor líquido (receita - custo)
                $allDates[$custo->periodo]['liquido'] = 
                    $allDates[$custo->periodo]['receita'] - floatval($custo->custo_total);
            }
        }
        
        // Recalcular valores líquidos para garantir
        foreach ($allDates as $date => $data) {
            // Se só temos receita mas não custo
            if ($data['receita'] > 0 && $data['custo'] == 0) {
                $allDates[$date]['liquido'] = $data['receita'];
            }
        }
        
        // Preparar dados para o gráfico
        $labels = [];
        $brutosData = [];
        $liquidosData = [];
        $despesasData = [];
        $provisoriosData = [];
        
        foreach ($allDates as $date => $data) {
            $labels[] = $date;
            $brutosData[] = $data['receita'];
            $liquidosData[] = $data['liquido'];
            $despesasData[] = $data['custo'];
            $provisoriosData[] = $data['provisorio'];
        }
        
        return [
            'labels' => $labels,
            'brutos' => $brutosData,    // Valor bruto (azul)
            'liquidos' => $liquidosData, // Valor líquido (verde)
            'provisorios' => $provisoriosData, // Vendas provisórias (amarelo)
            'despesas' => $despesasData  // Despesas (vermelho)
        ];
    }
}
