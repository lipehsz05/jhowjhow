@extends('layouts.app')

@section('title', 'Adicionar Novo Produto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Adicionar Novo Produto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Estoque</a></li>
        <li class="breadcrumb-item active">Adicionar Produto</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-plus me-1"></i>
                    Novo Produto
                </div>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form action="{{ route('inventory.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nome" class="form-label fw-bold">
                                <i class="fas fa-box me-1"></i> Nome do Produto*
                            </label>
                            <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome') }}" required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="codigo" class="form-label fw-bold">
                                <i class="fas fa-barcode me-1"></i> Código*
                            </label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo') }}" required>
                                <button class="btn btn-outline-secondary" type="button" id="gerarCodigo">
                                    <i class="fas fa-random"></i> Gerar
                                </button>
                            </div>
                            @error('codigo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="categoria_id" class="form-label fw-bold">
                                <i class="fas fa-tags me-1"></i> Categoria*
                            </label>
                            <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                                <option value="">Selecione uma categoria</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('categoria_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-5 offset-md-1">
                        <div class="form-group mb-4"> <!-- Aumentado o margin-bottom -->
                            <label for="preco_compra" class="form-label fw-bold">
                                <i class="fas fa-tag me-1"></i> Preço de Compra*
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control @error('preco_compra') is-invalid @enderror" 
                                       id="preco_compra" name="preco_compra" 
                                       value="{{ old('preco_compra') }}" 
                                       placeholder="0,00" required>
                            </div>
                            @error('preco_compra')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4"> <!-- Aumentado o margin-bottom -->
                            <label for="preco_venda" class="form-label fw-bold">
                                <i class="fas fa-dollar-sign me-1"></i> Preço de Venda*
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control @error('preco_venda') is-invalid @enderror" 
                                       id="preco_venda" name="preco_venda" 
                                       value="{{ old('preco_venda') }}" 
                                       placeholder="0,00" required>
                            </div>
                            @error('preco_venda')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4"> <!-- Aumentado o margin-bottom -->
                            <label for="quantidade_estoque" class="form-label fw-bold">
                                <i class="fas fa-cubes me-1"></i> Quantidade em Estoque*
                            </label>
                            <input type="number" class="form-control @error('quantidade_estoque') is-invalid @enderror" 
                                   id="quantidade_estoque" name="quantidade_estoque" 
                                   value="{{ old('quantidade_estoque', 0) }}" 
                                   min="0" required>
                            @error('quantidade_estoque')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group mb-4"> <!-- Aumentado o margin-bottom -->
                            <label for="estoque_minimo" class="form-label fw-bold">
                                <i class="fas fa-exclamation-triangle me-1"></i> Estoque Mínimo
                            </label>
                            <input type="number" class="form-control @error('estoque_minimo') is-invalid @enderror" 
                                   id="estoque_minimo" name="estoque_minimo" 
                                   value="{{ old('estoque_minimo', 5) }}" 
                                   min="0">
                            @error('estoque_minimo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="descricao" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i> Descrição
                            </label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" 
                                      id="descricao" name="descricao" rows="3">{{ old('descricao') }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="fornecedor" class="form-label fw-bold">
                                <i class="fas fa-truck me-1"></i> Fornecedor
                            </label>
                            <input type="text" class="form-control @error('fornecedor') is-invalid @enderror" 
                                   id="fornecedor" name="fornecedor" 
                                   value="{{ old('fornecedor') }}">
                            @error('fornecedor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="ativo" class="form-label fw-bold">
                                <i class="fas fa-toggle-on me-1"></i> Status
                            </label>
                            <select class="form-select @error('ativo') is-invalid @enderror" id="ativo" name="ativo">
                                <option value="1" selected>Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                            @error('ativo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="imagem" class="form-label fw-bold">
                                <i class="fas fa-image me-1"></i> Imagem do Produto
                            </label>
                            <input type="file" class="form-control @error('imagem') is-invalid @enderror" id="imagem" name="imagem">
                            @error('imagem')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-success" id="btnSubmit">
                        <i class="fas fa-plus-circle me-1"></i> Adicionar Produto
                    </button>
                </div>
                
                <!-- Campos ocultos para valores formatados corretamente -->
                <input type="hidden" name="preco_compra_hidden" id="preco_compra_hidden">
                <input type="hidden" name="preco_venda_hidden" id="preco_venda_hidden">
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    // Esperar até que o jQuery esteja disponível
    $(document).ready(function() {
        console.log('Document ready!');
        
        // Máscaras para campos de preço
        if($.fn.mask) {
            $('#preco_compra, #preco_venda').mask('#.##0,00', {reverse: true});
        }
        
        // Gerador de código aleatório - versão simplificada
        $('#gerarCodigo').on('click', function(e) {
            e.preventDefault();
            
            // Gerar código aleatório de 10 caracteres alfanuméricos
            var caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            var codigo = '';
            for (var i = 0; i < 10; i++) {
                codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
            }
            
            // Definir o valor no campo
            $('#codigo').val(codigo);
            
            // Mostrar feedback visual
            $('#codigo').css('background-color', '#f8f9fa');
            setTimeout(function() {
                $('#codigo').css('background-color', '');
            }, 300);
        });
        
        // Preview da imagem
        $('#imagem').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if ($('.image-preview').length) {
                        $('.image-preview img').attr('src', e.target.result);
                    } else {
                        $('<div class="mt-2 image-preview"><p>Pré-visualização:</p><img src="' + e.target.result + '" class="img-thumbnail" style="max-height: 100px"></div>').insertAfter('#imagem');
                    }
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Cálculo automático do preço de venda baseado na margem
        $('#preco_compra').on('input', function() {
            const precoCompra = $(this).val().replace('.', '').replace(',', '.');
            if (precoCompra > 0) {
                // Sugestão de margem de 30%
                const precoVenda = (parseFloat(precoCompra) * 1.3).toFixed(2).toString().replace('.', ',');
                $('#preco_venda').val(precoVenda);
            }
        });
        
        // Função para converter valor do formato brasileiro para o formato internacional
        function converterParaFormatoInternacional(valor) {
            // Remove os pontos de milhar e substitui a vírgula por ponto
            return valor.replace(/\./g, '').replace(',', '.');
        }
        
        // Processar o formulário antes de enviar
        $('form').on('submit', function(e) {
            e.preventDefault();
            
            // Converter os valores de preço do formato brasileiro para o internacional
            var precoCompra = converterParaFormatoInternacional($('#preco_compra').val());
            var precoVenda = converterParaFormatoInternacional($('#preco_venda').val());
            
            // Atualizar os campos originais com os valores convertidos
            $('#preco_compra').val(precoCompra);
            $('#preco_venda').val(precoVenda);
            
            console.log('Valores convertidos:', precoCompra, precoVenda);
            
            // Enviar o formulário
            this.submit();
        });
    });
</script>
@endsection
