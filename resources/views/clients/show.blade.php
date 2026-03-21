@extends('layouts.app')

@section('title', 'Cliente — '.$cliente->nome)

@section('styles')
<style>
    .cliente-show-page {
        --cs-radius: 20px;
        --cs-radius-sm: 14px;
        --cs-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
        --cs-shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.18);
    }

    /* Hero */
    .cliente-show-hero {
        position: relative;
        border-radius: var(--cs-radius);
        overflow: hidden;
        background: linear-gradient(145deg, #ffffff 0%, #f4f4f5 50%, #ececee 100%);
        border: 1px solid rgba(0, 0, 0, 0.08);
        box-shadow: var(--cs-shadow);
    }
    .cliente-show-hero__glow {
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 0, 0, 0.06) 0%, transparent 70%);
        top: -180px;
        right: -120px;
        pointer-events: none;
    }
    .cliente-show-hero__glow2 {
        position: absolute;
        width: 280px;
        height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0, 0, 0, 0.04) 0%, transparent 70%);
        bottom: -100px;
        left: -80px;
        pointer-events: none;
    }
    .cliente-show-hero__bar {
        height: 5px;
        background: linear-gradient(90deg, #0a0a0a, #404040, #0a0a0a);
    }
    .cliente-show-hero__inner {
        position: relative;
        z-index: 1;
        padding: 2rem 1.5rem 2.25rem;
    }
    @media (min-width: 768px) {
        .cliente-show-hero__inner {
            padding: 2.5rem 2rem 2.65rem;
        }
    }
    @media (min-width: 1200px) {
        .cliente-show-hero__inner {
            padding: 2.65rem 2.5rem 2.85rem;
        }
    }

    /* Layout hero: coluna no mobile (espaço generoso); linha no desktop com botões à direita */
    .cliente-show-hero-layout {
        display: flex;
        flex-direction: column;
        align-items: stretch;
        gap: 1.75rem;
    }
    @media (min-width: 576px) {
        .cliente-show-hero-layout {
            gap: 2rem;
        }
    }
    @media (min-width: 992px) {
        .cliente-show-hero-layout {
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: space-between;
            gap: 2rem 2.5rem;
        }
    }

    .cliente-show-hero-main {
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 1.1rem;
        min-width: 0;
        flex: 1;
    }
    @media (min-width: 576px) {
        .cliente-show-hero-main {
            gap: 1.35rem;
        }
    }
    @media (min-width: 992px) {
        .cliente-show-hero-main {
            gap: 1.5rem;
            align-items: center;
        }
    }

    .cliente-show-hero-text {
        display: flex;
        flex-direction: column;
        gap: 1.15rem;
        min-width: 0;
    }
    @media (min-width: 576px) {
        .cliente-show-hero-text {
            gap: 1.35rem;
        }
    }
    @media (min-width: 992px) {
        .cliente-show-hero-text {
            gap: 1rem;
        }
    }

    .cliente-show-hero-actions {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 0.65rem 0.85rem;
        padding-top: 1.5rem;
        margin-top: 0.15rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }
    @media (min-width: 576px) {
        .cliente-show-hero-actions {
            gap: 0.75rem 1rem;
            padding-top: 1.65rem;
        }
    }
    @media (min-width: 992px) {
        .cliente-show-hero-actions {
            flex-shrink: 0;
            flex-wrap: wrap;
            justify-content: flex-end;
            max-width: min(100%, 420px);
            padding-top: 0;
            margin-top: 0;
            border-top: none;
            gap: 0.65rem 0.9rem;
        }
    }

    @media (max-width: 575.98px) {
        .cliente-show-hero-actions .btn {
            flex: 1 1 calc(50% - 0.5rem);
            min-width: 140px;
        }
        .cliente-show-hero-actions .btn:last-child:nth-child(odd) {
            flex: 1 1 100%;
        }
    }
    .cliente-show-avatar {
        width: 80px;
        height: 80px;
        border-radius: 22px;
        background: linear-gradient(145deg, #1a1a1a 0%, #0a0a0a 100%);
        color: #fff;
        font-size: 2rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.25);
        line-height: 1;
    }
    .cliente-show-title {
        font-size: clamp(1.35rem, 4vw, 1.85rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        color: #1a1a2e;
        line-height: 1.25;
        margin: 0;
        padding-bottom: 0.05rem;
    }
    .cliente-show-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.6rem 0.75rem;
        align-items: center;
    }
    @media (min-width: 576px) {
        .cliente-show-meta {
            gap: 0.7rem 1rem;
        }
    }
    .cliente-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8125rem;
        font-weight: 600;
        padding: 0.55rem 1.05rem;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.06);
        color: #0a0a0a;
        line-height: 1.35;
    }
    .cliente-pill--muted {
        background: rgba(0, 0, 0, 0.05);
        color: #5c5c6f;
    }

    /* KPIs financeiros — todo texto branco (sobrescreve tema / Bootstrap) */
    .cliente-kpi-finance {
        position: relative;
        border-radius: var(--cs-radius-sm);
        border: none;
        overflow: hidden;
        min-height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .cliente-kpi-finance::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 45%);
        pointer-events: none;
    }
    .cliente-kpi-finance:hover {
        transform: translateY(-4px);
        box-shadow: var(--cs-shadow-lg);
    }
    .cliente-kpi-finance .card-body {
        position: relative;
        z-index: 1;
        padding: 1.35rem 1.4rem !important;
    }
    @media (min-width: 992px) {
        .cliente-kpi-finance .card-body {
            padding: 1.6rem 1.85rem !important;
        }
    }
    .cliente-kpi-finance,
    .cliente-kpi-finance .card-body,
    .cliente-kpi-finance .kpi-finance-label,
    .cliente-kpi-finance .kpi-finance-value,
    .cliente-kpi-finance .kpi-finance-hint,
    .cliente-kpi-finance .kpi-finance-icon {
        color: #ffffff !important;
    }
    .cliente-kpi-finance .kpi-finance-hint {
        opacity: 0.9;
    }
    .cliente-kpi-finance .kpi-finance-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        opacity: 0.92;
        margin-bottom: 0.65rem;
    }
    .cliente-kpi-finance .kpi-finance-value {
        font-size: clamp(1.15rem, 3.5vw, 1.5rem);
        font-weight: 800;
        letter-spacing: -0.02em;
        line-height: 1.35;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.12);
        margin-bottom: 0.15rem;
    }
    .cliente-kpi-finance .kpi-finance-hint {
        font-size: 0.75rem;
        margin-top: 0.75rem;
        font-weight: 500;
        line-height: 1.4;
    }
    .cliente-kpi-finance .kpi-finance-icon-wrap {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        background: rgba(255, 255, 255, 0.22);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
        backdrop-filter: blur(6px);
    }
    .cliente-kpi-finance .d-flex.align-items-start {
        gap: 1rem !important;
    }

    /* Blocos de KPI em 2 colunas (linha financeiro / linha datas) */
    .cliente-kpi-rows {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    @media (min-width: 768px) {
        .cliente-kpi-rows {
            gap: 2rem;
        }
    }
    .cliente-kpi-pair {
        --bs-gutter-x: 1.25rem;
        --bs-gutter-y: 1.25rem;
    }
    @media (min-width: 768px) {
        .cliente-kpi-pair {
            --bs-gutter-x: 1.75rem;
            --bs-gutter-y: 1.5rem;
        }
    }

    /* KPIs datas */
    .cliente-kpi-date {
        border-radius: var(--cs-radius-sm);
        border: 1px solid rgba(0, 0, 0, 0.06);
        background: #fff;
        box-shadow: var(--cs-shadow);
        height: 100%;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .cliente-kpi-date:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 36px rgba(36, 32, 56, 0.1);
    }
    .cliente-kpi-date .kpi-date-accent {
        width: 4px;
        border-radius: 4px;
        align-self: stretch;
        min-height: 52px;
    }
    .cliente-kpi-date .card-body-inner {
        padding: 0.15rem 0 0;
    }
    .cliente-kpi-date .kpi-date-title {
        font-size: 0.65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #8898a8;
        margin-bottom: 0.65rem;
    }
    .cliente-kpi-date .kpi-date-main {
        font-size: 1.2rem;
        font-weight: 800;
        line-height: 1.3;
        margin-bottom: 0.35rem;
    }
    .cliente-kpi-date .kpi-date-sub {
        font-size: 0.875rem;
        color: #718096;
        line-height: 1.4;
    }
    .cliente-kpi-date.card {
        padding: 1.35rem 1.4rem !important;
    }
    @media (min-width: 992px) {
        .cliente-kpi-date.card {
            padding: 1.6rem 1.85rem !important;
        }
    }

    /* Dados */
    .cliente-section-card {
        border-radius: var(--cs-radius);
        border: 1px solid rgba(0, 0, 0, 0.06);
        box-shadow: var(--cs-shadow);
        overflow: hidden;
        background: #fff;
    }
    .cliente-section-card .card-header {
        background: linear-gradient(180deg, #fafbff 0%, #fff 100%);
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        font-weight: 800;
        font-size: 0.95rem;
        padding: 1rem 1.35rem;
        letter-spacing: -0.01em;
    }
    .cliente-section-head {
        display: flex;
        align-items: center;
        gap: 0.65rem;
    }
    .cliente-section-head i {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.08), rgba(0, 0, 0, 0.04));
        color: #0a0a0a;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 0.95rem;
    }
    .cliente-subsection-title {
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #8898a8;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
    }
    .cliente-info-tile {
        display: flex;
        gap: 1rem;
        padding: 1rem 1.1rem;
        border-radius: var(--cs-radius-sm);
        background: linear-gradient(180deg, #fafbfd 0%, #fff 100%);
        border: 1px solid rgba(0, 0, 0, 0.05);
        margin-bottom: 0.75rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .cliente-info-tile:last-child {
        margin-bottom: 0;
    }
    .cliente-info-tile:hover {
        border-color: rgba(0, 0, 0, 0.12);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06);
    }
    .cliente-info-tile .ci-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        background: linear-gradient(145deg, rgba(0, 0, 0, 0.1), rgba(0, 0, 0, 0.04));
        color: #0a0a0a;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 1rem;
    }
    .cliente-info-tile .ci-label {
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        font-weight: 800;
        color: #8b9bab;
        margin-bottom: 0.2rem;
    }
    .cliente-info-tile .ci-value {
        font-size: 0.98rem;
        font-weight: 600;
        color: #2d3748;
        word-break: break-word;
        line-height: 1.45;
    }
    .cliente-info-tile .ci-value a {
        color: var(--primary);
        font-weight: 700;
    }
    .cliente-empty-map {
        border-radius: var(--cs-radius-sm);
        padding: 2rem 1.5rem;
        text-align: center;
        background: linear-gradient(180deg, #f8fafc 0%, #fff 100%);
        border: 2px dashed rgba(0, 0, 0, 0.15);
    }

    /* Tabela */
    .cliente-table-wrap {
        margin: 0;
    }
    .cliente-table-wrap thead th {
        font-size: 0.68rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        font-weight: 800;
        color: #718096 !important;
        border-bottom-width: 1px;
        padding-top: 1rem;
        padding-bottom: 1rem;
        background: #f8fafc !important;
    }
    .cliente-table-wrap tbody tr {
        transition: background 0.15s;
    }
    .cliente-table-wrap tbody tr:hover {
        background: rgba(0, 0, 0, 0.03);
    }
    .cliente-table-code {
        font-family: ui-monospace, monospace;
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--primary);
    }

    /* Mobile vendas */
    .cliente-venda-mobile {
        border-radius: var(--cs-radius-sm);
        padding: 1.1rem 1.15rem;
        margin-bottom: 0.85rem;
        background: #fff;
        border: 1px solid rgba(0, 0, 0, 0.07);
        box-shadow: 0 2px 12px rgba(36, 32, 56, 0.05);
        position: relative;
        overflow: hidden;
    }
    .cliente-venda-mobile::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #0a0a0a, #525252);
        opacity: 0.85;
    }
    .cliente-venda-mobile:last-child {
        margin-bottom: 0;
    }

    .cliente-empty-map .text-primary {
        opacity: 0.45;
    }

    @media (max-width: 575.98px) {
        .cliente-show-avatar {
            width: 64px;
            height: 64px;
            font-size: 1.5rem;
            border-radius: 18px;
        }
    }
