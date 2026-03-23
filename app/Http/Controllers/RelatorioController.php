<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RelatorioController extends Controller
{
    public function index(Request $request)
    {
        [$inicio, $fim, $periodoAtual] = $this->resolverPeriodo($request);

        $compararMes = $request->boolean('comparar_mes');
        $comparativo = $compararMes ? $this->dadosComparativoMensal($inicio, $fim) : null;

        $baseConcluidas = Venda::query()
            ->where('status', 'concluida')
            ->whereBetween('data', [$inicio, $fim]);

        $baseTodas = Venda::query()->whereBetween('data', [$inicio, $fim]);

        $faturamento = (float) (clone $baseConcluidas)->sum('valor_total');
        $totalVendas = (int) (clone $baseConcluidas)->count();
        $ticketMedio = $totalVendas > 0 ? ($faturamento / $totalVendas) : 0.0;
        $descontos = (float) (clone $baseTodas)->sum('desconto');
        $vendasProvisorias = (float) (clone $baseTodas)->where('status', 'provisoria')->sum('valor_total');

        $custoTotal = (float) DB::table('vendas')
            ->join('itens_venda', 'vendas.id', '=', 'itens_venda.venda_id')
            ->join('produtos', 'produtos.id', '=', 'itens_venda.produto_id')
            ->where('vendas.status', 'concluida')
            ->whereBetween('vendas.data', [$inicio, $fim])
            ->sum(DB::raw('produtos.preco_compra * itens_venda.quantidade'));

        $lucroEstimado = $faturamento - $custoTotal;
        $margem = $faturamento > 0 ? (($lucroEstimado / $faturamento) * 100) : 0.0;

        $formasPagamento = DB::table('vendas')
            ->select('forma_pagamento', DB::raw('SUM(valor_total) as total'))
            ->where('status', 'concluida')
            ->whereBetween('data', [$inicio, $fim])
            ->groupBy('forma_pagamento')
            ->orderByDesc('total')
            ->get();

        $topProdutos = DB::table('itens_venda')
            ->join('vendas', 'vendas.id', '=', 'itens_venda.venda_id')
            ->join('produtos', 'produtos.id', '=', 'itens_venda.produto_id')
            ->select(
                'produtos.id',
                'produtos.nome',
                DB::raw('SUM(itens_venda.quantidade) as quantidade'),
                DB::raw('SUM(itens_venda.subtotal) as total')
            )
            ->where('vendas.status', 'concluida')
            ->whereBetween('vendas.data', [$inicio, $fim])
            ->groupBy('produtos.id', 'produtos.nome')
            ->orderByDesc('quantidade')
            ->limit(10)
            ->get();

        $topClientes = DB::table('vendas')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->select(
                DB::raw("COALESCE(clientes.nome, 'Consumidor não identificado') as nome"),
                DB::raw('COUNT(vendas.id) as compras'),
                DB::raw('SUM(vendas.valor_total) as total')
            )
            ->where('vendas.status', 'concluida')
            ->whereBetween('vendas.data', [$inicio, $fim])
            ->groupBy('clientes.nome')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $inicioMesAtual = Carbon::now()->startOfMonth();
        $fimMesAtual = Carbon::now()->endOfMonth();
        $clienteDestaqueMes = DB::table('vendas')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->select(
                DB::raw("COALESCE(clientes.nome, 'Consumidor não identificado') as nome"),
                DB::raw('COUNT(vendas.id) as compras'),
                DB::raw('SUM(vendas.valor_total) as total')
            )
            ->where('vendas.status', 'concluida')
            ->whereBetween('vendas.data', [$inicioMesAtual, $fimMesAtual])
            ->groupBy('clientes.nome')
            ->orderByDesc('total')
            ->first();

        $vendasCategoria = DB::table('categorias')
            ->join('produtos', 'produtos.categoria_id', '=', 'categorias.id')
            ->join('itens_venda', 'itens_venda.produto_id', '=', 'produtos.id')
            ->join('vendas', 'vendas.id', '=', 'itens_venda.venda_id')
            ->select(
                'categorias.nome',
                DB::raw('SUM(itens_venda.quantidade) as quantidade'),
                DB::raw('SUM(itens_venda.subtotal) as total')
            )
            ->where('vendas.status', 'concluida')
            ->whereBetween('vendas.data', [$inicio, $fim])
            ->groupBy('categorias.nome')
            ->orderByDesc('total')
            ->get();

        $evolucao = $this->dadosEvolucao($inicio, $fim);

        $statusVendas = DB::table('vendas')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->whereBetween('data', [$inicio, $fim])
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('relatorio.index', [
            'filtros' => [
                'periodo' => $periodoAtual,
                'data_inicio' => $request->input('data_inicio'),
                'data_fim' => $request->input('data_fim'),
                'comparar_mes' => $compararMes,
            ],
            'periodoTexto' => $inicio->format('d/m/Y').' - '.$fim->format('d/m/Y'),
            'kpis' => [
                'faturamento' => $faturamento,
                'total_vendas' => $totalVendas,
                'ticket_medio' => $ticketMedio,
                'lucro_estimado' => $lucroEstimado,
                'margem' => $margem,
                'descontos' => $descontos,
                'vendas_provisorias' => $vendasProvisorias,
                'custo_total' => $custoTotal,
            ],
            'formasPagamento' => $formasPagamento,
            'topProdutos' => $topProdutos,
            'topClientes' => $topClientes,
            'clienteDestaqueMes' => $clienteDestaqueMes,
            'vendasCategoria' => $vendasCategoria,
            'evolucao' => $evolucao,
            'statusVendas' => [
                'concluida' => (int) ($statusVendas['concluida'] ?? 0),
                'provisoria' => (int) ($statusVendas['provisoria'] ?? 0),
            ],
            'comparativo' => $comparativo,
        ]);
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        [$inicio, $fim] = $this->resolverPeriodo($request);

        $vendas = DB::table('vendas')
            ->leftJoin('clientes', 'clientes.id', '=', 'vendas.cliente_id')
            ->leftJoin('users', 'users.id', '=', 'vendas.user_id')
            ->select(
                'vendas.codigo',
                'vendas.data',
                'vendas.status',
                'vendas.forma_pagamento',
                'vendas.valor_total',
                'vendas.desconto',
                DB::raw("COALESCE(clientes.nome, 'Consumidor não identificado') as cliente"),
                DB::raw("COALESCE(users.name, 'N/A') as vendedor")
            )
            ->whereBetween('vendas.data', [$inicio, $fim])
            ->orderByDesc('vendas.data')
            ->get();

        $nomeArquivo = 'relatorio_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($vendas) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($out, [
                'Codigo',
                'Data',
                'Status',
                'Forma de pagamento',
                'Valor total',
                'Desconto',
                'Cliente',
                'Vendedor',
            ], ';');

            foreach ($vendas as $venda) {
                fputcsv($out, [
                    $venda->codigo,
                    Carbon::parse($venda->data)->format('d/m/Y H:i'),
                    $venda->status,
                    $venda->forma_pagamento,
                    number_format((float) $venda->valor_total, 2, ',', '.'),
                    number_format((float) $venda->desconto, 2, ',', '.'),
                    $venda->cliente,
                    $venda->vendedor,
                ], ';');
            }

            fclose($out);
        }, $nomeArquivo, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function dadosComparativoMensal(Carbon $inicio, Carbon $fim): array
    {
        $dias = $inicio->diffInDays($fim) + 1;
        $inicioComparativo = (clone $inicio)->subMonth()->startOfDay();
        $fimComparativo = (clone $inicioComparativo)->addDays($dias - 1)->endOfDay();

        $faturamentoAtual = (float) Venda::where('status', 'concluida')
            ->whereBetween('data', [$inicio, $fim])
            ->sum('valor_total');

        $faturamentoAnterior = (float) Venda::where('status', 'concluida')
            ->whereBetween('data', [$inicioComparativo, $fimComparativo])
            ->sum('valor_total');

        $delta = $faturamentoAtual - $faturamentoAnterior;
        $variacao = $faturamentoAnterior > 0 ? ($delta / $faturamentoAnterior) * 100 : 100.0;

        return [
            'periodo_anterior' => $inicioComparativo->format('d/m/Y').' - '.$fimComparativo->format('d/m/Y'),
            'faturamento_anterior' => $faturamentoAnterior,
            'delta' => $delta,
            'variacao' => $variacao,
        ];
    }

    private function dadosEvolucao(Carbon $inicio, Carbon $fim): array
    {
        $diasNoPeriodo = $inicio->diffInDays($fim);
        $agrupamentoMensal = $diasNoPeriodo > 62;
        $formato = $agrupamentoMensal ? '%Y-%m' : '%Y-%m-%d';
        $saida = [];

        $consulta = DB::table('vendas')
            ->select(
                DB::raw("DATE_FORMAT(data, '{$formato}') as periodo"),
                DB::raw('SUM(valor_total) as total')
            )
            ->where('status', 'concluida')
            ->whereBetween('data', [$inicio, $fim])
            ->groupBy('periodo')
            ->orderBy('periodo')
            ->get();

        foreach ($consulta as $linha) {
            $saida[] = [
                'periodo' => $linha->periodo,
                'total' => (float) $linha->total,
            ];
        }

        return [
            'agrupamento' => $agrupamentoMensal ? 'mensal' : 'diario',
            'labels' => array_column($saida, 'periodo'),
            'values' => array_column($saida, 'total'),
        ];
    }

    private function resolverPeriodo(Request $request): array
    {
        $periodo = $request->input('periodo', 'hoje');
        $agora = Carbon::now();

        switch ($periodo) {
            case 'hoje':
                $inicio = $agora->copy()->startOfDay();
                $fim = $agora->copy()->endOfDay();
                break;
            case 'ontem':
                $inicio = $agora->copy()->subDay()->startOfDay();
                $fim = $agora->copy()->subDay()->endOfDay();
                break;
            case 'semana':
                $inicio = $agora->copy()->startOfWeek();
                $fim = $agora->copy()->endOfWeek();
                break;
            case 'mes':
                $inicio = $agora->copy()->startOfMonth();
                $fim = $agora->copy()->endOfMonth();
                break;
            case 'trimestre':
                $inicio = $agora->copy()->startOfQuarter();
                $fim = $agora->copy()->endOfQuarter();
                break;
            case 'semestre':
                $mes = (int) $agora->format('n');
                if ($mes <= 6) {
                    $inicio = $agora->copy()->month(1)->startOfMonth();
                    $fim = $agora->copy()->month(6)->endOfMonth();
                } else {
                    $inicio = $agora->copy()->month(7)->startOfMonth();
                    $fim = $agora->copy()->month(12)->endOfMonth();
                }
                break;
            case 'anual':
                $inicio = $agora->copy()->startOfYear();
                $fim = $agora->copy()->endOfYear();
                break;
            case 'personalizado':
                $inicio = $request->filled('data_inicio')
                    ? Carbon::parse($request->input('data_inicio'))->startOfDay()
                    : $agora->copy()->startOfMonth();
                $fim = $request->filled('data_fim')
                    ? Carbon::parse($request->input('data_fim'))->endOfDay()
                    : $agora->copy()->endOfDay();
                break;
            default:
                $periodo = 'hoje';
                $inicio = $agora->copy()->startOfDay();
                $fim = $agora->copy()->endOfDay();
                break;
        }

        if ($fim->lt($inicio)) {
            $fim = $inicio->copy()->endOfDay();
        }

        return [$inicio, $fim, $periodo];
    }
}
