@extends('layouts.app')

@section('title', 'Dashboard')

@section('styles')
{{-- Evita flash dos valores quando o usuário deixou oculto (localStorage) --}}
<script>
    (function () {
        try {
            if (localStorage.getItem('dashboardValuesVisible') === 'false') {
                document.documentElement.classList.add('dashboard-values-pending-mask');
            }
        } catch (e) {}
    })();
</script>
<!-- CSS específico para o Dashboard -->
<!-- SweetAlert2 para notificações modernas -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- Animate.css para animações -->  
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<style>
    /* Estilos para toast coloridos */
    .colored-toast.swal2-icon-success {
        background-color: #4CAF50 !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4) !important;
    }
    
    .colored-toast.swal2-icon-error {
        background-color: #F44336 !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(244, 67, 54, 0.4) !important;
    }
    
    .colored-toast .swal2-title,
    .colored-toast .swal2-html-container {
        color: white !important;
    }
    
    .colored-toast .swal2-timer-progress-bar {
        background: rgba(255, 255, 255, 0.5) !important;
    }
    
    /* Enquanto o JS não aplica máscara, esconde os números (sem flash) */
    html.dashboard-values-pending-mask .dashboard-summary .card-value {
        visibility: hidden !important;
    }

    /* Variáveis CSS para o dashboard */
    :root {
        --card-bg: #ffffff;
        --card-border-radius: 12px;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --card-hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        --positive-color: #38b000;
        --negative-color: #e63946;
        --transition: all 0.3s ease;
    }
    
    /* Container do Dashboard */
    .dashboard-container {
        padding: 8px 16px 20px;
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Media Queries para responsividade */
    @media (max-width: 768px) {
        .dashboard-container {
            padding: 8px 10px 15px;
        }
        
        .dashboard-title {
            font-size: 20px;
        }
    }
    
    /* Cabeçalho do Dashboard */
    .dashboard-header {
        margin-bottom: 20px;
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
    }
    
    .dashboard-title {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
        margin: 0;
    }
    
    /* Seletor de Período */
    .period-selector {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .period-btn {
        padding: 8px 16px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        color: #495057;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .period-btn:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .period-btn.active {
        background-color: var(--primary);
        color: white;
        border-color: var(--primary);
    }
    
    /* Seletor de Data Personalizado */
    .custom-date-selector {
        display: none;
        margin-top: 15px;
        width: 100%;
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .date-inputs {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }
    
    .date-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    
    .date-group label {
        font-size: 14px;
        font-weight: 500;
        color: #495057;
    }
    
    .date-group input {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        font-size: 14px;
    }
    
    .btn-apply {
        padding: 8px 16px;
        background-color: var(--primary);
        color: white;
        border: none;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }
    
    .btn-apply:hover {
        background-color: var(--primary-dark);
    }
    
    /* Linha superior do Dashboard */
    .dashboard-top-row {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        margin-bottom: 20px;
    }
    
    /* Resumo do Dashboard */
    .dashboard-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        flex: 3;
    }
    
    /* Seção de Últimos Clientes */
    .client-history-section {
        flex: 1;
        background-color: var(--card-bg);
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        padding: 20px;
        min-width: 300px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .client-history-section:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }
    
    .client-history-section h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
    }
    
    .client-list {
        list-style: none;
        padding: 0;
        margin: 0;
        max-height: 250px;
        overflow-y: auto;
    }
    
    .client-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f5;
    }
    
    .client-name {
        display: flex;
        align-items: center;
        font-weight: 500;
        color: #343a40;
    }
    .client-info {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    .client-date {
        color: #6c757d;
        font-size: 14px;
    }
    
    /* Cards do Dashboard */
    .dashboard-card {
        background-color: var(--card-bg);
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        padding: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--card-hover-shadow);
    }
    
    .card-title {
        font-size: 14px;
        font-weight: 600;
        color: #6c757d;
        margin: 0 0 8px 0;
    }
    
    .card-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark);
    }
    .card-value.is-hidden {
        letter-spacing: 1px;
        user-select: none;
    }
    .dashboard-visibility-toggle {
        border: 1px solid #dfe3ea;
        background: #fff;
        color: #495057;
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .dashboard-visibility-toggle:hover {
        background: #f8f9fa;
        border-color: #cfd6df;
    }
    
    /* Cores para lucros/perdas */
    .dashboard-card.positive .card-value {
        color: var(--positive-color);
    }
    
    .dashboard-card.negative .card-value {
        color: var(--negative-color);
    }
    
    /* Container de Gráficos */
    .charts-container {
        margin-bottom: 20px;
    }
    
    .chart-container {
        background-color: white;
        border-radius: var(--card-border-radius);
        box-shadow: var(--card-shadow);
        padding: 20px;
        margin-bottom: 20px;
        position: relative;
        height: 400px;
    }
    
    .chart-container.full-width {
        width: 100%;
    }
    
    /* Detalhes do Dashboard */
    .dashboard-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    @media (max-width: 576px) {
        .dashboard-details {
            grid-template-columns: 1fr;
            gap: 15px;
        }
        
        .dashboard-card {
            padding: 15px;
        }
        
        .chart-container {
            padding: 15px;
            margin-bottom: 15px;
        }
        
        .dashboard-stats {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }
        
        .card-value {
            font-size: 20px;
        }
        .dashboard-visibility-toggle {
            width: 36px;
            height: 36px;
            border-radius: 8px;
        }
        
        .client-history-section,
        .top-products-section {
            padding: 15px;
        }
    }
    
    /* Tabelas de Dados */
    .dashboard-card h3 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark);
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 10px;
    }
    
    /* Melhorias das tabelas de dados */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 14px;
    }
    
    .data-table th,
    .data-table td {
        padding: 10px 8px;
        text-align: left;
        border-bottom: 1px solid #eaeaea;
    }
    
    .data-table th {
        font-weight: 600;
        color: #495057;
        background-color: #f8f9fa;
    }
    
    .data-table tr:hover {
        background-color: #f8f9fa;
    }
    
    /* Estilo para alertas e notificações */
    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
        border-left: 4px solid;
    }
    
    .alert-success {
        background-color: #d4edda;
        border-color: var(--positive-color);
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        border-color: var(--negative-color);
        color: #721c24;
    }
    
    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffeeba;
        color: #856404;
    }
    
    @media (max-width: 576px) {
        .data-table {
            font-size: 12px;
        }
        
        .data-table th,
        .data-table td {
            padding: 8px 5px;
        }
        
        .alert {
            padding: 10px;
            font-size: 13px;
        }
    }
    
    .data-table tr:last-child td {
        border-bottom: none;
    }
    
    .data-table tr:hover td {
        background-color: #f8f9fa;
    }
    
    /* Estado de carregamento */
    .loading {
        position: relative;
        opacity: 0.8;
    }
    
    .loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.7);
        z-index: 10;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .dashboard-summary {
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        }
        
        .dashboard-top-row {
            flex-direction: column;
        }
        
        .client-history-section {
            min-width: 100%;
        }
        
        .dashboard-details {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
@php
    $hora = now()->hour;
    if ($hora >= 5 && $hora < 12) {
        $saudacaoDashboard = 'Bom dia';
    } elseif ($hora >= 12 && $hora < 18) {
        $saudacaoDashboard = 'Boa tarde';
    } else {
        $saudacaoDashboard = 'Boa noite';
    }
@endphp
<div id="dashboard-section" class="content-wrapper">
    <div class="dashboard-container">
        <!-- Alertas e notificações serão exibidos via SweetAlert2 -->
        
        <div class="dashboard-header">
            <h2 class="dashboard-title">{{ $saudacaoDashboard }} {{ Auth::user()->name }}, bem vindo ao Dashboard 👋</h2>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <button type="button"
                        id="dashboard-toggle-values"
                        class="dashboard-visibility-toggle"
                        title="Ocultar valores"
                        aria-label="Ocultar valores">
                    <i class="fas fa-eye"></i>
                </button>
                <div class="period-selector">
                    <button type="button" id="period-daily" data-period="daily" class="period-btn active">Hoje</button>
                    <button type="button" id="period-yesterday" data-period="yesterday" class="period-btn">Ontem</button>
                    <button type="button" id="period-weekly" data-period="weekly" class="period-btn">Essa semana</button>
                    <button type="button" id="period-monthly" data-period="monthly" class="period-btn">Esse mês</button>
                    <button type="button" id="period-custom" data-period="custom" class="period-btn">personalizado</button>
                </div>
            </div>
            
            <!-- Seletor de datas personalizado -->
            <div id="custom-date-selector" class="custom-date-selector">
                <div class="date-inputs">
                    <div class="date-group">
                        <label for="start-date">Data inicial:</label>
                        <input type="date" id="start-date">
                    </div>
                    <div class="date-group">
                        <label for="end-date">Data final:</label>
                        <input type="date" id="end-date">
                    </div>
                    <button id="apply-date-range" class="btn-apply">Aplicar</button>
                </div>
            </div>
        </div>
        
        <!-- Resumo do Dashboard e Últimos Clientes -->
        <div class="dashboard-top-row">
            <div class="dashboard-summary">
                <div class="dashboard-card">
                    <h3 class="card-title">Receita Total</h3>
                    <div id="total-receita" class="card-value">R$ {{ number_format($totalReceita ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Despesas Totais</h3>
                    <div id="total-despesas" class="card-value">R$ {{ number_format($totalDespesas ?? 0, 2, ',', '.') }}</div>
                </div>
                <div id="lucro-card" class="dashboard-card {{ isset($totalLucro) && $totalLucro >= 0 ? 'positive' : 'negative' }}">
                    <h3 class="card-title">Lucro Líquido</h3>
                    <div id="total-lucro" class="card-value">R$ {{ number_format($totalLucro ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Margem de Lucro</h3>
                    <div id="margem-lucro" class="card-value">{{ number_format($margemLucro ?? 0, 1) }}%</div>
                </div>
                
                <div class="dashboard-card">
                    <h3 class="card-title">Recebimento PIX</h3>
                    <div id="pix-recebimento" class="card-value">R$ {{ number_format($recebimentoPix ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Recebimento Dinheiro</h3>
                    <div id="dinheiro-recebimento" class="card-value">R$ {{ number_format($recebimentoDinheiro ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Recebimento Cartão Débito</h3>
                    <div id="debito-recebimento" class="card-value">R$ {{ number_format($recebimentoDebito ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Recebimento Cartão Crédito</h3>
                    <div id="credito-recebimento" class="card-value">R$ {{ number_format($recebimentoCredito ?? 0, 2, ',', '.') }}</div>
                </div>
                <div class="dashboard-card">
                    <h3 class="card-title">Vendas Provisórias</h3>
                    <div id="vendas-provisorias" class="card-value">R$ {{ number_format($vendasProvisorias ?? 0, 2, ',', '.') }}</div>
                </div>
            </div>
            
            <!-- Seção de Usuários Online -->
            <div class="client-history-section" id="client-history">
                <h3>
                    <i class="fas fa-users text-primary"></i> 
                    Usuários Online
                </h3>
                <ul class="client-list">
                    @forelse ($usuariosAtivos ?? [] as $usuario)
                        <li class="client-item">
                            <div class="client-name">
                                <span class="online-indicator pulse"></span> 
                                {{ $usuario->name }}
                            </div>
                            <div class="client-info">
                                @if($usuario->nivel_acesso == 'dev')
                                    <span class="badge user-online-role-badge user-online-role-badge--dev text-white">
                                        <span class="user-online-role-badge-label">DEV</span>
                                    </span>
                                @else
                                    <span class="badge user-online-role-badge text-white
                                        @if($usuario->nivel_acesso == 'dono') bg-dark
                                        @elseif($usuario->nivel_acesso == 'administrador') bg-primary
                                        @elseif($usuario->nivel_acesso == 'vendedor') bg-success
                                        @elseif($usuario->nivel_acesso == 'estoquista') bg-info
                                        @else bg-secondary @endif">
                                        <span class="user-online-role-badge-label">{{ ucfirst($usuario->nivel_acesso) }}</span>
                                    </span>
                                @endif
                                <small class="text-muted ms-2" title="Última atividade">Online</small>
                            </div>
                        </li>
                    @empty
                        <li class="client-history-loading">Nenhum usuário online no momento</li>
                    @endforelse
                </ul>
            </div>
            
            <style>
                .online-indicator {
                    display: inline-block;
                    width: 10px;
                    height: 10px;
                    background-color: #2ecc71;
                    border-radius: 50%;
                    margin-right: 8px;
                }
                
                .pulse {
                    animation: pulse-animation 1.5s infinite;
                }
                
                @keyframes pulse-animation {
                    0% {
                        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0.7);
                    }
                    70% {
                        box-shadow: 0 0 0 6px rgba(46, 204, 113, 0);
                    }
                    100% {
                        box-shadow: 0 0 0 0 rgba(46, 204, 113, 0);
                    }
                }
                
                /* Badges de cargo na lista de online: brilho animado por dentro do fundo */
                .user-online-role-badge {
                    position: relative;
                    overflow: hidden;
                    border: 0;
                    color: #fff !important;
                }
                .user-online-role-badge--dev {
                    background: #6f42c1 !important;
                }
                .user-online-role-badge-label {
                    position: relative;
                    z-index: 1;
                }
                .user-online-role-badge::after {
                    content: '';
                    position: absolute;
                    top: -50%;
                    left: -60%;
                    width: 45%;
                    height: 200%;
                    background: linear-gradient(
                        90deg,
                        transparent 0%,
                        rgba(255, 255, 255, 0) 35%,
                        rgba(255, 255, 255, 0.55) 50%,
                        rgba(255, 255, 255, 0) 65%,
                        transparent 100%
                    );
                    transform: rotate(18deg);
                    animation: user-online-badge-shine 14s ease-in-out infinite;
                    pointer-events: none;
                    z-index: 0;
                }
                /* Brilho rápido no início do ciclo; o resto do tempo fica parado até repetir */
                @keyframes user-online-badge-shine {
                    0% {
                        left: -60%;
                        opacity: 0;
                    }
                    4% {
                        opacity: 1;
                    }
                    18% {
                        left: 120%;
                        opacity: 1;
                    }
                    19%,
                    100% {
                        left: 120%;
                        opacity: 0;
                    }
                }
            </style>
        </div>
        
        <!-- Charts -->
        <div class="charts-container" style="margin-bottom: 30px;">
            <!-- Gráfico principal com largura controlada -->
            <div class="chart-container" style="position: relative; height: 400px !important; max-width: 800px; width: 95%; margin: 0 auto; border-radius: 12px; background-color: #fff; padding: 15px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08); margin-bottom: 20px;">
                <canvas id="salesChart" height="370" style="max-height: 370px;"></canvas>
            </div>
        </div>
        
        <!-- Dados adicionais -->
        <div class="dashboard-details">
            <!-- Produtos mais vendidos -->
            <div class="dashboard-card" id="top-products">
                <h3>Produtos Mais Vendidos</h3>
                @if (isset($produtosMaisVendidos) && count($produtosMaisVendidos) > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Total (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($produtosMaisVendidos as $produto)
                                <tr>
                                    <td>{{ $produto->nome }}</td>
                                    <td>{{ $produto->quantidade_vendida }}</td>
                                    <td>R$ {{ number_format($produto->total_vendido, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhum produto vendido no período</p>
                @endif
            </div>
            
            <!-- Vendas por categoria -->
            <div class="dashboard-card" id="category-sales">
                <h3>Vendas por Categoria</h3>
                @if (isset($vendasPorCategoria) && count($vendasPorCategoria) > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Categoria</th>
                                <th>Qtd. Produtos</th>
                                <th>Total (R$)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vendasPorCategoria as $categoria)
                                <tr>
                                    <td>{{ $categoria->nome }}</td>
                                    <td>{{ $categoria->quantidade_vendida }}</td>
                                    <td>R$ {{ number_format($categoria->total_vendido, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>Nenhuma venda por categoria no período</p>
                @endif
            </div>
            
            <div class="dashboard-card">
                <h3 class="card-title">Usuários Ativos</h3>
                <div class="card-content">
                    <p class="value">{{ isset($totalUsuarios) ? $totalUsuarios : 0 }}</p>
                    <p class="trend neutral">Total de usuários no sistema</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- SweetAlert2 para notificações modernas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Script para exibir notificações SweetAlert2 -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Verificar se existem mensagens de sucesso ou erro na sessão
        const successMessage = "{{ session('success') }}";
        const errorMessage = "{{ session('error') }}";
        
        // Exibir mensagem de sucesso com SweetAlert2 (toast que desaparece após 3 segundos)
        if (successMessage && successMessage.trim() !== "") {
            Swal.fire({
                icon: 'success',
                title: 'Sucesso!',
                text: successMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast swal2-icon-success'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });
        }
        
        // Exibir mensagem de erro com SweetAlert2
        if (errorMessage && errorMessage.trim() !== "") {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: errorMessage,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'colored-toast swal2-icon-error'
                },
                showClass: {
                    popup: 'animate__animated animate__fadeInRight animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutRight animate__faster'
                }
            });
        }
        
        // Preparar erros de validação
        const errors = [];
        
        // Dados de erros do backend - inseridos de forma segura usando JSON.parse
        const validationErrors = JSON.parse('{!! json_encode($errors->all()) !!}');
        
        // Adicionar erros do servidor à lista de erros
        if (validationErrors && validationErrors.length > 0) {
            validationErrors.forEach(function(error) {
                errors.push(error);
            });
        }
        
        if (errors.length > 0) {
            let errorList = "";
            errors.forEach(function(error) {
                errorList += "<li>" + error + "</li>";
            });
            
            Swal.fire({
                icon: 'error',
                title: 'Erros de validação',
                html: "<ul class='text-left'>" + errorList + "</ul>",
                toast: false,
                position: 'center',
                showConfirmButton: true,
                confirmButtonText: 'Entendi'
            });
        }
    });
</script>

<!-- jQuery - necessário para chamadas AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Chart.js - versão estável conhecida por funcionar bem -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"></script>
<script>
    // Função executada quando a página estiver carregada
    document.addEventListener('DOMContentLoaded', function() {
        // Variáveis globais e de controle - declaradas uma única vez
        let chartInstance = null;       // Instância do gráfico
        let isUpdatingDashboard = false; // Evita requisições simultâneas
        let mouseOverChart = false;     // Controla quando o mouse está sobre o gráfico
        let updatingFromUserAction = false; // Se a atualização vem de um clique do usuário
        let chartInitialized = false;   // Se o gráfico já foi inicializado
        let lastChartConfig = null;     // Guarda a última configuração do gráfico
        let valoresVisiveis = localStorage.getItem('dashboardValuesVisible') !== 'false';
        
        // Elementos DOM importantes
        const periodButtons = document.querySelectorAll('.period-btn');
        const customDateSelector = document.getElementById('custom-date-selector');
        const dashboardValueToggle = document.getElementById('dashboard-toggle-values');
        const dashboardValueElements = document.querySelectorAll('.dashboard-summary .card-value');

        function aplicarVisibilidadeValores() {
            dashboardValueElements.forEach(function (el) {
                if (!el.dataset.realValue) {
                    el.dataset.realValue = el.textContent.trim();
                }
                if (valoresVisiveis) {
                    el.textContent = el.dataset.realValue;
                    el.classList.remove('is-hidden');
                } else {
                    el.textContent = '••••••';
                    el.classList.add('is-hidden');
                }
            });

            if (dashboardValueToggle) {
                const iconClass = valoresVisiveis ? 'fa-eye' : 'fa-eye-slash';
                dashboardValueToggle.innerHTML = '<i class="fas ' + iconClass + '"></i>';
                dashboardValueToggle.setAttribute('title', valoresVisiveis ? 'Ocultar valores' : 'Mostrar valores');
                dashboardValueToggle.setAttribute('aria-label', valoresVisiveis ? 'Ocultar valores' : 'Mostrar valores');
            }

            document.documentElement.classList.remove('dashboard-values-pending-mask');
        }

        function atualizarCardResumo(selector, valorFormatado) {
            const el = document.querySelector(selector);
            if (!el) return;
            el.dataset.realValue = valorFormatado;
            if (valoresVisiveis) {
                el.textContent = valorFormatado;
                el.classList.remove('is-hidden');
            } else {
                el.textContent = '••••••';
                el.classList.add('is-hidden');
            }
        }

        if (dashboardValueToggle) {
            dashboardValueToggle.addEventListener('click', function () {
                valoresVisiveis = !valoresVisiveis;
                localStorage.setItem('dashboardValuesVisible', String(valoresVisiveis));
                aplicarVisibilidadeValores();
            });
        }

        aplicarVisibilidadeValores();
        
        // Datas personalizadas do localStorage
        let customStartDate = localStorage.getItem('dashboardCustomStartDate') || null;
        let customEndDate = localStorage.getItem('dashboardCustomEndDate') || null;
        
        // Períodos removidos da interface (anual, trimestre) → voltam para "Hoje"
        (function () {
            const p = localStorage.getItem('dashboardPeriod');
            if (p === 'yearly' || p === 'quarterly') {
                localStorage.setItem('dashboardPeriod', 'daily');
            }
        })();
        
        // Verificar se há um período salvo no localStorage e aplicar
        const savedPeriod = localStorage.getItem('dashboardPeriod');
        const activePeriodButton = document.querySelector('.period-btn.active');
        
        // Definir o período atual baseado na prioridade: localStorage > botão ativo > diário
        let currentPeriod;
        if (savedPeriod) {
            currentPeriod = savedPeriod;
            console.log('Usando período salvo no localStorage:', currentPeriod);
        } else if (activePeriodButton) {
            currentPeriod = activePeriodButton.dataset.period;
            localStorage.setItem('dashboardPeriod', currentPeriod);
            console.log('Usando período do botão ativo:', currentPeriod);
        } else {
            currentPeriod = 'daily'; // Definir diário como padrão em vez de mensal
            localStorage.setItem('dashboardPeriod', currentPeriod);
            console.log('Nenhum período encontrado, usando diário como padrão');
        }
        
        // Dados do gráfico - parsear do backend ou usar dados de demonstração
        let chartData;
        try {
            // Usando JSON.parse diretamente com os dados JSON do backend
            chartData = JSON.parse('{!! json_encode($chartData ?? []) !!}');
            console.log('Dados do gráfico carregados com sucesso:', chartData);
        } catch (error) {
            console.error('Erro ao parsear dados do gráfico:', error);
            // Usar dados de demonstração se houver erro
            chartData = {
                labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                brutos: [1000, 1500, 1200, 1800, 2000, 2200],
                liquidos: [200, 600, 350, 850, 900, 1000],
                provisorios: [300, 400, 450, 500, 600, 700],
                despesas: [800, 900, 850, 950, 1100, 1200]
            };
        }
        
        // Flag para evitar recriação desnecessária do gráfico
        window.chartInitialized = false;
        
        // Garantir que os arrays de dados existam
        chartData.labels = chartData.labels || ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
        chartData.brutos = chartData.brutos || [1000, 1500, 1200, 1800, 2000, 2200];
        chartData.liquidos = chartData.liquidos || [200, 600, 350, 850, 900, 1000];
        chartData.despesas = chartData.despesas || [800, 900, 850, 950, 1100, 1200];
        chartData.provisorios = chartData.provisorios || [300, 400, 450, 500, 600, 700];
        
        // Obter o elemento canvas do gráfico
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Criar o gráfico
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Valor Bruto',
                        data: chartData.brutos,
                        backgroundColor: 'rgba(67, 97, 238, 0.8)',
                        borderColor: '#4361ee',
                        borderWidth: 1
                    },
                    {
                        label: 'Valor Líquido',
                        data: chartData.liquidos,
                        backgroundColor: 'rgba(46, 204, 113, 0.8)',
                        borderColor: '#2ecc71',
                        borderWidth: 1
                    },
                    {
                        label: 'Vendas Provisórias',
                        data: chartData.provisorios,
                        backgroundColor: 'rgba(241, 196, 15, 0.8)', // Amarelo
                        borderColor: '#f1c40f',
                        borderWidth: 1
                    },
                    {
                        label: 'Despesas',
                        data: chartData.despesas,
                        backgroundColor: 'rgba(231, 76, 60, 0.8)',
                        borderColor: '#e74c3c',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                title: {
                    display: true,
                    text: 'Resumo financeiro',
                    fontSize: 18
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let label = data.datasets[tooltipItem.datasetIndex].label || '';
                            if (label) label += ': ';
                            label += new Intl.NumberFormat('pt-BR', {
                                style: 'currency',
                                currency: 'BRL'
                            }).format(tooltipItem.yLabel);
                            return label;
                        }
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false
                        },
                        ticks: {
                            maxRotation: 45,
                            minRotation: 45,
                            autoSkip: true,
                            maxTicksLimit: 15
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return new Intl.NumberFormat('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL',
                                    maximumFractionDigits: 0
                                }).format(value);
                            }
                        },
                        gridLines: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }]
                }
            }
        });
        
        // Usar as variáveis já declaradas no início do script
        // Não redeclarar aqui
        
        // Função para formatar valores monetários
        function formatarMoeda(valor) {
            return new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
        }
        
        // Função para formatar porcentagem
        function formatarPorcentagem(valor) {
            return valor.toFixed(1) + '%';
        }
        
        // Flag global para evitar requisições simultâneas já declarada anteriormente
        
        // Função para atualizar os dados do dashboard
        function atualizarDashboard(periodo, dataInicio = null, dataFim = null) {
            // Sempre forçar o período a ser o que foi salvo no localStorage ou 'daily' como padrão
            periodo = periodo || localStorage.getItem('dashboardPeriod') || 'daily';
            // Se já estiver atualizando, não faz nada
            if (isUpdatingDashboard) return;
            
            // Se o mouse estiver sobre o gráfico e não for uma ação do usuário, não atualiza
            if (mouseOverChart && !updatingFromUserAction) {
                console.log('Mouse sobre gráfico, evitando atualização automática');
                return;
            }
            
            // Marcar que está atualizando
            isUpdatingDashboard = true;
            
            // Mostrar indicador de carregamento
            document.body.style.cursor = 'wait';
            
            // Atualizar o período atual
            currentPeriod = periodo;
            
            // Salvar datas personalizadas se fornecidas
            if (periodo === 'custom') {
                if (dataInicio) customStartDate = dataInicio;
                if (dataFim) customEndDate = dataFim;
            }
            
            // Parâmetros da requisição
            // Garantir que o período não seja undefined ou null
            let periodoFinal = periodo || localStorage.getItem('dashboardPeriod') || 'daily';
            let params = { period: periodoFinal };
            
            // Sempre salvar o período atual no localStorage
            localStorage.setItem('dashboardPeriod', periodoFinal);
            currentPeriod = periodoFinal;
            console.log('Enviando período para backend:', periodoFinal);
            
            // Adicionar datas caso seja período personalizado
            if (periodo === 'custom' && dataInicio && dataFim) {
                params.start_date = dataInicio;
                params.end_date = dataFim;
            }
            
            // Fazer requisição AJAX para obter dados atualizados
            $.ajax({
                url: '{{ route("dashboard.dados") }}',
                type: 'GET',
                data: params,
                success: function(data) {
                    // Atualizar cards de resumo
                    atualizarCardResumo('#total-receita', formatarMoeda(data.totalReceita));
                    atualizarCardResumo('#total-despesas', formatarMoeda(data.totalDespesas));
                    atualizarCardResumo('#total-lucro', formatarMoeda(data.totalLucro));
                    atualizarCardResumo('#margem-lucro', formatarPorcentagem(data.margemLucro));
                    
                    // Atualizar recebimentos por forma de pagamento
                    atualizarCardResumo('#pix-recebimento', formatarMoeda(data.recebimentoPix));
                    atualizarCardResumo('#dinheiro-recebimento', formatarMoeda(data.recebimentoDinheiro));
                    atualizarCardResumo('#debito-recebimento', formatarMoeda(data.recebimentoDebito));
                    atualizarCardResumo('#credito-recebimento', formatarMoeda(data.recebimentoCredito));
                    atualizarCardResumo('#vendas-provisorias', formatarMoeda(data.vendasProvisorias));
                    
                    // Criar novo gráfico com os dados atualizados - forçar atualização quando vier do clique em botão de período
                    createChart(data.chartData, true);
                    
                    // Restaurar cursor e flags
                    document.body.style.cursor = 'default';
                    isUpdatingDashboard = false;
                    updatingFromUserAction = false; // Reset flag após atualização
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao carregar dados do dashboard:', error);
                    alert('Erro ao carregar dados do dashboard. Por favor, tente novamente.');
                    document.body.style.cursor = 'default';
                    isUpdatingDashboard = false;
                    updatingFromUserAction = false; // Reset flag mesmo em caso de erro
                }
            });
        }
        
        // Adicionar eventos aos botões de período
        if (periodButtons) {
            periodButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const clickedPeriod = this.dataset.period;
                    
                    // Marcar que a atualização está sendo feita por ação do usuário
                    updatingFromUserAction = true;
                    
                    // Salvar o período selecionado no localStorage
                    localStorage.setItem('dashboardPeriod', clickedPeriod);
                    
                    // Se o período clicado for o mesmo que o atual, não faz nada
                    if (clickedPeriod === currentPeriod && clickedPeriod !== 'custom') {
                        console.log('Período já selecionado:', clickedPeriod);
                        updatingFromUserAction = false;
                        return;
                    }
                    
                    // Remover classe ativa de todos os botões
                    periodButtons.forEach(btn => btn.classList.remove('active'));
                    
                    // Adicionar classe ativa ao botão clicado
                    this.classList.add('active');
                    
                    // Exibir ou ocultar o seletor de data personalizado
                    if (clickedPeriod === 'custom') {
                        if (customDateSelector) {
                            customDateSelector.style.display = 'block';
                            
                            // Se já tiver datas personalizadas salvas, carregar automaticamente
                            if (customStartDate && customEndDate) {
                                atualizarDashboard('custom', customStartDate, customEndDate);
                            }
                        }
                    } else {
                        if (customDateSelector) {
                            customDateSelector.style.display = 'none';
                        }
                        
                        // Atualizar dashboard com o período selecionado
                        atualizarDashboard(clickedPeriod);
                    }
                });
            });
            
            // Adicionar evento ao botão Aplicar do seletor de datas personalizado
            const applyButton = document.getElementById('apply-date-range');
            if (applyButton) {
                $('#apply-date-range').on('click', function() {
                    const startDate = $('#start-date').val();
                    const endDate = $('#end-date').val();
                    
                    if (startDate && endDate) {
                        customStartDate = startDate;
                        customEndDate = endDate;
                        
                        // Salvar datas personalizadas no localStorage
                        localStorage.setItem('dashboardCustomStartDate', startDate);
                        localStorage.setItem('dashboardCustomEndDate', endDate);
                        localStorage.setItem('dashboardPeriod', 'custom');
                        
                        // Marcar que a atualização está sendo feita por ação do usuário
                        updatingFromUserAction = true;
                        
                        atualizarDashboard('custom', startDate, endDate);
                    } else {
                        alert('Por favor, selecione as datas de início e fim.');
                    }
                });
            }
            
            // Inicializar com o período atual definido no início do script
            // Já prioriza localStorage > botão ativo > diário (nunca mensal)
            const savedPeriodBtn = document.getElementById('period-' + currentPeriod);
            
            // Se tiver um botão correspondente ao período salvo, ativá-lo
            if (savedPeriodBtn) {
                // Remover a classe ativa de todos os botões
                periodButtons.forEach(btn => btn.classList.remove('active'));
                
                // Adicionar classe ativa ao botão do período salvo
                savedPeriodBtn.classList.add('active');
                
                // Definir o período atual
                currentPeriod = savedPeriod;
                
                // Se for período personalizado, exibir o seletor de data
                if (savedPeriod === 'custom') {
                    if (customDateSelector) {
                        customDateSelector.style.display = 'block';
                    }
                    // Carregar datas salvas se existirem
                    if (customStartDate && customEndDate) {
                        document.getElementById('start-date').value = customStartDate;
                        document.getElementById('end-date').value = customEndDate;
                    }
                }
                
                // SOLUCIONAR PROBLEMA DE PERÍODO PADRÃO: Forçar o período daily caso ainda não haja um salvo
                // Se não tiver período salvo no localStorage, forçar diário como padrão
                if (!localStorage.getItem('dashboardPeriod')) {
                    localStorage.setItem('dashboardPeriod', 'daily');
                    currentPeriod = 'daily';
                    console.log('Forçando período padrão: diário');
                    
                    // Ativar o botão diário
                    document.querySelectorAll('.period-btn').forEach(btn => btn.classList.remove('active'));
                    const dailyBtn = document.getElementById('period-daily');
                    if (dailyBtn) dailyBtn.classList.add('active');
                }
                
                // Enviar período ao backend após montar a UI
                console.log('Inicializando dashboard com período:', currentPeriod);
                setTimeout(() => {
                    // SOLUÇÃO DEFINITIVA: Sobreescrever os métodos do Chart.js que causam o problema
                    // 1. Desabilitar todos os eventos hover do Chart.js
                    Chart.defaults.global.hover.mode = null;
                    Chart.defaults.global.hover.intersect = false;
                    Chart.defaults.global.hover.animationDuration = 0;
                    
                    // 2. Modificar o construtor de Chart para permitir tooltips mas evitar mudanças de período
                    const originalChartConstructor = Chart;
                    Chart = function(context, config) {
                        // Forçar configurações específicas para tooltips sem mudança de período
                        if (config) {
                            // Configurar opções
                            config.options = config.options || {};
                            
                            // Permitir apenas eventos de tooltip
                            config.options.events = ['mousemove', 'mouseout']; // Apenas eventos necessários para tooltips
                            
                            // Configurar tooltips
                            config.options.tooltips = config.options.tooltips || {};
                            config.options.tooltips.enabled = true;
                            config.options.tooltips.mode = 'index';
                            config.options.tooltips.intersect = false;
                            config.options.tooltips.position = 'nearest';
                            
                            // Configurar hover para tooltips
                            config.options.hover = {
                                mode: 'index',
                                intersect: false,
                                animationDuration: 0
                            };
                            
                            // Configurar tooltips detalhadas
                            if (!config.options.tooltips.callbacks) {
                                config.options.tooltips.callbacks = {
                                    label: function(tooltipItem, data) {
                                        let label = data.datasets[tooltipItem.datasetIndex].label || '';
                                        let value = tooltipItem.yLabel;
                                        if (label) {
                                            label += ': ' + new Intl.NumberFormat('pt-BR', {
                                                style: 'currency',
                                                currency: 'BRL'
                                            }).format(value);
                                        }
                                        return label;
                                    },
                                    title: function(tooltipItems, data) {
                                        // Personalizar o título baseado no período
                                        const period = localStorage.getItem('dashboardPeriod') || 'daily';
                                        const title = tooltipItems[0].xLabel;
                                        
                                        if (period === 'daily' || period === 'yesterday') {
                                            return 'Hora: ' + title;
                                        } else if (period === 'weekly' || period === 'monthly' || period === 'custom') {
                                            return 'Data: ' + title;
                                        }
                                        return title;
                                    }
                                };
                            }
                        }
                        
                        // Chamar o construtor original
                        return new originalChartConstructor(context, config);
                    };
                    
                    // 3. Copiar todas as propriedades e métodos do construtor original
                    for (let prop in originalChartConstructor) {
                        if (originalChartConstructor.hasOwnProperty(prop)) {
                            Chart[prop] = originalChartConstructor[prop];
                        }
                    }
                    
                    // 4. Adicionar proteção ao canvas para impedir qualquer evento do mouse
                    const chartCanvas = document.getElementById('salesChart');
                    if (chartCanvas) {
                        // Bloquear todos os eventos do mouse no canvas
                        const blockEvents = function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            return false;
                        };
                        
                        // Eventos do mouse que queremos bloquear
                        const mouseEvents = ['mousemove', 'mouseenter', 'mouseleave', 'mouseover', 'mouseout', 'click'];
                        
                        // Adicionar bloqueadores para cada tipo de evento
                        mouseEvents.forEach(eventType => {
                            chartCanvas.addEventListener(eventType, blockEvents, true);
                        });
                    }
                    
                    // 5. Renderizar o gráfico com o período correto
                    updatingFromUserAction = true;
                    if (currentPeriod === 'custom' && customStartDate && customEndDate) {
                        atualizarDashboard(currentPeriod, customStartDate, customEndDate);
                    } else {
                        atualizarDashboard(currentPeriod);
                    }
                    
                    // 6. Adicionar um verificador de segurança para garantir que o período não mude
                    const safetyInterval = setInterval(() => {
                        const activeBtn = document.querySelector('.period-btn.active');
                        if (activeBtn && activeBtn.dataset.period !== currentPeriod) {
                            console.log('Tentativa de mudança de período detectada e bloqueada');
                            const correctBtn = document.getElementById('period-' + currentPeriod);
                            if (correctBtn) correctBtn.click();
                        }
                    }, 100); // Verificar 10x por segundo
                    
                    // Aplicar proteção adicional para garantir que o gráfico não mude
                    setInterval(() => {
                        // Se o período atual não corresponder ao botão ativo, restaurar
                        const activeButton = document.querySelector('.period-btn.active');
                        if (activeButton && activeButton.dataset.period !== currentPeriod) {
                            console.log('Corrigindo período para:', currentPeriod);
                            // Restaurar o período correto
                            document.getElementById('period-' + currentPeriod)?.click();
                        }
                    }, 500); // Verificar a cada 500ms
                }, 100);
            } else {
                const dailyBtn = document.getElementById('period-daily');
                if (dailyBtn) {
                    dailyBtn.classList.add('active');
                    currentPeriod = 'daily';
                    localStorage.setItem('dashboardPeriod', 'daily');
                }
            }
        }
        
        // Variáveis de controle para gráfico
        let chartLastUpdated = new Date().getTime();
        // Variáveis de controle mouseOverChart e updatingFromUserAction já declaradas no início do script
        
        // Usamos a variável lastChartConfig já declarada no início do script
        
        // Função para criar o gráfico
        function createChart(chartData, forceUpdate = false) {
            // PROTEÇÃO DEFINITIVA: Detectar e bloquear tentativas de mudança não autorizadas
            const storedPeriod = localStorage.getItem('dashboardPeriod');
            
            // Evitar qualquer atualização quando o mouse estiver sobre o gráfico, a menos que seja uma ação do usuário
            if (mouseOverChart && !updatingFromUserAction) {
                console.log('Mouse sobre o gráfico, evitando atualização');
                return false;
            }
            
            // Se já existir um gráfico e não for uma atualização forçada, evitar recriação
            if (!forceUpdate && chartInstance) {
                console.log('Evitando atualização desnecessária do gráfico');
                return false;
            }
            
            // Atualizar timestamp
            chartLastUpdated = new Date().getTime();
            
            // Garantir que os arrays de dados existam
            chartData.labels = chartData.labels || ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'];
            chartData.brutos = chartData.brutos || [1000, 1500, 1200, 1800, 2000, 2200];
            chartData.liquidos = chartData.liquidos || [200, 600, 350, 850, 900, 1000];
            chartData.provisorios = chartData.provisorios || [300, 400, 450, 500, 600, 700];
            chartData.despesas = chartData.despesas || [800, 900, 850, 950, 1100, 1200];
            
            // Definir título com base no período atual
            let chartTitle = 'Resumo financeiro';
            switch(currentPeriod) {
                case 'daily':
                    chartTitle = 'Hoje — valores por hora';
                    break;
                case 'yesterday':
                    chartTitle = 'Ontem — valores por hora';
                    break;
                case 'weekly':
                    chartTitle = 'Essa semana — valores por dia';
                    break;
                case 'monthly':
                    chartTitle = 'Esse mês — valores por dia';
                    break;
                case 'custom':
                    chartTitle = 'Período personalizado — por dia';
                    break;
            }
            
            // Só destruir e recriar o gráfico se for uma ação deliberada (clique em botão) ou forçada
            if (!forceUpdate && chartInstance) {
                // NUNCA atualizar o gráfico automaticamente durante interacão do mouse
                // Ou se não for uma ação explícita do usuário
                if (!updatingFromUserAction) {
                    console.log('Bloqueando atualização não solicitada do gráfico');
                    return;
                }
                chartInstance.destroy();
            }
            
            // Obter o elemento canvas do gráfico
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Criar o gráfico
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Valor Bruto',
                            data: chartData.brutos,
                            backgroundColor: 'rgba(67, 97, 238, 0.8)',
                            borderColor: '#4361ee',
                            borderWidth: 1
                        },
                        {
                            label: 'Valor Líquido',
                            data: chartData.liquidos,
                            backgroundColor: 'rgba(46, 204, 113, 0.8)',
                            borderColor: '#2ecc71',
                            borderWidth: 1
                        },
                        {
                            label: 'Vendas Provisórias',
                            data: chartData.provisorios,
                            backgroundColor: 'rgba(241, 196, 15, 0.8)', // Amarelo
                            borderColor: '#f1c40f',
                            borderWidth: 1
                        },
                        {
                            label: 'Despesas',
                            data: chartData.despesas,
                            backgroundColor: 'rgba(231, 76, 60, 0.8)',
                            borderColor: '#e74c3c',
                            borderWidth: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    // Permitir evento de hover para tooltips, mas de forma controlada
                    hover: { 
                        mode: 'index',
                        intersect: true
                    },
                    // Permitir eventos para tooltips
                    events: ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove'],
                    animation: {
                        duration: 400 // Animação moderada
                    },
                    title: {
                        display: true,
                        text: chartTitle
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        intersect: true,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFontSize: 14,
                        bodyFontSize: 13,
                        callbacks: {
                            // Título personalizado com formato baseado no período
                            title: function(tooltipItems, data) {
                                const item = tooltipItems[0];
                                const label = data.labels[item.index];
                                
                                if (currentPeriod === 'daily' || currentPeriod === 'yesterday') {
                                    return 'Hora: ' + label;
                                }
                                if (currentPeriod === 'weekly' || currentPeriod === 'monthly' || currentPeriod === 'custom') {
                                    const date = new Date(label + (label.length <= 10 ? 'T12:00:00' : ''));
                                    if (!isNaN(date.getTime())) {
                                        return 'Data: ' + date.toLocaleDateString('pt-BR', {
                                            day: '2-digit',
                                            month: '2-digit',
                                            year: 'numeric'
                                        });
                                    }
                                }
                                return 'Período: ' + label;
                            },
                            // Detalhes de cada tipo de valor
                            label: function(tooltipItem, data) {
                                const dataset = data.datasets[tooltipItem.datasetIndex];
                                const value = dataset.data[tooltipItem.index] || 0;
                                const formattedValue = value.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL',
                                    minimumFractionDigits: 2
                                });
                                
                                return dataset.label + ': ' + formattedValue;
                            },
                            // Rodapé com total geral
                            footer: function(tooltipItems, data) {
                                let total = 0;
                                tooltipItems.forEach(function(tooltipItem) {
                                    // Soma apenas os valores brutos e provísorios, não soma despesas
                                    const datasetLabel = data.datasets[tooltipItem.datasetIndex].label;
                                    if (datasetLabel === 'Valor Bruto' || datasetLabel === 'Vendas Provisórias') {
                                        total += data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || 0;
                                    }
                                });
                                
                                return 'Total de Vendas: ' + total.toLocaleString('pt-BR', {
                                    style: 'currency',
                                    currency: 'BRL',
                                    minimumFractionDigits: 2
                                });
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            gridLines: {
                                display: false
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                autoSkip: true,
                                maxTicksLimit: 15
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return 'R$ ' + value.toLocaleString('pt-BR');
                                }
                            }
                        }]
                    }
                }
            });
            
            // Salvando configuração para poder restaurar se necessário
            lastChartConfig = chartData;
            
            // Desabilita eventos de hover para evitar atualizações desnecessárias
            if (chartInstance.options) {
                chartInstance.options.hover = { mode: null };
            }
            
            return chartInstance;
        }
        
        // Criar o gráfico inicial com os dados do backend
        createChart(chartData, true);
        
        // Implementar proteção contra mudança de período mas permitir tooltips
        const salesChartElem = document.getElementById('salesChart');
        
        if (salesChartElem) {
            // Guardar o período antes e depois de interagir com o gráfico
            salesChartElem.addEventListener('mouseenter', function() {
                // Marcar que estamos sobre o gráfico
                mouseOverChart = true;
                
                // Criar uma cópia das configurações para recuperar se necessário
                if (chartInstance && chartInstance.data) {
                    // Manter um registro do período para verificar se muda
                    console.log('Mouse entrou no gráfico - salvando estado atual');
                }
            });
            
            salesChartElem.addEventListener('mouseleave', function() {
                // Marcar que saiu do gráfico
                mouseOverChart = false;
                console.log('Mouse saiu do gráfico');
            });
            
            // Adicionar proteção extra para garantir que o gráfico permaneça no período selecionado
            setInterval(function() {
                // Verificar se o período atual está consistente com o botão ativo
                const activePeriodBtn = document.querySelector('.period-btn.active');
                if (activePeriodBtn && activePeriodBtn.dataset.period !== currentPeriod) {
                    console.log('Inconsistência detectada: período atual não corresponde ao botão ativo');
                    document.getElementById('period-' + currentPeriod)?.click();
                }
            }, 300);
        }
    });
</script>
@endsection
