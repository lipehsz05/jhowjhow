@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Clientes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Clientes</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                    <i class="fas fa-users me-1"></i>
                    Lista de clientes
                </div>
                <a href="{{ route('clients.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Novo cliente
                </a>
            </div>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('clients.index') }}" class="row g-2 mb-3">
                <div class="col-md-6 col-lg-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Nome, e-mail, telefone ou documento" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive table-list-desktop">
                <table class="table table-bordered table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>E-mail</th>
                            <th class="text-center">Compras</th>
                            <th class="text-end">Valor</th>
                            <th class="text-center" style="min-width: 11rem;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clientes as $c)
                            <tr>
                                <td>{{ $c->nome }}</td>
                                <td>{{ \App\Support\BrFormat::telefoneDisplay($c->telefone) ?: '—' }}</td>
                                <td>{{ $c->email ?: '—' }}</td>
                                <td class="text-center">{{ $c->vendas_count }}</td>
                                <td class="text-end text-nowrap">R$ {{ number_format((float) ($c->total_gasto_concluidas ?? 0), 2, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-flex flex-wrap justify-content-center gap-1">
                                        <a href="{{ route('clients.show', $c) }}" class="btn btn-info btn-sm" title="Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $c) }}" class="btn btn-primary btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($c->whatsappUrl())
                                            <a href="{{ $c->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-sm" title="WhatsApp">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </a>
                                        @else
                                            <span class="btn btn-secondary btn-sm disabled" title="Sem telefone cadastrado">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </span>
                                        @endif
                                        <button type="button" class="btn btn-danger btn-sm btn-delete-cliente" title="Excluir" data-id="{{ $c->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                        <form action="{{ route('clients.destroy', $c) }}" method="POST" id="form-delete-cliente-{{ $c->id }}" class="d-none">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">Nenhum cliente encontrado.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="table-list-mobile">
                @include('clients.partials.mobile-cards', ['clientes' => $clientes])
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $clientes->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.querySelectorAll('.btn-delete-cliente').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var id = this.getAttribute('data-id');
        Swal.fire({
            title: 'Excluir cliente?',
            html: '<p class="mb-2 text-start">O cadastro deste cliente será removido (nome, contato, endereço etc.).</p>' +
                '<p class="text-start small text-muted mb-0">As vendas e os valores (total gasto e lucro) <strong>não</strong> são apagados: permanecem no sistema, apenas sem vínculo com este cliente.</p>',
            icon: 'warning',
            showCancelButton: true,
            focusCancel: true,
            confirmButtonText: 'Sim, excluir',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            reverseButtons: true
        }).then(function (result) {
            if (result.isConfirmed) {
                document.getElementById('form-delete-cliente-' + id).submit();
            }
        });
    });
});
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Pronto',
    text: @json(session('success')),
    timer: 3200,
    showConfirmButton: false
});
@endif
</script>
@endsection
