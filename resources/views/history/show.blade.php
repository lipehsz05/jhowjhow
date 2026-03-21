@extends('layouts.app')

@section('title', 'Detalhes da Venda')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Detalhes da Venda</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('history.index') }}">Histórico</a></li>
        <li class="breadcrumb-item active">Venda #{{ $venda->id }}</li>
    </ol>
    
    <div class="card shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Venda #{{ $venda->codigo ?? str_pad($venda->id, 6, '0', STR_PAD_LEFT) }}
            </h5>
            <div>
                <a href="{{ route('history.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Voltar
                </a>
                <a href="#" onclick="window.print()" class="btn btn-outline-primary btn-sm ms-1">
                    <i class="fas fa-print me-1"></i>Imprimir
                </a>
            </div>
        </div>
        
        <div class="card-body">
            <!-- Informações Básicas -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-file-invoice me-2"></i>Informações da Venda
                            </h6>
                            <div class="info-group mb-2">
                                <label class="fw-bold">Data:</label>
                                <span>{{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-group mb-2">
                                <label class="fw-bold">Código:</label>
                                <span>{{ $venda->codigo ?? str_pad($venda->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </div>
                            <div class="info-group mb-2">
                                <label class="fw-bold">Status:</label>
                                <span class="badge {{ $venda->status == 'concluida' ? 'bg-success' : ($venda->status == 'provisoria' ? 'bg-primary' : 'bg-danger') }}">
                                    {{ ucfirst($venda->status) }}
                                </span>
                            </div>
                            <div class="info-group mb-2">
                                <label class="fw-bold">Forma de Pagamento:</label>
                                <span class="text-capitalize">{{ $venda->forma_pagamento }}</span>
                            </div>
                            <div class="info-group">
                                <label class="fw-bold">Vendedor:</label>
                                <span>{{ $venda->usuario->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-user me-2"></i>Informações do Cliente
                            </h6>
                            @if ($venda->cliente)
                            <div class="info-group mb-2">
                                <label class="fw-bold">Nome:</label>
                                <span>{{ $venda->cliente->nome }}</span>
                            </div>
                            <div class="info-group mb-2">
                                <label class="fw-bold">E-mail:</label>
                                <span>{{ $venda->cliente->email }}</span>
                            </div>
                            <div class="info-group">
                                <label class="fw-bold">Telefone:</label>
                                <span>{{ $venda->cliente->telefone ?? 'N/A' }}</span>
                            </div>
                            @else
                            <p class="text-muted fst-italic">Venda realizada sem cliente registrado</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Observação -->
            @if ($venda->observacao)
            <div class="mb-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-2">
                            <i class="fas fa-comment me-2"></i>Observações
                        </h6>
                        <p class="mb-0">{{ $venda->observacao }}</p>
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Itens da Venda -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-shopping-cart me-2"></i>Itens da Venda
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produto</th>
                                    <th class="text-center">Qtd</th>
                                    <th class="text-end">Preço Unit.</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venda->itens as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->produto->nome }}</td>
                                    <td class="text-center">{{ $item->quantidade }}</td>
                                    <td class="text-end">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                                    <td class="text-end">R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Resumo Financeiro -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-calculator me-2"></i>Resumo Financeiro
                            </h6>
                            <table class="table table-sm">
                                <tbody>
                                    <tr>
                                        <td class="text-end fw-bold">Subtotal:</td>
                                        <td class="text-end">R$ {{ number_format($venda->valor_total + $venda->desconto, 2, ',', '.') }}</td>
                                    </tr>
                                    @if ($venda->desconto > 0)
                                    <tr>
                                        <td class="text-end fw-bold">Desconto:</td>
                                        <td class="text-end text-danger">- R$ {{ number_format($venda->desconto, 2, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    <tr class="table-active">
                                        <td class="text-end fw-bold">Total:</td>
                                        <td class="text-end fw-bold fs-5">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>Registrada em {{ $venda->created_at->format('d/m/Y H:i') }}
                </small>
                
                <div class="d-flex gap-2">
                    @if ($venda->status == 'provisoria')
                    <form action="{{ route('sales.updateStatus', $venda->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="concluida">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="fas fa-check me-1"></i>Definir como Pago
                        </button>
                    </form>
                    @elseif ($venda->status == 'concluida')
                    <form action="{{ route('sales.updateStatus', $venda->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="provisoria">
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-clock me-1"></i>Definir como Provisória
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Estilos para impressão -->
<style>
@media print {
    nav, .sidebar, .breadcrumb, .card-header, .card-footer, footer {
        display: none !important;
    }
    
    body {
        background-color: white !important;
    }
    
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    h1 {
        margin-top: 0 !important;
    }
    
    .shadow-sm {
        box-shadow: none !important;
    }
}
</style>
@endsection
