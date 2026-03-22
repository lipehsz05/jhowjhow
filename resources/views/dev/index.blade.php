@extends('layouts.app')

@section('title', 'Painel DEV')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4"><i class="fas fa-code text-secondary me-2"></i>Painel desenvolvedor</h1>
    @include('dev._nav', ['devCrumb' => 'Visão geral'])

    <p class="text-muted mb-4">Ferramentas exclusivas para contas com cargo <strong>DEV</strong>. Alterações aqui afetam todo o sistema.</p>

    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-sliders-h me-2 text-primary"></i>Configurações gerais</h5>
                    <p class="card-text text-muted small">Nome exibido no título, cor principal e fundo do painel.</p>
                    <a href="{{ route('dev.general') }}" class="btn btn-dark btn-sm">Abrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-users-cog me-2 text-primary"></i>Usuários e cargos</h5>
                    <p class="card-text text-muted small">Alterar qualquer cargo, inclusive promover ou remover DEV.</p>
                    <a href="{{ route('dev.users') }}" class="btn btn-dark btn-sm">Abrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-bolt me-2 text-warning"></i>Cache e Artisan</h5>
                    <p class="card-text text-muted small">Rodar <code>route:cache</code>, <code>config:cache</code>, <code>view:cache</code>, limpar caches e otimizar.</p>
                    <a href="{{ route('dev.cache') }}" class="btn btn-dark btn-sm">Abrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle me-2 text-info"></i>Sobre o app</h5>
                    <p class="card-text text-muted small">Saída do comando <code>php artisan about</code> (ambiente, PHP, drivers).</p>
                    <a href="{{ route('dev.about') }}" class="btn btn-outline-secondary btn-sm">Abrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-database me-2 text-secondary"></i>Migrações</h5>
                    <p class="card-text text-muted small">Lista <code>migrate:status</code> (somente leitura).</p>
                    <a href="{{ route('dev.migrations') }}" class="btn btn-outline-secondary btn-sm">Abrir</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