</style>
@endsection

@section('content')
@php
    use Illuminate\Support\Str;
    $concluidas = $vendas->where('status', 'concluida')->sortBy('data');
    $nomeTrim = trim((string) $cliente->nome) ?: '?';
    $inicial = Str::upper(Str::substr($nomeTrim, 0, 1));
    $temEndereco = $cliente->cep || $cliente->endereco || $cliente->cidade || $cliente->estado;
    $cidadeUf = trim(($cliente->cidade ?: '').' / '.($cliente->estado ?: ''), ' /');
    $nVendas = count($linhasVendas);
    $nConcluidas = $concluidas->count();
    $ticketMedio = $nConcluidas > 0 ? $totalGasto / $nConcluidas : 0;
@endphp

<div class="container-fluid px-4 cliente-show-page pb-5">
    <ol class="breadcrumb mb-3 small">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($cliente->nome, 42) }}</li>
    </ol>

    {{-- Hero --}}
    <div class="cliente-show-hero mb-4">
        <div class="cliente-show-hero__bar"></div>
        <div class="cliente-show-hero__glow" aria-hidden="true"></div>
        <div class="cliente-show-hero__glow2" aria-hidden="true"></div>
        <div class="cliente-show-hero__inner">
            <div class="cliente-show-hero-layout">
                <div class="cliente-show-hero-main">
                    <div class="cliente-show-avatar" aria-hidden="true">{{ $inicial }}</div>
                    <div class="cliente-show-hero-text">
                        <h1 class="cliente-show-title text-break">{{ $cliente->nome }}</h1>
                        <div class="cliente-show-meta">
                            <span class="cliente-pill">
                                <i class="fas fa-user-check" aria-hidden="true"></i>
                                <span>Cliente ativo</span>
                            </span>
                            @if($cliente->data_cadastro)
                                <span class="cliente-pill cliente-pill--muted">
                                    <i class="far fa-calendar-alt" aria-hidden="true"></i>
                                    <span>Desde <time datetime="{{ $cliente->data_cadastro->toIso8601String() }}">{{ $cliente->data_cadastro->format('d/m/Y') }}</time></span>
                                </span>
                            @endif
                            @if($vendas->isNotEmpty())
                                <span class="cliente-pill cliente-pill--muted">
                                    <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                                    <span>{{ $vendas->count() }} {{ $vendas->count() === 1 ? 'pedido' : 'pedidos' }}</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="cliente-show-hero-actions">
                    <a href="{{ route('clients.edit', $cliente) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-edit"></i> Editar perfil
                    </a>
                    @if($cliente->whatsappUrl())
                        <a href="{{ $cliente->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-sm">
                            <i class="fa-brands fa-whatsapp"></i> Conversar
                        </a>
                    @endif
                    <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-list"></i> Todos os clientes
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- KPIs: linha 1 = financeiro (lado a lado); linha 2 = datas (lado a lado) --}}
    <div class="cliente-kpi-rows mb-4">
        <div class="row cliente-kpi-pair g-3 g-md-4">
            <div class="col-12 col-md-6">
                <div class="card cliente-kpi-finance cliente-show-kpi h-100" style="background: linear-gradient(145deg, #2a2a2a 0%, #171717 55%, #0a0a0a 100%);">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="kpi-finance-icon-wrap kpi-finance-icon" aria-hidden="true">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="kpi-finance-label">Total gasto</div>
                                <div class="kpi-finance-value text-break">R$ {{ number_format($totalGasto, 2, ',', '.') }}</div>
                                <div class="kpi-finance-hint">Soma das vendas concluídas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card cliente-kpi-finance cliente-show-kpi h-100" style="background: linear-gradient(145deg, #404040 0%, #2a2a2a 50%, #1a1a1a 100%);">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="kpi-finance-icon-wrap kpi-finance-icon" aria-hidden="true">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <div class="min-w-0 flex-grow-1">
                                <div class="kpi-finance-label">Lucro gerado</div>
                                <div class="kpi-finance-value text-break">R$ {{ number_format($totalLucro, 2, ',', '.') }}</div>
                                <div class="kpi-finance-hint">Estimativa nas vendas concluídas</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row cliente-kpi-pair g-3 g-md-4">
            <div class="col-12 col-md-6">
                <div class="card cliente-kpi-date h-100">
                    <div class="d-flex gap-3 align-items-stretch">
                        <div class="kpi-date-accent" style="background: linear-gradient(180deg, #0a0a0a, #525252);"></div>
                        <div class="min-w-0 flex-grow-1 card-body-inner py-1">
                            <div class="kpi-date-title">1ª compra</div>
                            @if($concluidas->isNotEmpty())
                                <div class="kpi-date-main text-dark">{{ $concluidas->first()->data->format('d/m/Y') }}</div>
                                <div class="kpi-date-sub">{{ $concluidas->first()->data->format('H:i') }}</div>
                            @else
                                <div class="kpi-date-main text-muted">—</div>
                                <div class="kpi-date-sub">Sem compras concluídas</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6">
                <div class="card cliente-kpi-date h-100">
                    <div class="d-flex gap-3 align-items-stretch">
                        <div class="kpi-date-accent" style="background: linear-gradient(180deg, #404040, #737373);"></div>
                        <div class="min-w-0 flex-grow-1 card-body-inner py-1">
                            <div class="kpi-date-title">Última compra</div>
                            @if($concluidas->isNotEmpty())
                                <div class="kpi-date-main text-dark">{{ $concluidas->last()->data->format('d/m/Y') }}</div>
                                <div class="kpi-date-sub">{{ $concluidas->last()->data->format('H:i') }}</div>
                            @else
                                <div class="kpi-date-main text-muted">—</div>
                                <div class="kpi-date-sub">Sem compras concluídas</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($nConcluidas > 0)
        <div class="alert alert-light border mb-4 py-3 px-4 d-flex flex-wrap align-items-center gap-2" style="border-radius: 14px; background: linear-gradient(90deg, rgba(0,0,0,0.04), rgba(255,255,255,0.95));">
            <span class="fw-bold text-primary"><i class="fas fa-receipt me-2"></i>Ticket médio (concluídas)</span>
            <span class="ms-sm-2 fw-bolder fs-5 text-dark">R$ {{ number_format($ticketMedio, 2, ',', '.') }}</span>
            <span class="text-muted small ms-sm-auto">Total ÷ {{ $nConcluidas }} {{ $nConcluidas === 1 ? 'venda' : 'vendas' }}</span>
        </div>
    @endif

    {{-- Dados --}}
    <div class="card cliente-section-card mb-4">
        <div class="card-header">
            <div class="cliente-section-head">
                <i class="fas fa-id-card-alt"></i>
                <span>Informações de contato e localização</span>
            </div>
        </div>
        <div class="card-body p-3 p-md-4 p-lg-5">
            <div class="row g-4 g-xl-5">
                <div class="col-12 col-lg-6">
                    <div class="cliente-subsection-title">Contato</div>
                    <div class="cliente-info-tile">
                        <div class="ci-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <div class="ci-label">E-mail</div>
                            <div class="ci-value">
                                @if($cliente->email)
                                    <a href="mailto:{{ $cliente->email }}" class="text-decoration-none">{{ $cliente->email }}</a>
                                @else
                                    <span class="text-muted fw-normal">Não informado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="cliente-info-tile">
                        <div class="ci-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <div class="ci-label">Telefone</div>
                            <div class="ci-value">
                                @if($t = \App\Support\BrFormat::telefoneDisplay($cliente->telefone))
                                    {{ $t }}
                                @else
                                    <span class="text-muted fw-normal">Não informado</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="cliente-info-tile">
                        <div class="ci-icon"><i class="fas fa-fingerprint"></i></div>
                        <div>
                            <div class="ci-label">CPF / CNPJ</div>
                            <div class="ci-value">
                                {{ \App\Support\BrFormat::cpfCnpjDisplay($cliente->cpf_cnpj) ?: 'Não informado' }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="cliente-subsection-title">Endereço</div>
                    @if($temEndereco)
                        <div class="cliente-info-tile">
                            <div class="ci-icon"><i class="fas fa-road"></i></div>
                            <div>
                                <div class="ci-label">Logradouro</div>
                                <div class="ci-value">{{ $cliente->endereco ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="cliente-info-tile">
                            <div class="ci-icon"><i class="fas fa-city"></i></div>
                            <div>
                                <div class="ci-label">Cidade / UF</div>
                                <div class="ci-value">{{ $cidadeUf ?: '—' }}</div>
                            </div>
                        </div>
                        <div class="cliente-info-tile">
                            <div class="ci-icon"><i class="fas fa-mail-bulk"></i></div>
                            <div>
                                <div class="ci-label">CEP</div>
                                <div class="ci-value">{{ \App\Support\BrFormat::cepDisplay($cliente->cep) ?: '—' }}</div>
                            </div>
                        </div>
                    @else
                        <div class="cliente-empty-map">
                            <i class="fas fa-map-marked-alt fa-3x text-primary mb-3 d-block"></i>
                            <p class="fw-bold text-dark mb-1">Sem endereço cadastrado</p>
                            <p class="text-muted small mb-0">Você pode incluir depois em &quot;Editar perfil&quot;.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Histórico --}}
    <div class="card cliente-section-card mb-4">
        <div class="card-header d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <div class="cliente-section-head">
                <i class="fas fa-history"></i>
                <span>Histórico de compras</span>
            </div>
            <span class="badge rounded-pill px-3 py-2 fw-bold align-self-start align-self-sm-center" style="background: linear-gradient(135deg, rgba(0,0,0,0.08), rgba(0,0,0,0.04)); color: #0a0a0a; font-size: 0.8rem; border: 1px solid rgba(0,0,0,0.1);">
                {{ $nVendas }} {{ $nVendas === 1 ? 'registro' : 'registros' }}
            </span>
        </div>
        <div class="card-body p-0">
            @if(empty($linhasVendas))
                <div class="text-center py-5 px-4 cliente-empty-vendas">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-4 shadow-sm" style="width: 96px; height: 96px;">
                        <i class="fas fa-shopping-cart fa-3x text-primary"></i>
                    </div>
                    <p class="fw-bold text-dark mb-1">Nenhuma venda por aqui</p>
                    <p class="text-muted small mb-0 mx-auto" style="max-width: 320px;">Quando este cliente aparecer em vendas, o histórico será listado automaticamente.</p>
                </div>
            @else
                <div class="d-none d-md-block table-responsive cliente-table-wrap">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Código</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Vendedor</th>
                                <th class="text-end">Total</th>
                                <th class="text-end">Lucro (est.)</th>
                                <th class="text-center pe-4">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($linhasVendas as $linha)
                                @php $v = $linha['venda']; @endphp
                                <tr>
                                    <td class="ps-4"><span class="cliente-table-code">{{ $v->codigo }}</span></td>
                                    <td>
                                        <span class="fw-semibold text-nowrap">{{ $v->data->format('d/m/Y') }}</span>
                                        <span class="d-block small text-muted">{{ $v->data->format('H:i') }}</span>
                                    </td>
                                    <td>
                                        @if($v->status === 'concluida')
                                            <span class="badge rounded-pill" style="background: #0a0a0a; color: #fff;">Concluída</span>
                                        @elseif($v->status === 'pendente')
                                            <span class="badge rounded-pill bg-warning text-dark">Pendente</span>
                                        @elseif($v->status === 'provisoria')
                                            <span class="badge rounded-pill bg-info text-dark">Provisória</span>
                                        @else
                                            <span class="badge rounded-pill bg-danger">Cancelada</span>
                                        @endif
                                    </td>
                                    <td class="text-break small">{{ $v->usuario->name ?? '—' }}</td>
                                    <td class="text-end fw-bold">R$ {{ number_format($v->valor_total, 2, ',', '.') }}</td>
                                    <td class="text-end fw-semibold" style="color: #0a0a0a;">R$ {{ number_format($linha['lucro'], 2, ',', '.') }}</td>
                                    <td class="text-center pe-4">
                                        <a href="{{ route('sales.show', $v) }}" class="btn btn-sm btn-primary rounded-pill px-3">Abrir</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-md-none p-3 p-sm-4">
                    @foreach($linhasVendas as $linha)
                        @php $v = $linha['venda']; @endphp
                        <div class="cliente-venda-mobile">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2 pt-1">
                                <div>
                                    <span class="cliente-table-code">{{ $v->codigo }}</span>
                                    <div class="small text-muted mt-1">{{ $v->data->format('d/m/Y · H:i') }}</div>
                                </div>
                                @if($v->status === 'concluida')
                                    <span class="badge rounded-pill" style="background: #0a0a0a; color: #fff;">Concluída</span>
                                @elseif($v->status === 'pendente')
                                    <span class="badge rounded-pill bg-warning text-dark">Pendente</span>
                                @elseif($v->status === 'provisoria')
                                    <span class="badge rounded-pill bg-info text-dark">Provisória</span>
                                @else
                                    <span class="badge rounded-pill bg-danger">Cancelada</span>
                                @endif
                            </div>
                            <div class="small text-muted mb-3"><i class="fas fa-user-tie me-1 opacity-75"></i>{{ $v->usuario->name ?? '—' }}</div>
                            <div class="row g-2 text-center">
                                <div class="col-6">
                                    <div class="rounded-3 py-2 px-2" style="background: #f8fafc;">
                                        <div class="text-muted" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.06em;">TOTAL</div>
                                        <div class="fw-bold">R$ {{ number_format($v->valor_total, 2, ',', '.') }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="rounded-3 py-2 px-2" style="background: #f4f4f5;">
                                        <div class="text-muted" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.06em;">LUCRO</div>
                                        <div class="fw-bold" style="color: #0a0a0a;">R$ {{ number_format($linha['lucro'], 2, ',', '.') }}</div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('sales.show', $v) }}" class="btn btn-primary btn-sm w-100 mt-3 rounded-pill fw-bold">Ver venda completa</a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Pronto',
    text: @json(session('success')),
    timer: 3200,
    showConfirmButton: false
});
</script>
@endif
@endsection
