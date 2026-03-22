@extends('layouts.app')

@section('title', 'DEV — Sobre')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Sobre o aplicativo</h1>
    @include('dev._nav', ['devCrumb' => 'Sobre'])

    <p class="text-muted small">Saída de <code>php artisan about</code> no momento do carregamento desta página.</p>

    <div class="card">
        <div class="card-body">
            <pre class="mb-0 small" style="white-space: pre-wrap; max-height: 70vh; overflow: auto;">{{ $about }}</pre>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('dev.index') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>
@endsection
