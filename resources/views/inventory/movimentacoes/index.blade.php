@extends('layouts.app')

@section('title', 'Movimentações de Estoque')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Movimentações de Estoque</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Movimentações de Estoque</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-exchange-alt me-1"></i>
                    Histórico de Movimentações
                </div>
                <div>
                    @if(Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'estoquista' || Auth::user()->hasDonoLevelAccess())
                        <a href="{{ route('inventory.movimentacoes.entrada.create') }}" class="btn btn-success btn-sm me-2">
                            <i class="fas fa-plus"></i> Nova Entrada
                        </a>
                        <a href="{{ route('inventory.movimentacoes.saida.create') }}" class="btn btn-warning btn-sm text-dark">
                            <i class="fas fa-minus"></i> Nova Saída
                        </a>
                    @endif
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
                <table class="table table-bordered table-hover" id="movimentacoesTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Responsável</th>
                            <th>Observação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimentacoes ?? [] as $movimentacao)
                            <tr class="{{ $movimentacao->tipo == 'entrada' ? 'table-success' : 'table-warning' }}">
                                <td>{{ $movimentacao->id }}</td>
                                <td>{{ $movimentacao->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($movimentacao->tipo == 'entrada')
                                        <span class="badge bg-success">Entrada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Saída</span>
                                    @endif
                                </td>
                                <td>{{ $movimentacao->produto->nome ?? 'Produto não encontrado' }}</td>
                                <td>{{ $movimentacao->quantidade }}</td>
                                <td>{{ $movimentacao->user->name ?? 'Usuário não identificado' }}</td>
                                <td>{{ $movimentacao->observacao ?? 'Nenhuma observação' }}</td>
                                <td>
                                    <a href="{{ route('inventory.movimentacoes.show', $movimentacao->id) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Detalhes
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Nenhuma movimentação registrada</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-list-mobile">
                @include('inventory.movimentacoes.partials.mobile-cards', ['movimentacoes' => $movimentacoes ?? []])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var $t = $('#movimentacoesTable');
        var opts = {
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
            },
            responsive: true,
            order: [[1, 'desc']]
        };
        function syncMovDt() {
            if (window.matchMedia('(min-width: 992px)').matches) {
                if (!$.fn.DataTable.isDataTable($t)) {
                    $t.DataTable(opts);
                }
            } else if ($.fn.DataTable.isDataTable($t)) {
                $t.DataTable().destroy();
            }
        }
        syncMovDt();
        var deb;
        $(window).on('resize', function () {
            clearTimeout(deb);
            deb = setTimeout(syncMovDt, 200);
        });
    });
</script>
@endsection
