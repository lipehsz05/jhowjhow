@extends('layouts.app')

@section('title', 'Histórico')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Histórico de Vendas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Histórico</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-history me-1"></i>
                    Histórico de Vendas e Operações
                </div>
                <div>
                    <form action="{{ route('history.index') }}" method="GET" class="d-flex gap-2">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">De</span>
                            <input type="date" name="data_inicio" class="form-control form-control-sm" 
                                value="{{ request('data_inicio', date('Y-m-d', strtotime('-30 days'))) }}">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">Até</span>
                            <input type="date" name="data_fim" class="form-control form-control-sm" 
                                value="{{ request('data_fim', date('Y-m-d')) }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fas fa-filter"></i> Filtrar
                        </button>
                    </form>
                </div>
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
                <table class="table table-bordered table-hover" id="historicoTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Código</th>
                            <th>Data</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th>Valor</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendas ?? [] as $item)
                             <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->codigo ?? str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y H:i') }}</td>
                                <td>{{ $item->cliente->nome ?? 'N/A' }}</td>
                                <td>{{ $item->usuario->name ?? 'N/A' }}</td>
                                <td>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</td>
                                <td>
                                    @if($item->status == 'concluida')
                                        <span class="badge bg-success">Concluída</span>
                                    @elseif($item->status == 'provisoria')
                                        <span class="badge bg-primary">Provisória</span>
                                    @elseif($item->status == 'cancelada')
                                        <span class="badge bg-danger">Cancelada</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('history.show', $item->id) }}" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> Detalhes
                                        </a>
                                        @if($item->status == 'provisoria')
                                        <a href="{{ route('sales.show', $item->id) }}" class="btn btn-success btn-sm">
                                            <i class="fas fa-check"></i> Pagar
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhum registro encontrado no período selecionado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-list-mobile">
                @include('history.partials.mobile-cards', ['vendas' => $vendas ?? []])
            </div>
            
            <!-- Paginação -->
            <div class="mt-4 d-flex justify-content-center">
                {{ $vendas->appends(request()->except('page'))->links() }}
            </div>
            
            <!-- Resumo -->
            <div class="mt-4 card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <h5 class="mb-0">Total de Vendas</h5>
                            <p class="lead">{{ $totalVendas ?? 0 }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-0">Valor Total</h5>
                            <p class="lead">R$ {{ number_format($somaTotal ?? 0, 2, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var $t = $('#historicoTable');
        var opts = {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            responsive: true,
            order: [[2, 'desc']]
        };
        function syncHistoricoDt() {
            if (window.matchMedia('(min-width: 992px)').matches) {
                if (!$.fn.DataTable.isDataTable($t)) {
                    $t.DataTable(opts);
                }
            } else if ($.fn.DataTable.isDataTable($t)) {
                $t.DataTable().destroy();
            }
        }
        syncHistoricoDt();
        var deb;
        $(window).on('resize', function () {
            clearTimeout(deb);
            deb = setTimeout(syncHistoricoDt, 200);
        });
    });
</script>
@endsection
