@extends('layouts.app')

@section('title', 'DEV — Migrações')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Status das migrações</h1>
    @include('dev._nav', ['devCrumb' => 'Migrações'])

    <p class="text-muted small">Saída de <code>php artisan migrate:status</code>.</p>

    <div class="card">
        <div class="card-body">
            <pre class="mb-0 small" style="white-space: pre-wrap; max-height: 70vh; overflow: auto;">{{ $output }}</pre>
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('dev.index') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>
@endsection
