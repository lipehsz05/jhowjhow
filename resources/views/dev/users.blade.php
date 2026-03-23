@extends('layouts.app')

@section('title', 'DEV — Usuários')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Usuários e cargos</h1>
    @include('dev._nav', ['devCrumb' => 'Usuários'])

    <div class="alert alert-info">
        <strong>{{ $devCount }}</strong> usuário(s) com cargo DEV. É obrigatório manter pelo menos um DEV no sistema.
    </div>

    <div class="card mb-4">
        <div class="card-header"><i class="fas fa-users me-1"></i> Todos os usuários</div>
        <div class="card-body p-0">
            <div class="table-responsive table-list-desktop">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Usuário</th>
                            <th>Cargo atual</th>
                            <th style="min-width: 220px;">Novo cargo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr>
                            <td>{{ $u->name }}</td>
                            <td><code>{{ $u->username }}</code></td>
                            <td>
                                @if($u->nivel_acesso === 'dev')
                                    <span class="badge" style="background:#6f42c1;">DEV</span>
                                @elseif($u->nivel_acesso === 'dono')
                                    <span class="badge bg-danger">Dono</span>
                                @elseif($u->nivel_acesso === 'administrador')
                                    <span class="badge bg-primary">Administrador</span>
                                @elseif($u->nivel_acesso === 'vendedor')
                                    <span class="badge bg-success">Vendedor</span>
                                @elseif($u->nivel_acesso === 'estoquista')
                                    <span class="badge bg-info">Estoquista</span>
                                @else
                                    <span class="badge bg-secondary">{{ $u->nivel_acesso }}</span>
                                @endif
                            </td>
                            <td>
                                <form method="post" action="{{ route('dev.users.role', $u) }}" class="d-flex flex-wrap gap-2 align-items-center">
                                    @csrf
                                    <select name="nivel_acesso" class="form-select form-select-sm" style="max-width: 180px;">
                                        <option value="administrador" @selected($u->nivel_acesso === 'administrador')>Administrador</option>
                                        <option value="vendedor" @selected($u->nivel_acesso === 'vendedor')>Vendedor</option>
                                        <option value="estoquista" @selected($u->nivel_acesso === 'estoquista')>Estoquista</option>
                                        <option value="dono" @selected($u->nivel_acesso === 'dono')>Dono</option>
                                        <option value="dev" @selected($u->nivel_acesso === 'dev')>DEV</option>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-primary">Aplicar</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="table-list-mobile p-3">
                @include('dev.partials.users-mobile-cards', ['users' => $users])
            </div>
        </div>
    </div>

    <a href="{{ route('dev.index') }}" class="btn btn-outline-secondary">Voltar ao painel DEV</a>
</div>
@endsection
