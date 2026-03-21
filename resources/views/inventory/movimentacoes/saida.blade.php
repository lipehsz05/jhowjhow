@extends('layouts.app')

@section('title', 'Nova Saída de Estoque')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Nova Saída de Estoque</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventory.movimentacoes.index') }}">Movimentações</a></li>
        <li class="breadcrumb-item active">Nova Saída</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-minus-circle me-1"></i>
            Registrar Saída de Produto
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

            <form action="{{ route('inventory.movimentacoes.saida.store') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="produto_id" class="form-label">Produto</label>
                            <select name="produto_id" id="produto_id" class="form-select" required>
                                <option value="">Selecione um produto</option>
                                @foreach($produtos as $produto)
                                    @if($produto->quantidade > 0)
                                        <option value="{{ $produto->id }}">{{ $produto->nome }} - Estoque atual: {{ $produto->quantidade }}</option>
                                    @else
                                        <option value="{{ $produto->id }}" disabled>{{ $produto->nome }} - Sem estoque</option>
                                    @endif
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
                                <option value="venda">Venda</option>
                                <option value="devolucao">Devolução ao Fornecedor</option>
                                <option value="perda">Perda/Quebra</option>
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
                    <button type="submit" class="btn btn-warning text-dark">
                        <i class="fas fa-save me-1"></i> Registrar Saída
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Validação para não permitir quantidade maior que o estoque
    document.addEventListener('DOMContentLoaded', function() {
        const produtoSelect = document.getElementById('produto_id');
        const quantidadeInput = document.getElementById('quantidade');
        
        produtoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                const estoqueText = selectedOption.textContent;
                const match = estoqueText.match(/Estoque atual: (\d+)/);
                
                if (match && match[1]) {
                    const estoqueAtual = parseInt(match[1]);
                    quantidadeInput.max = estoqueAtual;
                    quantidadeInput.setAttribute('max', estoqueAtual);
                }
            }
        });
    });
</script>
@endsection
