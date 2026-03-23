@extends('layouts.app')

@section('title', 'Relatório Completo')

@section('styles')
<style>
    .report-wrap { max-width: 1500px; margin: 0 auto; padding: 16px; }
    .report-header { display: flex; gap: 12px; justify-content: space-between; flex-wrap: wrap; margin-bottom: 14px; }
    .report-title { margin: 0; font-size: 24px; font-weight: 700; }
    .report-subtitle { color: #6b7280; font-size: 14px; margin-top: 3px; }
    .report-actions { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .btn-excel {
        display: inline-flex; align-items: center; gap: 8px; text-decoration: none; border: 0;
        background: linear-gradient(135deg, #10b981, #059669); color: #fff; padding: 10px 14px; border-radius: 10px; font-weight: 600;
    }
    .filters-card, .card-clean {
        background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 5px 18px rgba(15, 23, 42, 0.05);
    }
    .filters-card { padding: 16px 18px; margin-bottom: 14px; }
    .filters-card-head {
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 8px;
        margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid #f1f5f9;
    }
    .filters-card-head h3 { margin: 0; font-size: 15px; font-weight: 700; color: #0f172a; }
    .filters-card-head p { margin: 2px 0 0; font-size: 12px; color: #64748b; }
    .filters-toolbar {
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 14px 18px;
    }
    .filters-toolbar__period {
        display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px;
        flex: 1 1 auto; min-width: min(100%, 200px);
    }
    .filter-field { min-width: 180px; flex: 0 1 220px; }
    .filter-field label,
    .filter-dates label {
        display: block; font-size: 11px; font-weight: 600; letter-spacing: .02em;
        text-transform: uppercase; color: #64748b; margin-bottom: 6px; text-align: left;
    }
    .filter-field select,
    .filter-dates input {
        width: 100%; border: 1px solid #cbd5e1; border-radius: 10px; padding: 10px 12px; font-size: 14px;
        background: #fff; color: #0f172a; transition: border-color .15s, box-shadow .15s;
    }
    .filter-field select:focus,
    .filter-dates input:focus {
        outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
    .filter-dates {
        display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;
    }
    .filter-dates .filter-field { min-width: 150px; flex: 1 1 150px; }
    .filters-toolbar__compare {
        flex: 1 1 280px; min-width: 240px; max-width: 420px;
        padding: 12px 14px; background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
        border: 1px solid #e2e8f0; border-radius: 12px; align-self: stretch;
        display: flex; flex-direction: column; justify-content: flex-end;
    }
    .filters-toolbar__compare .compare-label {
        font-size: 11px; font-weight: 600; letter-spacing: .02em; text-transform: uppercase;
        color: #64748b; margin-bottom: 8px; text-align: left;
    }
    .compare-toggle {
        display: flex; align-items: flex-start; gap: 10px; cursor: pointer; margin: 0;
        font-size: 13px; line-height: 1.45; color: #334155; user-select: none;
    }
    .compare-toggle input[type="checkbox"] {
        width: 18px; height: 18px; margin-top: 2px; flex-shrink: 0; accent-color: #2563eb; cursor: pointer;
    }
    .filters-toolbar__actions {
        display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
        margin-left: auto;
    }
    .btn-primary-soft {
        background: linear-gradient(180deg, #2563eb 0%, #1d4ed8 100%);
        color: #fff; border: 0; border-radius: 10px; padding: 10px 18px; font-weight: 600;
        cursor: pointer; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35); transition: transform .12s, box-shadow .12s;
    }
    .btn-primary-soft:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4); }
    .btn-outline-soft {
        background: #fff; color: #334155; border: 1px solid #cbd5e1; border-radius: 10px;
        padding: 10px 16px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center;
        transition: background .15s, border-color .15s;
    }
    .btn-outline-soft:hover { background: #f8fafc; border-color: #94a3b8; }
    .kpis {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 10px; margin-bottom: 14px;
    }
    .kpi {
        padding: 14px; border-radius: 12px; background: #fff; border: 1px solid #e5e7eb;
    }
    .kpi .label { color: #6b7280; font-size: 13px; margin-bottom: 4px; }
    .kpi .value { font-size: 22px; font-weight: 700; color: #111827; }
    .kpi .hint { color: #6b7280; font-size: 12px; margin-top: 5px; }
    .value-positive { color: #059669 !important; }
    .value-negative { color: #dc2626 !important; }
    .grid-2 { display: grid; grid-template-columns: 1.5fr 1fr; gap: 12px; margin-bottom: 14px; }
    .grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 12px; margin-bottom: 14px; }
    .card-clean { padding: 14px; }
    .card-title { font-size: 16px; font-weight: 700; margin: 0 0 12px; }
    .chart-panel {
        background: linear-gradient(145deg, #ffffff 0%, #f8fbff 100%);
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .9), 0 6px 18px rgba(15, 23, 42, 0.06);
        padding: 12px;
    }
    .chart-wrap { position: relative; min-height: 320px; }
    .chart-wrap--sm { min-height: 210px; }
    .chart-subtitle {
        font-size: 12px;
        color: #64748b;
        margin-top: -8px;
        margin-bottom: 10px;
    }
    .table-wrap { overflow: auto; }
    table.clean { width: 100%; border-collapse: collapse; font-size: 14px; }
    table.clean th, table.clean td { border-bottom: 1px solid #eef2f7; padding: 9px 8px; text-align: left; }
    table.clean th { font-weight: 700; color: #374151; background: #f8fafc; position: sticky; top: 0; }
    .badge-soft { background: #e0e7ff; color: #3730a3; border-radius: 999px; padding: 4px 8px; font-size: 11px; }
    .compare-box { border: 1px dashed #93c5fd; background: #eff6ff; border-radius: 12px; padding: 12px; margin-bottom: 14px; }
    .compare-grid { display: grid; gap: 8px; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); }
    @media (max-width: 1100px) {
        .grid-2, .grid-3 { grid-template-columns: 1fr; }
        .filters-toolbar__actions { margin-left: 0; width: 100%; }
        .filters-toolbar__actions .btn-primary-soft,
        .filters-toolbar__actions .btn-outline-soft { flex: 1 1 auto; justify-content: center; min-width: 120px; }
    }
    @media (max-width: 640px) {
        .report-wrap { padding: 10px; }
        .filters-card { padding: 14px; }
        .filters-toolbar__period { flex-direction: column; align-items: stretch; }
        .filter-field { flex: 1 1 auto; min-width: 100%; }
        .filters-toolbar__compare { max-width: none; }
        .chart-wrap { min-height: 270px; }
        .chart-wrap--sm { min-height: 200px; }
        .card-title { font-size: 15px; }
    }
</style>
@endsection

@section('content')
<div class="content-wrapper">
    <div class="report-wrap">
        <div class="report-header">
            <div>
                <h2 class="report-title">Relatório Completo de Vendas</h2>
                <div class="report-subtitle">Período analisado: {{ $periodoTexto }}</div>
            </div>
            <div class="report-actions">
                <a class="btn-excel" href="{{ route('relatorio.export', request()->query()) }}">
                    <i class="fas fa-file-excel"></i>
                    Exportar para Excel (.csv)
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('relatorio.index') }}" class="filters-card" aria-label="Filtros do relatório">
            <div class="filters-card-head">
                <div>
                    <h3>Filtros</h3>
                    <p>Escolha o período e, se quiser, compare com o mês anterior.</p>
                </div>
            </div>
            <div class="filters-toolbar">
                <div class="filters-toolbar__period">
                    <div class="filter-field">
                        <label for="periodo">Período</label>
                        <select id="periodo" name="periodo">
                            <option value="hoje" {{ $filtros['periodo'] === 'hoje' ? 'selected' : '' }}>Hoje</option>
                            <option value="ontem" {{ $filtros['periodo'] === 'ontem' ? 'selected' : '' }}>Ontem</option>
                            <option value="semana" {{ $filtros['periodo'] === 'semana' ? 'selected' : '' }}>Essa semana</option>
                            <option value="mes" {{ $filtros['periodo'] === 'mes' ? 'selected' : '' }}>Esse mês</option>
                            <option value="trimestre" {{ $filtros['periodo'] === 'trimestre' ? 'selected' : '' }}>Trimestral</option>
                            <option value="semestre" {{ $filtros['periodo'] === 'semestre' ? 'selected' : '' }}>Semestral</option>
                            <option value="anual" {{ $filtros['periodo'] === 'anual' ? 'selected' : '' }}>Anual</option>
                            <option value="personalizado" {{ $filtros['periodo'] === 'personalizado' ? 'selected' : '' }}>Personalizado</option>
                        </select>
                    </div>
                    <div class="filter-dates" id="filter-dates-row" @if($filtros['periodo'] !== 'personalizado') style="display:none" @endif>
                        <div class="filter-field" id="date-start-wrap">
                            <label for="data_inicio">Data inicial</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="{{ $filtros['data_inicio'] }}" autocomplete="off">
                        </div>
                        <div class="filter-field" id="date-end-wrap">
                            <label for="data_fim">Data final</label>
                            <input type="date" id="data_fim" name="data_fim" value="{{ $filtros['data_fim'] }}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="filters-toolbar__compare">
                    <span class="compare-label">Comparativo</span>
                    <label class="compare-toggle">
                        <input type="checkbox" name="comparar_mes" value="1" {{ $filtros['comparar_mes'] ? 'checked' : '' }}>
                        <span>Comparar com o mês anterior <span style="color:#64748b;">(mesmo intervalo de dias)</span></span>
                    </label>
                </div>
                <div class="filters-toolbar__actions">
                    <button type="submit" class="btn-primary-soft">
                        <i class="fas fa-check" style="margin-right:6px;opacity:.9;"></i>Aplicar
                    </button>
                    <a href="{{ route('relatorio.index') }}" class="btn-outline-soft">
                        <i class="fas fa-undo" style="margin-right:6px;opacity:.75;"></i>Limpar
                    </a>
                </div>
            </div>
        </form>

        @if($comparativo)
            <div class="compare-box">
                <div class="card-title" style="margin-bottom:8px;">Comparativo com mês anterior</div>
                <div class="compare-grid">
                    <div><strong>Período anterior:</strong> {{ $comparativo['periodo_anterior'] }}</div>
                    <div><strong>Faturamento anterior:</strong> R$ {{ number_format($comparativo['faturamento_anterior'], 2, ',', '.') }}</div>
                    <div><strong>Diferença:</strong> R$ {{ number_format($comparativo['delta'], 2, ',', '.') }}</div>
                    <div><strong>Variação:</strong> <span class="{{ $comparativo['variacao'] >= 0 ? 'value-positive' : 'value-negative' }}">{{ number_format($comparativo['variacao'], 2, ',', '.') }}%</span></div>
                </div>
            </div>
        @endif

        <section class="kpis">
            <div class="kpi"><div class="label">Faturamento</div><div class="value">R$ {{ number_format($kpis['faturamento'], 2, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Total de Vendas</div><div class="value">{{ number_format($kpis['total_vendas'], 0, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Ticket Médio</div><div class="value">R$ {{ number_format($kpis['ticket_medio'], 2, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Custo Total</div><div class="value">R$ {{ number_format($kpis['custo_total'], 2, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Lucro Estimado</div><div class="value {{ $kpis['lucro_estimado'] >= 0 ? 'value-positive' : 'value-negative' }}">R$ {{ number_format($kpis['lucro_estimado'], 2, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Margem</div><div class="value {{ $kpis['margem'] >= 0 ? 'value-positive' : 'value-negative' }}">{{ number_format($kpis['margem'], 2, ',', '.') }}%</div></div>
            <div class="kpi"><div class="label">Descontos</div><div class="value">R$ {{ number_format($kpis['descontos'], 2, ',', '.') }}</div></div>
            <div class="kpi"><div class="label">Vendas Provisórias</div><div class="value">R$ {{ number_format($kpis['vendas_provisorias'], 2, ',', '.') }}</div></div>
        </section>

        <section class="grid-2">
            <article class="card-clean">
                <h3 class="card-title">Evolução das vendas ({{ $evolucao['agrupamento'] === 'mensal' ? 'mensal' : 'diária' }})</h3>
                <div class="chart-subtitle">Acompanhe tendência e variação de faturamento ao longo do período.</div>
                <div class="chart-panel">
                    <div class="chart-wrap"><canvas id="chartEvolucao"></canvas></div>
                </div>
            </article>
            <article class="card-clean">
                <h3 class="card-title">Formas de pagamento e status</h3>
                <div class="chart-panel" style="margin-bottom:10px;">
                    <div class="chart-wrap chart-wrap--sm"><canvas id="chartPagamento"></canvas></div>
                </div>
                <div class="chart-panel">
                    <div class="chart-wrap chart-wrap--sm"><canvas id="chartStatus"></canvas></div>
                </div>
            </article>
        </section>

        <section class="grid-3">
            <article class="card-clean">
                <h3 class="card-title">Itens mais vendidos</h3>
                <div class="table-wrap">
                    <table class="clean">
                        <thead>
                            <tr><th>Produto</th><th>Qtd</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topProdutos as $item)
                                <tr>
                                    <td>{{ $item->nome }}</td>
                                    <td>{{ number_format($item->quantidade, 0, ',', '.') }}</td>
                                    <td>R$ {{ number_format($item->total, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">Sem dados no período.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
            <article class="card-clean">
                <h3 class="card-title">Clientes que mais compraram</h3>
                <div class="table-wrap">
                    <table class="clean">
                        <thead>
                            <tr><th>Cliente</th><th>Compras</th><th>Total</th></tr>
                        </thead>
                        <tbody>
                            @forelse($topClientes as $cliente)
                                <tr>
                                    <td>{{ $cliente->nome }}</td>
                                    <td>{{ number_format($cliente->compras, 0, ',', '.') }}</td>
                                    <td>R$ {{ number_format($cliente->total, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3">Sem dados no período.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
            <article class="card-clean">
                <h3 class="card-title">Cliente destaque do mês</h3>
                @if($clienteDestaqueMes)
                    <div class="kpi" style="border:0;padding:4px 0 0;">
                        <div class="label">Maior comprador do mês atual</div>
                        <div class="value" style="font-size:20px;">{{ $clienteDestaqueMes->nome }}</div>
                        <div class="hint">{{ number_format($clienteDestaqueMes->compras, 0, ',', '.') }} compras | R$ {{ number_format($clienteDestaqueMes->total, 2, ',', '.') }}</div>
                    </div>
                @else
                    <p style="margin:0;">Sem compras concluídas neste mês.</p>
                @endif
                <hr>
                <h3 class="card-title" style="margin-top:0;">Vendas por categoria</h3>
                <div class="chart-panel">
                    <div class="chart-wrap chart-wrap--sm"><canvas id="chartCategoria"></canvas></div>
                </div>
            </article>
        </section>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    (function () {
        const periodo = document.getElementById('periodo');
        const dateRow = document.getElementById('filter-dates-row');

        function toggleDatas() {
            const personalizado = periodo.value === 'personalizado';
            if (dateRow) {
                dateRow.style.display = personalizado ? 'flex' : 'none';
            }
        }
        periodo.addEventListener('change', toggleDatas);
        toggleDatas();

        const evolucaoLabels = @json($evolucao['labels']);
        const evolucaoValues = @json($evolucao['values']);
        const pagamentoData = @json($formasPagamento);
        const status = @json($statusVendas);
        const categoriaData = @json($vendasCategoria);
        const isMobile = window.matchMedia('(max-width: 768px)').matches;

        function moeda(v) {
            return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(Number(v || 0));
        }

        const defaultOptions = {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        pointStyle: 'circle',
                        boxWidth: 10,
                        padding: isMobile ? 12 : 16,
                        color: '#334155',
                        font: { size: isMobile ? 11 : 12, weight: 600 }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.92)',
                    titleColor: '#e2e8f0',
                    bodyColor: '#f8fafc',
                    borderColor: 'rgba(148,163,184,.35)',
                    borderWidth: 1,
                    padding: 10,
                    cornerRadius: 10
                }
            }
        };

        new Chart(document.getElementById('chartEvolucao'), {
            type: 'line',
            data: {
                labels: evolucaoLabels,
                datasets: [{
                    label: 'Faturamento',
                    data: evolucaoValues,
                    borderColor: '#2563eb',
                    borderWidth: 2.2,
                    pointRadius: isMobile ? 2 : 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: '#2563eb',
                    backgroundColor: (ctx) => {
                        const chart = ctx.chart;
                        const { ctx: c, chartArea } = chart;
                        if (!chartArea) return 'rgba(37,99,235,.16)';
                        const g = c.createLinearGradient(0, chartArea.top, 0, chartArea.bottom);
                        g.addColorStop(0, 'rgba(37,99,235,.28)');
                        g.addColorStop(1, 'rgba(37,99,235,.04)');
                        return g;
                    },
                    fill: true,
                    tension: 0.35
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: (ctx) => ` ${ctx.dataset.label}: ${moeda(ctx.parsed.y)}`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(148,163,184,.12)', drawBorder: false },
                        ticks: { color: '#64748b', maxRotation: 0, autoSkip: true, maxTicksLimit: isMobile ? 5 : 10 }
                    },
                    y: {
                        grid: { color: 'rgba(148,163,184,.16)', drawBorder: false },
                        ticks: { color: '#64748b', callback: (v) => moeda(v).replace(',00', '') }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartPagamento'), {
            type: 'bar',
            data: {
                labels: pagamentoData.map(i => i.forma_pagamento || 'não informado'),
                datasets: [{
                    label: 'Total por forma',
                    data: pagamentoData.map(i => Number(i.total)),
                    backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#8b5cf6', '#ef4444'],
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${moeda(ctx.parsed.y)}`
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#64748b' } },
                    y: { grid: { color: 'rgba(148,163,184,.16)', drawBorder: false }, ticks: { color: '#64748b', callback: (v) => moeda(v).replace(',00', '') } }
                }
            }
        });

        new Chart(document.getElementById('chartStatus'), {
            type: 'doughnut',
            data: {
                labels: ['Concluída', 'Provisória'],
                datasets: [{
                    data: [status.concluida || 0, status.provisoria || 0],
                    backgroundColor: ['#16a34a', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                ...defaultOptions,
                cutout: isMobile ? '58%' : '62%',
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${ctx.parsed} venda(s)`
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('chartCategoria'), {
            type: 'pie',
            data: {
                labels: categoriaData.map(i => i.nome),
                datasets: [{
                    data: categoriaData.map(i => Number(i.total)),
                    backgroundColor: ['#2563eb', '#0ea5e9', '#14b8a6', '#22c55e', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'],
                    borderColor: '#ffffff',
                    borderWidth: 2,
                    hoverOffset: 7
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    ...defaultOptions.plugins,
                    tooltip: {
                        ...defaultOptions.plugins.tooltip,
                        callbacks: {
                            label: (ctx) => ` ${ctx.label}: ${moeda(ctx.parsed)}`
                        }
                    }
                }
            }
        });
    })();
</script>
@endsection
