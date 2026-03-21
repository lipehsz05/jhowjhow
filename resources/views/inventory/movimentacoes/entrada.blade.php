@extends('layouts.app')

@section('title', 'Nova Entrada de Estoque')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Nova Entrada de Estoque</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventory.movimentacoes.index') }}">Movimentações</a></li>
        <li class="breadcrumb-item active">Nova Entrada</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-plus-circle me-1"></i>
            Registrar Entrada de Produto
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('inventory.movimentacoes.entrada.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="produto_id" class="form-label">Produto</label>
                            <select name="produto_id" id="produto_id" class="form-select" required>
                                <option value="">Selecione um produto</option>
                                @foreach($produtos as $produto)
                                    <option value="{{ $produto->id }}">{{ $produto->nome }} - Estoque atual: {{ $produto->quantidade }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="quantidade" class="form-label">Quantidade</label>
                            <input type="number" name="quantidade" id="quantidade" class="form-control" min="1" required>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="motivo" class="form-label">Motivo</label>
                            <select name="motivo" id="motivo" class="form-select" required>
                                <option value="compra">Compra de Fornecedor</option>
                                <option value="devolucao">Devolução</option>
                                <option value="ajuste">Ajuste de Inventário</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="data" class="form-label">Data</label>
                            <input type="datetime-local" name="data" id="data" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-4">
                    <label for="observacao" class="form-label">Observação</label>
                    <textarea name="observacao" id="observacao" rows="3" class="form-control"></textarea>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="{{ route('inventory.movimentacoes.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Registrar Entrada
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
