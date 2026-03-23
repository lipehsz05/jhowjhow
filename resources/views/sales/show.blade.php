@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-receipt me-2"></i>Detalhes da Venda #{{ $venda->codigo ?? str_pad($venda->id, 6, '0', STR_PAD_LEFT) }}
            </h5>
            <div>
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-sm">
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
                    <h6 class="text-muted mb-3">Informações da Venda</h6>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Data:</label>
                        <span>{{ \Carbon\Carbon::parse($venda->data)->format('d/m/Y H:i') }}</span>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Status:</label>
                        <span class="badge {{ $venda->status == 'concluida' ? 'bg-success' : 'bg-primary' }}">
                            {{ ucfirst($venda->status) }}
                        </span>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Forma de Pagamento:</label>
                        <span class="text-capitalize">{{ $venda->forma_pagamento }}</span>
                    </div>
                    <div class="info-group mb-2">
                        <label class="fw-bold">Vendedor:</label>
                        <span>{{ $venda->usuario->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-group">
                        <label class="fw-bold">Código:</label>
                        <span>{{ $venda->codigo ?? str_pad($venda->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Informações do Cliente</h6>
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
            
            <!-- Observação -->
            @if ($venda->observacao)
            <div class="mb-4">
                <h6 class="text-muted mb-2">Observações</h6>
                <div class="p-3 bg-light rounded">
                    {{ $venda->observacao }}
                </div>
            </div>
            @endif
            
            <!-- Itens da Venda -->
            <h6 class="text-muted mb-3">Itens da Venda</h6>
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
                            <td>{{ $item->produto->nome }}@if($item->produto->tamanho) <span class="text-muted">(tam. {{ $item->produto->tamanho }})</span>@endif</td>
                            <td class="text-center">{{ $item->quantidade }}</td>
                            <td class="text-end">R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</td>
                            <td class="text-end">R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Resumo Financeiro -->
            <div class="row mt-4">
                <div class="col-md-6 offset-md-6">
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
                                <td class="text-end fw-bold">R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-calendar-alt me-1"></i>Criado em: {{ $venda->created_at->format('d/m/Y H:i') }}
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
                    
                    @if ($venda->status != 'cancelada')
                    <form action="{{ route('sales.destroy', $venda->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar esta venda?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">
                            <i class="fas fa-times me-1"></i>Cancelar Venda
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
    .btn, nav, .sidebar, .card-header, .card-footer, footer {
        display: none !important;
    }
    
    body {
        background-color: white !important;
    }
    
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    
    .container {
        max-width: 100% !important;
        width: 100% !important;
    }
    
    .card-body {
        padding: 0 !important;
    }
}
</style>
@endsection
