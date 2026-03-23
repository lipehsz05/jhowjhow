@extends('layouts.app')

@section('title', 'Editar Produto')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar Produto</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('inventory.index') }}">Estoque</a></li>
        <li class="breadcrumb-item active">Editar Produto</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-edit me-1"></i>
                    Editar Produto
                </div>
                <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
        <div class="card-body">
            {{-- As mensagens de sucesso agora serão exibidas como notificações do SweetAlert2 --}}

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
            
            <form action="{{ route('inventory.update', $produto->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row mb-5">
                    <!-- Coluna da esquerda --> 
                    <div class="col-md-6 px-4">
                        <div class="card p-4 h-100 shadow-sm border ms-3">
                            <h5 class="mb-4 border-bottom pb-3">Informações Básicas</h5>
                            
                            <div class="form-group mb-3">
                                <label for="nome" class="form-label fw-bold">
                                    <i class="fas fa-box me-1"></i> Nome do Produto*
                                </label>
                                <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $produto->nome) }}" required>
                                @error('nome')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="codigo" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1"></i> Código*
                                </label>
                                <input type="text" class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" value="{{ old('codigo', $produto->codigo) }}" required>
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
                                        <option value="{{ $categoria->id }}"
                                                data-tipo-tamanho="{{ $categoria->tipo_tamanho ?? \App\Support\TamanhosBrasil::TIPO_UNICO }}"
                                                {{ old('categoria_id', $produto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3" id="tamanho_wrapper" style="display: none;">
                                <label for="tamanho" class="form-label fw-bold">
                                    <i class="fas fa-ruler-vertical me-1"></i> Tamanho*
                                </label>
                                <select class="form-select @error('tamanho') is-invalid @enderror"
                                        id="tamanho"
                                        name="tamanho">
                                    <option value="">Selecione o tamanho</option>
                                </select>
                                @error('tamanho')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Coluna da direita -->
                    <div class="col-md-6 px-4">
                        <div class="card p-4 h-100 shadow-sm border ms-4">
                            <h5 class="mb-4 border-bottom pb-3">Valores e Estoque</h5>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preco_compra" class="form-label fw-bold">
                                            <i class="fas fa-tag me-1"></i> Preço de Compra*
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control @error('preco_compra') is-invalid @enderror" 
                                                   id="preco_compra" name="preco_compra" 
                                                   value="{{ old('preco_compra', number_format($produto->preco_compra, 2, ',', '.')) }}" 
                                                   required>
                                        </div>
                                        @error('preco_compra')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="preco_venda" class="form-label fw-bold">
                                            <i class="fas fa-dollar-sign me-1"></i> Preço de Venda*
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">R$</span>
                                            <input type="text" class="form-control @error('preco_venda') is-invalid @enderror" 
                                                   id="preco_venda" name="preco_venda" 
                                                   value="{{ old('preco_venda', number_format($produto->preco_venda, 2, ',', '.')) }}" 
                                                   required>
                                        </div>
                                        @error('preco_venda')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="quantidade_estoque" class="form-label fw-bold">
                                            <i class="fas fa-cubes me-1"></i> Estoque Atual*
                                        </label>
                                        <input type="number" class="form-control @error('quantidade_estoque') is-invalid @enderror" 
                                               id="quantidade_estoque" name="quantidade_estoque" 
                                               value="{{ old('quantidade_estoque', $produto->quantidade_estoque) }}" 
                                               min="0" required>
                                        @error('quantidade_estoque')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="estoque_minimo" class="form-label fw-bold">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Estoque Mínimo
                                        </label>
                                        <input type="number" class="form-control @error('estoque_minimo') is-invalid @enderror" 
                                               id="estoque_minimo" name="estoque_minimo" 
                                               value="{{ old('estoque_minimo', $produto->estoque_minimo) }}" 
                                               min="0" required>
                                        @error('estoque_minimo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-5">
                    <!-- Descrição do Produto -->
                    <div class="col-md-12 px-4">
                        <div class="card p-4 shadow-sm border ms-3">
                            <h5 class="mb-4 border-bottom pb-3">Descrição do Produto</h5>
                            
                            <div class="form-group">
                                <label for="descricao" class="form-label fw-bold">
                                    <i class="fas fa-align-left me-1"></i> Detalhes do Produto
                                </label>
                                <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" rows="3">{{ old('descricao', $produto->descricao) }}</textarea>
                                @error('descricao')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-5">
                    <div class="col-md-6 px-4">
                        <!-- Informações Adicionais -->
                        <div class="card p-4 h-100 shadow-sm border ms-3">
                            <h5 class="mb-4 border-bottom pb-3">Informações Adicionais</h5>
                            
                            <div class="form-group mb-3">
                                <label for="fornecedor" class="form-label fw-bold">
                                    <i class="fas fa-truck me-1"></i> Fornecedor
                                </label>
                                <input type="text" class="form-control @error('fornecedor') is-invalid @enderror" 
                                       id="fornecedor" name="fornecedor" 
                                       value="{{ old('fornecedor', $produto->fornecedor) }}">
                                @error('fornecedor')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="ativo" class="form-label fw-bold">
                                    <i class="fas fa-toggle-on me-1"></i> Status do Produto
                                </label>
                                <select class="form-select @error('ativo') is-invalid @enderror" id="ativo" name="ativo">
                                    <option value="1" {{ old('ativo', $produto->ativo) ? 'selected' : '' }}>Ativo</option>
                                    <option value="0" {{ old('ativo', $produto->ativo) ? '' : 'selected' }}>Inativo</option>
                                </select>
                                @error('ativo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 px-4">
                        <!-- Imagem do Produto -->
                        <div class="card p-4 h-100 shadow-sm border ms-4">
                            <h5 class="mb-4 border-bottom pb-3">Imagem do Produto</h5>
                            
                            <div class="form-group">
                                <label for="imagem" class="form-label fw-bold">
                                    <i class="fas fa-image me-1"></i> Selecione uma Imagem
                                </label>
                                <input type="file" class="form-control @error('imagem') is-invalid @enderror" id="imagem" name="imagem">
                                @error('imagem')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                
                                @if($produto->imagem)
                                    <div class="mt-3 text-center">
                                        <p class="fw-bold">Imagem atual:</p>
                                        <div class="image-preview-container border rounded p-2 d-inline-block">
                                            <img src="{{ asset('storage/produtos/' . $produto->imagem) }}" 
                                                 alt="{{ $produto->nome }}" 
                                                 class="img-thumbnail" 
                                                 style="max-height: 150px">
                                        </div>
                                        <div class="form-check mt-2 text-start">
                                            <input class="form-check-input" type="checkbox" id="remover_imagem" name="remover_imagem" value="1">
                                            <label class="form-check-label" for="remover_imagem">
                                                Remover imagem atual
                                            </label>
                                        </div>
                                    </div>
                                @else
                                    <div class="mt-3 text-center">
                                        <p class="text-muted"><em>Nenhuma imagem cadastrada</em></p>
                                        <div class="border rounded p-3 d-inline-block">
                                            <i class="fas fa-image fa-4x text-secondary"></i>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-12 px-4">
                        <div class="card p-4 shadow-sm border ms-3">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('inventory.index') }}" class="btn btn-lg btn-secondary">
                                    <i class="fas fa-times me-2"></i> Cancelar
                                </a>
                                <button type="submit" id="btnSubmit" class="btn btn-lg btn-primary">
                                    <i class="fas fa-save me-2"></i> Salvar Alterações
                                </button>
                                
                                <!-- Campos ocultos para valores formatados corretamente -->
                                <input type="hidden" name="preco_compra_hidden" id="preco_compra_hidden">
                                <input type="hidden" name="preco_venda_hidden" id="preco_venda_hidden">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Estilo para notificações toast coloridas */
    .colored-toast.swal2-icon-success {
        background-color: rgba(48, 200, 105, 0.9) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(48, 200, 105, 0.4);
        border-left: 4px solid #28a745;
    }
    
    .colored-toast.swal2-icon-error {
        background-color: rgba(225, 70, 70, 0.9) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(225, 70, 70, 0.4);
        border-left: 4px solid #dc3545;
    }
    
    .colored-toast .swal2-title,
    .colored-toast .swal2-html-container {
        color: white !important;
    }
    
    .colored-toast .swal2-timer-progress-bar {
        background: rgba(255, 255, 255, 0.5);
    }
</style>
@endsection

@section('scripts')
<script>
    window.TAMANHOS_POR_TIPO = @json([
        'roupa' => \App\Support\TamanhosBrasil::opcoesRoupa(),
        'calcado' => \App\Support\TamanhosBrasil::opcoesCalcado(),
    ]);
    window.OLD_TAMANHO_PRODUTO = @json(old('tamanho', $produto->tamanho));
</script>
<!-- Adicionar SweetAlert2 com animações melhoradas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Adicionar Animate.css para animações nas notificações -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script type="text/javascript">
// Dados das mensagens de sessão - não usar diretivas Blade dentro de blocos JavaScript
var sessionData = {
    success: "{{ session('success') }}",
    error: "{{ session('error') }}"
};

// Se as mensagens estiverem vazias, definir como null
if (sessionData.success === "") sessionData.success = null;
if (sessionData.error === "") sessionData.error = null;

$(document).ready(function() {
    function atualizarCampoTamanho() {
        var $cat = $('#categoria_id');
        var opt = $cat.find('option:selected');
        var tipo = opt.attr('data-tipo-tamanho') || 'unico';
        var $wrap = $('#tamanho_wrapper');
        var $tam = $('#tamanho');
        if (tipo === 'unico') {
            $wrap.hide();
            $tam.prop('required', false).val('');
            $tam.empty().append('<option value="">—</option>');
            return;
        }
        $wrap.show();
        $tam.prop('required', true);
        var lista = (window.TAMANHOS_POR_TIPO && window.TAMANHOS_POR_TIPO[tipo]) || [];
        $tam.empty().append('<option value="">Selecione o tamanho</option>');
        lista.forEach(function (t) {
            $tam.append($('<option></option>').attr('value', t).text(t));
        });
        var oldVal = window.OLD_TAMANHO_PRODUTO;
        if (oldVal && lista.indexOf(oldVal) !== -1) {
            $tam.val(oldVal);
        }
    }

    $('#categoria_id').on('change', atualizarCampoTamanho);
    atualizarCampoTamanho();

    // Verificar e mostrar mensagens de sessão
    if (sessionData.success) {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso!',
            text: sessionData.success,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end',
            toast: true,
            customClass: {
                popup: 'colored-toast'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInRight animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight animate__faster'
            }
        });
    }
    
    if (sessionData.error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro!',
            text: sessionData.error,
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            position: 'top-end',
            toast: true,
            customClass: {
                popup: 'colored-toast'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInRight animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutRight animate__faster'
            }
        });
    }
        
        // Máscaras para campos de preço
        $('#preco_compra, #preco_venda').mask('#.##0,00', {reverse: true});
        
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
        
        // Preview da imagem
        $('#imagem').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
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
    });
</script>
@endsection
