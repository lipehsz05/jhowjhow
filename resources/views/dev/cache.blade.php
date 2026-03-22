@extends('layouts.app')

@section('title', 'DEV — Cache')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Cache e Artisan</h1>
    @include('dev._nav', ['devCrumb' => 'Cache'])

    <p class="text-muted">Comandos executados no servidor com o mesmo PHP do Laravel. Em ambiente local, <code>route:cache</code> pode falhar se existirem rotas baseadas em closure.</p>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header text-success"><i class="fas fa-layer-group me-1"></i> Gerar / otimizar cache</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach([
                            'optimize' => 'optimize — otimização geral (config, rotas, views, eventos quando aplicável)',
                            'config:cache' => 'config:cache',
                            'route:cache' => 'route:cache',
                            'view:cache' => 'view:cache',
                            'event:cache' => 'event:cache',
                        ] as $cmd => $label)
                        <li class="mb-2 d-flex justify-content-between align-items-start gap-2 flex-wrap">
                            <span class="small">{{ $label }}</span>
                            <form method="post" action="{{ route('dev.artisan') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="comando" value="{{ $cmd }}">
                                <button type="submit" class="btn btn-sm btn-success">Executar</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header text-warning"><i class="fas fa-eraser me-1"></i> Limpar cache</div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        @foreach([
                            'optimize:clear' => 'optimize:clear — limpa caches de otimização',
                            'cache:clear' => 'cache:clear — cache da aplicação',
                            'config:clear' => 'config:clear',
                            'route:clear' => 'route:clear',
                            'view:clear' => 'view:clear',
                            'event:clear' => 'event:clear',
                        ] as $cmd => $label)
                        <li class="mb-2 d-flex justify-content-between align-items-start gap-2 flex-wrap">
                            <span class="small">{{ $label }}</span>
                            <form method="post" action="{{ route('dev.artisan') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="comando" value="{{ $cmd }}">
                                <button type="submit" class="btn btn-sm btn-outline-warning">Executar</button>
                            </form>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header"><i class="fas fa-tasks me-1"></i> Filas (deploy)</div>
        <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span class="small text-muted mb-0">Sinaliza workers para encerrarem após o job atual (útil após deploy com código novo).</span>
            <form method="post" action="{{ route('dev.artisan') }}" class="d-inline">
                @csrf
                <input type="hidden" name="comando" value="queue:restart">
                <button type="submit" class="btn btn-sm btn-outline-primary">queue:restart</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('dev.index') }}" class="btn btn-outline-secondary">Voltar</a>
    </div>
</div>
@endsection
