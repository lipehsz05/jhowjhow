@extends('layouts.app')

@section('title', 'Vendas')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gerenciamento de Vendas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Vendas</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-cash-register me-1"></i>
                    Vendas
                </div>
                <a href="{{ route('sales.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nova Venda
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <div class="table-responsive table-list-desktop">
                <table class="table table-bordered table-hover" id="vendasTable">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Valor Total</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendas ?? [] as $venda)
                            <tr>
                                <td>{{ $venda->codigo }}</td>
                                <td>{{ $venda->data->format('d/m/Y H:i') }}</td>
                                <td>{{ $venda->cliente->nome ?? 'Cliente não registrado' }}</td>
                                <td>{{ $venda->usuario->name ?? 'Vendedor não identificado' }}</td>
                                <td>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    @if($venda->status == 'concluida')
                                        <span class="badge bg-success">Concluída</span>
                                    @elseif($venda->status == 'pendente')
                                        <span class="badge bg-warning text-dark">Pendente</span>
                                    @elseif($venda->status == 'provisoria')
                                        <span class="badge bg-info">Provisória</span>
                                    @elseif($venda->status == 'cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('sales.show', $venda->id) }}" class="btn btn-info btn-sm me-2">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </a>
                                        @if($venda->status != 'cancelada')
                                            <form action="{{ route('sales.destroy', $venda->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar esta venda?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-ban"></i> Cancelar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma venda registrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-list-mobile">
                @include('sales.partials.mobile-cards', ['vendas' => $vendas ?? []])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var $t = $('#vendasTable');
        var opts = {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            responsive: true,
            order: [[1, 'desc']]
        };
        function syncVendasDt() {
            if (window.matchMedia('(min-width: 992px)').matches) {
                if (!$.fn.DataTable.isDataTable($t)) {
                    $t.DataTable(opts);
                }
            } else if ($.fn.DataTable.isDataTable($t)) {
                $t.DataTable().destroy();
            }
        }
        syncVendasDt();
        var deb;
        $(window).on('resize', function () {
            clearTimeout(deb);
            deb = setTimeout(syncVendasDt, 200);
        });
    });
</script>
@endsection
