@extends('layouts.app')

@section('title', 'Categorias')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Categorias</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Categorias</li>
    </ol>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <i class="fas fa-tags me-1"></i>
                Lista de categorias
            </div>
            <a href="{{ url('/categories/create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Nova categoria
            </a>
        </div>
        <div class="card-body p-0">
            @if ($categorias->isEmpty())
                <p class="p-4 mb-0 text-muted">Nenhuma categoria cadastrada. Clique em <strong>Nova categoria</strong> para criar.</p>
            @else
                <div class="table-responsive table-list-desktop">
                    <table class="table table-striped table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Tamanho</th>
                                <th>Descrição</th>
                                <th class="text-center">Produtos</th>
                                <th class="text-center">Status</th>
                                <th class="text-end" style="width: 160px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categorias as $categoria)
                                <tr>
                                    <td class="fw-semibold">{{ $categoria->nome }}</td>
                                    <td class="small">{{ $categoria->tipo_tamanho_label }}</td>
                                    <td class="text-muted small">
                                        {{ $categoria->descricao ? \Illuminate\Support\Str::limit($categoria->descricao, 80) : '—' }}
                                    </td>
                                    <td class="text-center">{{ $categoria->produtos_count }}</td>
                                    <td class="text-center">
                                        @if ($categoria->ativa)
                                            <span class="badge bg-success">Ativa</span>
                                        @else
                                            <span class="badge bg-secondary">Inativa</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <a href="{{ url('/categories/' . $categoria->id . '/edit') }}" class="btn btn-outline-primary btn-sm" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ url('/categories/' . $categoria->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir esta categoria? Esta ação não pode ser desfeita.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-outline-danger btn-sm"
                                                    @if ($categoria->produtos_count > 0)
                                                        disabled
                                                        title="Existem produtos vinculados a esta categoria"
                                                    @else
                                                        title="Excluir"
                                                    @endif>
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="table-list-mobile px-3 pt-3">
                    @include('categories.partials.mobile-cards', ['categorias' => $categorias])
                </div>
                <div class="card-footer d-flex justify-content-center">
                    {{ $categorias->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
