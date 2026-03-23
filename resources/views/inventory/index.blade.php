@extends('layouts.app')

@section('title', 'Estoque')

@section('styles')
<!-- Adicionar Animate.css para animações -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
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
    
    /* Estilos personalizados para o modal de confirmação */
    .swal2-popup {
        border-radius: 15px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
    }
    .swal2-title {
        font-size: 1.5rem !important;
        font-weight: 600 !important;
    }
    .swal2-html-container {
        font-size: 1.1rem !important;
        margin-top: 1.5rem !important;
        margin-bottom: 1.5rem !important;
    }
    .swal2-confirm, .swal2-cancel {
        border-radius: 8px !important;
        font-weight: 500 !important;
        padding: 0.65rem 2rem !important;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1) !important;
    }
    .swal2-icon {
        margin-top: 1.5rem !important;
        margin-bottom: 1rem !important;
    }
    
    /* Design ultra-moderno para caixinhas de paginação */
    .pagination .page-item .page-link {
        border: none !important;
        background: #f8f9fa !important;
        color: #495057 !important;
        border-radius: 8px !important;
        margin: 0 3px !important;
        padding: 0 !important;
        font-size: 0.9rem !important;
        font-weight: 600 !important;
        min-width: 36px !important;
        height: 36px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
        box-shadow: 0 1px 2px rgba(0,0,0,0.08) !important;
        position: relative !important;
    }
    
    /* Remover borda ao clicar */
    .pagination .page-item .page-link:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.25) !important;
    }
    
    /* Estilo ao passar o mouse */
    .pagination .page-item .page-link:hover {
        background: #e9ecef !important;
        color: var(--primary) !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.12) !important;
        transform: translateY(-2px) !important;
    }
    
    /* Estilo para botão ativo */
    .pagination .page-item.active .page-link {
        background: var(--primary) !important;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
        color: white !important;
        font-weight: 700 !important;
        border-color: transparent !important;
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.3) !important;
    }
    
    /* Estilo para botão ativo ao passar o mouse */
    .pagination .page-item.active .page-link:hover {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%) !important;
        transform: translateY(-2px) !important;
    }
    
    /* Espaçamento da área de paginação */
    .pagination {
        margin: 20px 0 10px 0 !important;
        padding: 15px 0 !important;
        display: flex !important;
        justify-content: center !important;
    }

    /* Estilizar informações de paginação */
    .pagination-container .small.text-muted {
        font-size: 0.8rem !important;
        color: #6c757d !important;
        text-align: center !important;
        margin-bottom: 10px !important;
        font-weight: 500 !important;
    }
    
    /* Modernizar as setas de navegação */
    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border-radius: 8px !important;
        font-size: 0.8rem !important;
        padding: 0 !important;
        background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%) !important;
        color: white !important;
        border: none !important;
        min-width: 30px !important;
        height: 30px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 3px 8px rgba(67, 97, 238, 0.25) !important;
        transition: all 0.3s ease !important;
        overflow: hidden !important; /* Ocultar textos que vazem */
    }
    
    /* Esconder os textos pagination.previous e pagination.next */
    .pagination .page-item:first-child .page-link span,
    .pagination .page-item:last-child .page-link span {
        display: none !important;
        visibility: hidden !important;
    }
    
    /* Ocultar completamente o texto 'Showing 1 to 15 of 50 results' */
    nav[aria-label="Pagination Navigation"] > div:first-child,
    .d-flex.justify-content-between.flex-fill.d-sm-none,
    .d-none.flex-sm-fill.d-sm-flex.align-items-sm-center > div:first-child,
    .small.text-muted,
    p.small.text-muted {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Garantir que todas as navegações fiquem em uma linha só */
    nav[aria-label="Pagination Navigation"],
    nav[aria-label="Pagination Navigation"] > div,
    .pagination {
        display: flex !important;
        flex-direction: row !important;
        justify-content: center !important;
        align-items: center !important;
        width: 100% !important;
    }
    
    /* Garantir que os itens da paginação fiquem na ordem correta */
    .pagination {
        display: flex !important;
        flex-direction: row !important;
        flex-wrap: nowrap !important;
    }
    
    /* Resetar o display da paginação para garantir controle total */
    ul.pagination {
        display: inline-flex !important;
        list-style: none !important;
        padding: 0 !important;
        margin: 0 !important;
        gap: 5px !important;
    }
    
    /* Garantir que as setas fiquem na ordem correta */
    .pagination li:first-child {
        order: 1 !important;
    }
    
    /* Números no meio */
    .pagination li:not(:first-child):not(:last-child) {
        order: 2 !important;
    }
    
    /* Última seta */
    .pagination li:last-child {
        order: 3 !important;
    }
    
    /* Remover margens desnecessárias que possam causar quebras */
    .pagination .page-item {
        margin-bottom: 0 !important;
    }
    
    .pagination .page-item:first-child .page-link:hover,
    .pagination .page-item:last-child .page-link:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 5px 12px rgba(67, 97, 238, 0.35) !important;
    }
    
    @media (max-width: 520px) {
        ul.pagination {
            flex-wrap: wrap !important;
            justify-content: center;
            row-gap: 8px;
            max-width: 100%;
        }
        .pagination .page-item .page-link {
            min-width: 32px !important;
            height: 32px !important;
            font-size: 0.8rem !important;
        }
    }

    /* Anel circular girando — carregamento da tabela de estoque (sem texto na tela) */
    .inventory-loader-ring {
        width: 2.75rem;
        height: 2.75rem;
        border-radius: 50%;
        border: 3px solid rgba(67, 97, 238, 0.2);
        border-top-color: var(--primary, #4361ee);
        animation: inventory-loader-spin 0.75s linear infinite;
        box-sizing: border-box;
    }
    @keyframes inventory-loader-spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gerenciamento de Estoque</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Estoque</li>
    </ol>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-box-open me-1"></i>
                    Lista de Produtos
                </div>
                @if (!auth()->user()->isVendedor())
                <a href="{{ route('inventory.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Adicionar Produto
                </a>
                @endif
            </div>
        </div>
        <div class="card-body">
            {{-- As mensagens de sucesso agora serão exibidas como notificações do SweetAlert2 --}}

            <div class="row g-3 align-items-end mb-3">
                <div class="col-md-6 col-lg-5">
                    <label for="inventory-search" class="form-label fw-bold mb-1">
                        <i class="fas fa-search me-1"></i> Pesquisar
                    </label>
                    <input type="search"
                           class="form-control"
                           id="inventory-search"
                           placeholder="Nome, código ou tamanho…"
                           autocomplete="off"
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-5 col-lg-4">
                    <label for="inventory-categoria" class="form-label fw-bold mb-1">
                        <i class="fas fa-tags me-1"></i> Categoria
                    </label>
                    <select class="form-select" id="inventory-categoria">
                        <option value="">Todas as categorias</option>
                        @foreach ($categorias as $cat)
                            <option value="{{ $cat->id }}" @selected((string) request('categoria') === (string) $cat->id)>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 col-lg-3">
                    <p class="text-muted small mb-0" id="inventory-total-hint"></p>
                </div>
            </div>

            <div class="position-relative" id="inventory-table-wrap">
                <div id="inventory-loading"
                     class="d-none position-absolute top-0 start-0 w-100 h-100 align-items-center justify-content-center"
                     style="z-index: 5; background: rgba(255,255,255,0.7); border-radius: 0.25rem;"
                     aria-busy="false">
                    <div class="inventory-loader-ring" role="status" aria-label="Carregando lista de produtos"></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="produtosTable">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nome</th>
                                <th>Tamanho</th>
                                <th>Categoria</th>
                                <th>Preço de Venda</th>
                                <th>Qtd. em Estoque</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody id="produtos-tbody">
                            @include('inventory.partials.table-rows', ['produtos' => $produtos])
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="inventory-pagination-wrap">
                @include('inventory.partials.pagination-inventory', ['produtos' => $produtos])
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    window.INVENTORY_TABLE_URL = @json(route('inventory.table-data'));
</script>
<!-- Adicionar SweetAlert2 com animações melhoradas -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Passar dados da sessão para JavaScript de forma segura -->
<script type="text/javascript">
// Dados das mensagens de sessão - não usar diretivas Blade dentro de blocos JavaScript
var sessionData = {
    success: "{{ session('success') }}",
    error: "{{ session('error') }}"
};

// Se as mensagens estiverem vazias, definir como null
if (sessionData.success === "") sessionData.success = null;
if (sessionData.error === "") sessionData.error = null;

// Código JavaScript puro sem misturar com Blade
document.addEventListener('DOMContentLoaded', function() {
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
});

// Pesquisa e filtro em tempo real (estoque)
$(document).ready(function() {
    var $tbody = $('#produtos-tbody');
    var $pagination = $('#inventory-pagination-wrap');
    var $loading = $('#inventory-loading');
    var $hint = $('#inventory-total-hint');
    var $search = $('#inventory-search');
    var $categoria = $('#inventory-categoria');
    var debounceTimer = null;

    function setTotalHint(total) {
        if (typeof total !== 'number') return;
        if (total === 0) {
            $hint.text('Nenhum resultado');
        } else if (total === 1) {
            $hint.text('1 produto encontrado');
        } else {
            $hint.text(total + ' produtos encontrados');
        }
    }

    function showLoading(show) {
        if (show) {
            $loading.removeClass('d-none').addClass('d-flex').attr('aria-busy', 'true');
        } else {
            $loading.removeClass('d-flex').addClass('d-none').attr('aria-busy', 'false');
        }
    }

    function loadInventoryTable(page) {
        page = page || 1;
        var params = new URLSearchParams();
        var q = ($search.val() || '').trim();
        var cat = $categoria.val() || '';
        if (q) params.set('search', q);
        if (cat) params.set('categoria', cat);
        params.set('page', page);

        showLoading(true);
        fetch(window.INVENTORY_TABLE_URL + '?' + params.toString(), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
            .then(function (r) {
                if (!r.ok) throw new Error('Erro ao carregar');
                return r.json();
            })
            .then(function (data) {
                $tbody.html(data.html);
                $pagination.html(data.pagination || '');
                if (typeof data.total === 'number') {
                    setTotalHint(data.total);
                }
            })
            .catch(function () {
                $hint.text('Não foi possível atualizar a lista.');
            })
            .finally(function () {
                showLoading(false);
            });
    }

    setTotalHint({{ (int) $produtos->total() }});

    $search.on('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            loadInventoryTable(1);
        }, 300);
    });

    $categoria.on('change', function () {
        loadInventoryTable(1);
    });

    $(document).on('click', '.js-inventory-page', function (e) {
        e.preventDefault();
        var $li = $(this).closest('.page-item');
        if ($li.hasClass('disabled')) return;
        var p = parseInt($(this).data('page'), 10);
        if (!isNaN(p) && p >= 1) {
            loadInventoryTable(p);
        }
    });
});

// Configurar botões de exclusão (delegado — funciona após atualizar a tabela via AJAX)
$(document).ready(function() {
    $(document).on('click', '.delete-btn', function() {
        var productId = $(this).data('id');
        var productName = $(this).data('name');
        
        Swal.fire({
            title: 'Confirmar exclusão?',
            html: "Você está prestes a excluir o produto <strong>" + productName + "</strong>.<br>Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Sim, excluir!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                // Se confirmado, enviar o formulário de exclusão
                document.getElementById('form-delete-' + productId).submit();
                
                // Mostrar mensagem de carregamento enquanto processa
                Swal.fire({
                    title: 'Excluindo...',
                    text: 'Processando sua solicitação',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    });
});
</script>
@endsection