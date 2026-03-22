@extends('layouts.app')

@section('title', 'Gerenciar Usuários')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gerenciar Usuários</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Gerenciar Usuários</li>
    </ol>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-users me-1"></i>
            Lista de Usuários
        </div>
        <div class="card-body">
            <div class="mb-3">
                <a href="{{ route('admin.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Adicionar Usuário
                </a>
            </div>
            <div class="table-responsive">
                <table id="datatablesSimple" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Nome de Usuário</th>
                            <th>Cargo</th>
                            <th>Data de Criação</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>
                                    @if($user->nivel_acesso == 'dev')
                                        <span class="badge" style="background:#6f42c1;">DEV</span>
                                    @elseif($user->nivel_acesso == 'dono')
                                        <span class="badge bg-danger">Dono</span>
                                    @elseif($user->nivel_acesso == 'administrador')
                                        <span class="badge bg-primary">Administrador</span>
                                    @elseif($user->nivel_acesso == 'vendedor')
                                        <span class="badge bg-success">Vendedor</span>
                                    @elseif($user->nivel_acesso == 'estoquista')
                                        <span class="badge bg-info">Estoquista</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $user->nivel_acesso }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($currentUser->id == $user->id ||
                                            $currentUser->nivel_acesso == 'dev' ||
                                            ($currentUser->nivel_acesso == 'dono' && $user->nivel_acesso != 'dev') ||
                                            ($currentUser->nivel_acesso == 'administrador' && !in_array($user->nivel_acesso, ['administrador', 'dono', 'dev'])))
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning me-3">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                        @endif
                                        
                                        @if($currentUser->id != $user->id && (
                                            $currentUser->nivel_acesso == 'dev' ||
                                            ($currentUser->nivel_acesso == 'dono' && !in_array($user->nivel_acesso, ['dono', 'dev'])) ||
                                            ($currentUser->nivel_acesso == 'administrador' && !in_array($user->nivel_acesso, ['administrador', 'dono', 'dev']))
                                        ))
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')" class="ms-1">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i> Excluir
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Configuração da DataTable
    document.addEventListener('DOMContentLoaded', function () {
        const datatablesSimple = document.getElementById('datatablesSimple');
        if (datatablesSimple) {
            new simpleDatatables.DataTable(datatablesSimple, {
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Portuguese-Brasil.json'
                }
            });
        }
    });
</script>
@endsection
