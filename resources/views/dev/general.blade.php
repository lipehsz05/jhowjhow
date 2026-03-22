@extends('layouts.app')

@section('title', 'DEV — Gerais')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Configurações gerais</h1>
    @include('dev._nav', ['devCrumb' => 'Gerais'])

    <div class="card mb-4" style="max-width: 640px;">
        <div class="card-header"><i class="fas fa-sliders-h me-1"></i> Título e tema</div>
        <div class="card-body">
            <form method="post" action="{{ route('dev.general.update') }}">
                @csrf
                <div class="mb-3">
                    <label for="site_title" class="form-label">Nome do site (aba do navegador)</label>
                    <input type="text" class="form-control" id="site_title" name="site_title" value="{{ old('site_title', $siteTitle) }}" required maxlength="120">
                </div>
                <div class="mb-3">
                    <label for="primary_color" class="form-label">Cor principal</label>
                    <input type="color" class="form-control form-control-color" id="primary_color" name="primary_color" value="{{ old('primary_color', $primaryColor) }}" title="Cor principal">
                    <div class="form-text">Sidebar, destaques e variável CSS <code>--primary</code>. Valor salvo: <code>{{ $primaryColor }}</code></div>
                </div>
                <div class="mb-3">
                    <label for="body_bg" class="form-label">Cor de fundo do painel</label>
                    <input type="color" class="form-control form-control-color" id="body_bg" name="body_bg" value="{{ old('body_bg', $bodyBg) }}">
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="{{ route('dev.index') }}" class="btn btn-outline-secondary">Voltar</a>
            </form>
        </div>
    </div>
</div>
@endsection
