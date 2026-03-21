@extends('layouts.app')

@section('title', 'Nova Venda')

@section('styles')
<style>
    /* Estilo para o campo de seleção de produtos com lupa */
    .produto-select-container {
        position: relative;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        transition: all 0.3s ease;
        z-index: 100;
    }
    
    .produto-select-container:focus-within {
        box-shadow: 0 4px 15px rgba(0, 123, 255, 0.25);
        transform: translateY(-1px);
    }
    
    .produto-select-container .search-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #adb5bd;
        font-size: 1.2rem;
        transition: all 0.3s ease;
        opacity: 0.7;
        cursor: pointer;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: transparent;
        z-index: 10;
    }
    
    .produto-select-container:focus-within .search-icon {
        color: #ffffff;
        opacity: 1;
        background-color: var(--primary);
        box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.15);
        animation: appear 0.3s ease-out;
    }
    
    .produto-select-container .search-icon.searching {
        color: #ffffff;
        background-color: var(--primary);
        box-shadow: 0 0 0 5px rgba(0, 123, 255, 0.15);
    }
    
    .produto-select-container .search-icon.selected {
        color: #ffffff;
        background-color: #28a745;
        box-shadow: 0 0 0 5px rgba(40, 167, 69, 0.15);
    }
    
    @keyframes appear {
        0% { opacity: 0.5; transform: translateY(-50%) scale(0.8); }
        100% { opacity: 1; transform: translateY(-50%) scale(1); }
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0.5); }
        70% { box-shadow: 0 0 0 10px rgba(0, 123, 255, 0); }
        100% { box-shadow: 0 0 0 0 rgba(0, 123, 255, 0); }
    }
    
    .produto-select-container select {
        padding: 12px 40px 12px 15px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        appearance: none;
        -webkit-appearance: none;
        background-image: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    
    .produto-select-container select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: none;
    }
    
    /* Estilização do placeholder */
    .produto-select-container select option:first-child {
        color: #6c757d;
        font-style: italic;
        font-weight: normal;
    }
    
    .produto-select-container #produto_search {
        padding: 12px 40px 12px 15px;
        border-radius: 8px;
        border: 1px solid #ced4da;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        color: #495057;
        font-weight: 500;
        width: 100%;
    }
    
    /* Efeito de destaque quando o campo de busca está ativo */
    .produto-select-container #produto_search:focus {
        border-color: var(--primary);
        box-shadow: none;
        outline: none;
        color: var(--primary);
    }
    
    /* Estilos para o dropdown de produtos */
    .produtos-dropdown {
        position: absolute;
        top: calc(100% + 5px);
        left: 0;
        right: 0;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        max-height: 0;
        overflow: hidden;
        opacity: 0;
        transition: all 0.3s ease;
        z-index: 1000;
    }
    
    .produtos-dropdown.show {
        max-height: 300px;
        opacity: 1;
        overflow-y: auto;
    }
    
    .dropdown-placeholder {
        padding: 15px;
        color: #6c757d;
        font-style: italic;
        text-align: center;
        display: none;
    }
    
    .dropdown-placeholder.show {
        display: block;
    }
    
    .produtos-lista {
        overflow-y: auto;
    }
    
    .produto-item {
        padding: 12px 15px;
        border-bottom: 1px solid #f1f1f1;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .produto-item:last-child {
        border-bottom: none;
    }
    
    .produto-item:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .produto-item.selected {
        background-color: rgba(0, 123, 255, 0.1);
    }
    
    .produto-nome {
        font-weight: 500;
        margin-bottom: 4px;
        color: #333;
    }
    
    .produto-item .produto-info {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        color: #6c757d;
    }
    
    .produto-preco {
        color: #28a745;
        font-weight: 500;
    }
    
    .produto-fornecedor {
        color: #6c757d;
        max-width: 60%;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    
    /* Estilização para o bloco de informações do produto na tabela */
    .produto-info {
        display: flex;
        flex-direction: column;
    }
    
    .produto-info .nome-produto {
        font-weight: 500;
        margin-bottom: 3px;
    }
    
    .produto-info .fornecedor-produto {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    /* Destacar linhas da tabela no hover */
    #produtos_tabela tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Registrar Nova Venda</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('sales.index') }}">Vendas</a></li>
        <li class="breadcrumb-item active">Nova Venda</li>
    </ol>
    
    <form id="vendaForm" action="{{ route('sales.store') }}" method="POST">
        @csrf
        <div class="row mb-5">
            <!-- Dados da Venda -->
            <div class="col-md-4 px-4">
                <div class="card p-4 h-100 shadow-sm border ms-3">
                    <h5 class="mb-4 border-bottom pb-3">Dados da Venda</h5>
                    
                    <div class="form-group mb-3">
                        <label for="cliente_id" class="form-label fw-bold">
                            <i class="fas fa-user me-1"></i> Cliente
                        </label>
                        <div class="input-group">
                            <select class="form-select @error('cliente_id') is-invalid @enderror" name="cliente_id" id="cliente_id">
                                <option value="">Cliente não registrado</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nome }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#novoClienteModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        @error('cliente_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="forma_pagamento" class="form-label fw-bold">
                            <i class="fas fa-credit-card me-1"></i> Forma de Pagamento
                        </label>
                        <select class="form-select @error('forma_pagamento') is-invalid @enderror" name="forma_pagamento" id="forma_pagamento" required>
                            <option value="">Selecione...</option>
                            <option value="dinheiro" {{ old('forma_pagamento') == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                            <option value="pix" {{ old('forma_pagamento') == 'pix' ? 'selected' : '' }}>PIX</option>
                            <option value="debito" {{ old('forma_pagamento') == 'debito' ? 'selected' : '' }}>Cartão de Débito</option>
                            <option value="credito" {{ old('forma_pagamento') == 'credito' ? 'selected' : '' }}>Cartão de Crédito</option>
                        </select>
                        @error('forma_pagamento')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="status" class="form-label fw-bold">
                            <i class="fas fa-tag me-1"></i> Status da Venda
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" id="status" required>
                            <option value="concluida" {{ old('status') == 'concluida' ? 'selected' : '' }}>Concluída</option>
                            <option value="provisoria" {{ old('status') == 'provisoria' ? 'selected' : '' }}>Provisória</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="desconto" class="form-label fw-bold">
                            <i class="fas fa-percent me-1"></i> Desconto (R$)
                        </label>
                        <input type="number" step="0.01" min="0" class="form-control @error('desconto') is-invalid @enderror" name="desconto" id="desconto" value="{{ old('desconto', '0.00') }}">
                        @error('desconto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="observacao" class="form-label fw-bold">
                            <i class="fas fa-sticky-note me-1"></i> Observações
                        </label>
                        <textarea class="form-control @error('observacao') is-invalid @enderror" name="observacao" id="observacao" rows="3">{{ old('observacao') }}</textarea>
                        @error('observacao')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Produtos da Venda -->
            <div class="col-md-8 px-4">
                <div class="card p-4 h-100 shadow-sm border ms-4">
                    <h5 class="mb-4 border-bottom pb-3">Produtos</h5>
                    
                    <div class="mb-4">
                        <div class="row align-items-end">
                            <div class="col-md-6 mb-3">
                                <label for="produto_select" class="form-label fw-bold">
                                    <i class="fas fa-barcode me-1"></i> Selecionar Produto
                                </label>
                                <div class="produto-select-container">
                                    <input type="text" class="form-control" id="produto_search" placeholder="Digite para buscar produtos..." autocomplete="off">
                                    <input type="hidden" id="produto_select" value="">
                                    <div class="search-icon">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <div class="produtos-dropdown" id="produtos-dropdown">
                                        <div class="dropdown-placeholder">Digite para buscar produtos...</div>
                                        <div class="produtos-lista">
                                            @foreach($produtos as $produto)
                                                <div class="produto-item" 
                                                    data-id="{{ $produto->id }}"
                                                    data-nome="{{ $produto->nome }}" 
                                                    data-preco="{{ $produto->preco_venda }}"
                                                    data-estoque="{{ $produto->quantidade_estoque }}"
                                                    data-fornecedor="{{ $produto->fornecedor ?? 'Não especificado' }}">
                                                    <div class="produto-nome">{{ $produto->nome }}</div>
                                                    <div class="produto-info">
                                                        <span class="produto-preco">R$ {{ number_format($produto->preco_venda, 2, ',', '.') }}</span> 
                                                        <span class="produto-fornecedor">{{ $produto->fornecedor ?? 'Não especificado' }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="produto_quantidade" class="form-label fw-bold">Quantidade</label>
                                <input type="number" min="1" value="1" class="form-control" id="produto_quantidade">
                            </div>
                            <div class="col-md-3 mb-3">
                                <button type="button" class="btn btn-success w-100" id="adicionar_produto">
                                    <i class="fas fa-plus me-1"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr class="table-secondary">
                                    <th>Produto</th>
                                    <th width="15%">Preço Unit.</th>
                                    <th width="10%">Qtde</th>
                                    <th width="15%">Subtotal</th>
                                    <th width="10%">Ações</th>
                                </tr>
                            </thead>
                            <tbody id="produtos_tabela">
                                <tr id="sem_produtos">
                                    <td colspan="5" class="text-center text-muted">Nenhum produto adicionado</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-end border-top pt-3">
                        <div class="text-end">
                            <div class="h5 mb-2">
                                Valor Total: R$ <span id="valor_total">0,00</span>
                            </div>
                            <div class="text-muted">
                                Desconto: R$ <span id="valor_desconto">0,00</span>
                            </div>
                            <div class="h4 text-success">
                                Valor Final: R$ <span id="valor_final">0,00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Produtos serão adicionados aqui dinamicamente -->
        <div id="produtos_input"></div>
        
        <div class="row">
            <div class="col-12 px-4">
                <div class="card p-4 shadow-sm border ms-3">
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('sales.index') }}" class="btn btn-lg btn-secondary">
                            <i class="fas fa-times me-2"></i> Cancelar
                        </a>
                        <button type="submit" class="btn btn-lg btn-success" id="btn_finalizar">
                            <i class="fas fa-check me-2"></i> Finalizar Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Modal para Novo Cliente -->
<div class="modal fade" id="novoClienteModal" tabindex="-1" aria-labelledby="novoClienteModalLabel" aria-modal="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="novoClienteModalLabel">Cadastrar Novo Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <form id="formNovoCliente">
                    <div class="mb-3">
                        <label for="nome_cliente" class="form-label">Nome do Cliente</label>
                        <input type="text" class="form-control" id="nome_cliente" required>
                        <div class="invalid-feedback" id="nome_cliente_error"></div>
                    </div>
                    <div class="mb-3">
                        <label for="email_cliente" class="form-label">Email*</label>
                        <input type="email" class="form-control" id="email_cliente" required>
                        <div class="invalid-feedback" id="email_cliente_error"></div>
                        <small class="form-text text-muted">* Campo obrigatório</small>
                    </div>
                    <div class="mb-3">
                        <label for="telefone_cliente" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone_cliente" placeholder="(83) 99999-9999">
                        <div class="invalid-feedback" id="telefone_cliente_error"></div>
                        <small class="form-text text-muted">Formato: (83) 99999-9999</small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelarNovoCliente" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="salvarNovoCliente">Salvar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Função para formatar telefone
    document.getElementById('telefone_cliente').addEventListener('input', function(e) {
        // Remove caracteres não numéricos
        let value = e.target.value.replace(/\D/g, '');
        
        // Limita a 11 dígitos
        if (value.length > 11) {
            value = value.substring(0, 11);
        }
        
        // Formata o telefone
        if (value.length > 0) {
            if (value.length > 2) {
                value = '(' + value.substring(0, 2) + ') ' + value.substring(2);
            }
            if (value.length > 7) {
                // Ajusta o índice para levar em conta os parênteses e espaço
                value = value.substring(0, 10) + '-' + value.substring(10);
            }
        }
        
        e.target.value = value;
    });
    
    // Define o atributo maxlength para limitar o tamanho total do campo
    document.getElementById('telefone_cliente').setAttribute('maxlength', '16'); // (99) 99999-9999
    
    // Função para validar email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }
    
    // Inicialização correta do modal
    let novoClienteModal = null;
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar o modal corretamente
        novoClienteModal = new bootstrap.Modal(document.getElementById('novoClienteModal'), {
            backdrop: 'static',  // Não fechar ao clicar fora
            keyboard: false      // Não fechar com ESC
        });
        
        // Garantir que o modal não mantém o foco em elementos
        document.getElementById('novoClienteModal').addEventListener('hidden.bs.modal', function () {
            // Redefinir o formulário quando o modal é fechado
            document.getElementById('formNovoCliente').reset();
            document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
            document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        });
        
        // Foco no primeiro campo quando o modal é aberto
        document.getElementById('novoClienteModal').addEventListener('shown.bs.modal', function () {
            document.getElementById('nome_cliente').focus();
        });
    });
    
    // Função para cadastrar novo cliente
    document.getElementById('salvarNovoCliente').addEventListener('click', function() {
        // Reset previous errors
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
        
        const nome = document.getElementById('nome_cliente');
        const email = document.getElementById('email_cliente');
        const telefone = document.getElementById('telefone_cliente');
        
        let isValid = true;
        
        // Validate name
        if (!nome.value.trim()) {
            document.getElementById('nome_cliente_error').textContent = 'O nome do cliente é obrigatório';
            nome.classList.add('is-invalid');
            isValid = false;
        }
        
        // Validate email
        if (!email.value.trim()) {
            document.getElementById('email_cliente_error').textContent = 'O email do cliente é obrigatório';
            email.classList.add('is-invalid');
            isValid = false;
        } else if (!validateEmail(email.value)) {
            document.getElementById('email_cliente_error').textContent = 'Por favor, informe um email válido';
            email.classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            // Focar no primeiro campo com erro
            document.querySelector('.is-invalid').focus();
            return;
        }
        
        // Desabilitar o botão de salvar para evitar cliques duplos
        const btnSalvar = document.getElementById('salvarNovoCliente');
        const btnTextoOriginal = btnSalvar.innerHTML;
        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
        
        // Enviar dados via fetch para cadastrar cliente
        // Usar a nova rota nomeada para evitar erros 404
        fetch('{{ route("clientes.api.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                nome: nome.value,
                email: email.value,
                telefone: telefone.value
            }),
            credentials: 'same-origin' // Importante para enviar cookies de sessão
        })
        .then(response => {
            // Restaurar botão
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = btnTextoOriginal;
            
            // Verificar se a resposta foi bem-sucedida
            if (!response.ok) {
                throw new Error('Resposta do servidor não foi ok: ' + response.status + ' ' + response.statusText);
            }
            
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Adicionar o novo cliente ao select
                const select = document.getElementById('cliente_id');
                const option = new Option(data.cliente.nome, data.cliente.id);
                select.add(option);
                select.value = data.cliente.id;
                
                // Fechar o modal usando o método adequado
                if (novoClienteModal) {
                    novoClienteModal.hide();
                    // Focar no campo seguinte após fechar o modal
                    setTimeout(() => {
                        document.getElementById('forma_pagamento').focus();
                    }, 100);
                }
                
                // Mensagem de sucesso com notificação moderna
                showNotification('Cliente cadastrado com sucesso!', 'success');
            } else {
                // Mostrar erros de validação se houver
                if (data.errors) {
                    Object.keys(data.errors).forEach(key => {
                        const field = document.getElementById(key + '_cliente');
                        const errorDiv = document.getElementById(key + '_cliente_error');
                        if (field && errorDiv) {
                            field.classList.add('is-invalid');
                            errorDiv.textContent = data.errors[key][0];
                        }
                    });
                    // Focar no primeiro campo com erro
                    document.querySelector('.is-invalid').focus();
                } else {
                    showNotification('Erro ao cadastrar cliente: ' + data.message, 'error');
                }
            }
        })
        .catch(error => {
            // Restaurar botão em caso de erro
            btnSalvar.disabled = false;
            btnSalvar.innerHTML = btnTextoOriginal;
            
            console.error('Erro completo:', error);
            
            // Mensagem de erro melhorada
            showNotification('Erro de conexão com o servidor. O cliente não foi cadastrado.', 'error');
            
            // Tenta novamente com uma abordagem alternativa (via FormData e XMLHttpRequest)
            const formData = new FormData();
            formData.append('nome', nome.value);
            formData.append('email', email.value);
            formData.append('telefone', telefone.value);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("clientes.api.store") }}', true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // Adicionar o novo cliente ao select
                            const select = document.getElementById('cliente_id');
                            const option = new Option(response.cliente.nome, response.cliente.id);
                            select.add(option);
                            select.value = response.cliente.id;
                            
                            // Fechar o modal
                            const clienteModal = bootstrap.Modal.getInstance(document.getElementById('clienteModal'));
                            clienteModal.hide();
                            
                            // Focar no próximo campo após fechar o modal
                            setTimeout(() => {
                                document.getElementById('produto').focus();
                            }, 100);
                            
                            // Mensagem de sucesso
                            showNotification('Cliente cadastrado com sucesso!', 'success');
                        } else {
                            showNotification('Erro: ' + (response.message || 'Falha no cadastro'), 'error');
                        }
                    } catch (e) {
                        showNotification('Erro ao processar resposta do servidor', 'error');
                    }
                } else {
                    showNotification('Erro no servidor: ' + xhr.status, 'error');
                }
            };
            
            xhr.onerror = function() {
                showNotification('Falha na conexão com o servidor', 'error');
            };
            
            xhr.send(formData);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const produtos = [];
        const produtoSelect = document.getElementById('produto_select'); // hidden input
        const produtoSearch = document.getElementById('produto_search'); // text input
        const produtosDropdown = document.getElementById('produtos-dropdown');
        const produtosLista = document.querySelector('.produtos-lista');
        const produtoItems = document.querySelectorAll('.produto-item');
        const dropdownPlaceholder = document.querySelector('.dropdown-placeholder');
        const produtoQuantidade = document.getElementById('produto_quantidade');
        const btnAdicionar = document.getElementById('adicionar_produto');
        const produtosTabela = document.getElementById('produtos_tabela');
        const produtosInput = document.getElementById('produtos_input');
        const semProdutos = document.getElementById('sem_produtos');
        const valorTotalSpan = document.getElementById('valor_total');
        const valorDescontoSpan = document.getElementById('valor_desconto');
        const valorFinalSpan = document.getElementById('valor_final');
        const inputDesconto = document.getElementById('desconto');
        const searchIcon = document.querySelector('.search-icon');
        
        // Variável para armazenar todos os produtos para busca rápida
        const todosProdutos = [];
        produtoItems.forEach(item => {
            todosProdutos.push({
                id: item.dataset.id,
                nome: item.dataset.nome.toLowerCase(),
                preco: parseFloat(item.dataset.preco),
                estoque: parseInt(item.dataset.estoque, 10),
                fornecedor: item.dataset.fornecedor,
                element: item
            });
        });
        
        // Função para ativar o modo de pesquisa
        function ativarLupa() {
            searchIcon.classList.add('searching');
            searchIcon.style.animation = 'pulse 1.5s infinite';
            produtoSearch.focus();
            mostrarDropdown();
        }
        
        // Efeitos visuais para quando selecionar um produto
        function atualizarEstadoLupa() {
            if (produtoSelect.value) {
                searchIcon.classList.remove('searching');
                searchIcon.classList.add('selected');
                searchIcon.style.animation = 'none';
            } else {
                searchIcon.classList.remove('selected');
                if (document.activeElement === produtoSearch) {
                    searchIcon.classList.add('searching');
                    searchIcon.style.animation = 'pulse 1.5s infinite';
                } else {
                    searchIcon.classList.remove('searching');
                    searchIcon.style.animation = 'none';
                }
            }
        }
        
        // Função para mostrar o dropdown com todos os produtos inicialmente
        function mostrarDropdown() {
            produtosDropdown.classList.add('show');
            filtrarProdutos(produtoSearch.value);
            
            // Se não houver texto digitado, exibir todos os produtos
            if (!produtoSearch.value.trim()) {
                todosProdutos.forEach(produto => {
                    produto.element.style.display = 'block';
                });
                dropdownPlaceholder.classList.remove('show');
            }
        }
        
        // Função para esconder o dropdown
        function esconderDropdown(immediate = false) {
            if (immediate) {
                produtosDropdown.classList.remove('show');
            } else {
                // Pequeno delay para permitir a seleção de um item
                setTimeout(() => {
                    if (!produtoSearch.matches(':focus')) {
                        produtosDropdown.classList.remove('show');
                    }
                }, 150);
            }
        }
        
        // Função para filtrar produtos com base no texto digitado
        function filtrarProdutos(texto) {
            const termoBusca = texto.toLowerCase().trim();
            let encontrados = 0;
            
            todosProdutos.forEach(produto => {
                if (termoBusca === '' || produto.nome.includes(termoBusca) || produto.fornecedor.toLowerCase().includes(termoBusca)) {
                    produto.element.style.display = 'block';
                    encontrados++;
                } else {
                    produto.element.style.display = 'none';
                }
            });
            
            // Mostrar placeholder se não houver resultados
            if (encontrados === 0) {
                dropdownPlaceholder.textContent = 'Nenhum produto encontrado';
                dropdownPlaceholder.classList.add('show');
            } else {
                dropdownPlaceholder.classList.remove('show');
            }
        }
        
        // Selecionar um produto do dropdown
        function selecionarProduto(produtoItem) {
            const id = produtoItem.dataset.id;
            const nome = produtoItem.dataset.nome;
            const fornecedor = produtoItem.dataset.fornecedor;
            
            // Atualizar campo de busca e hidden input
            produtoSearch.value = `${nome} - ${fornecedor}`;
            produtoSelect.value = id;
            
            // Destacar produto selecionado
            produtoItems.forEach(item => item.classList.remove('selected'));
            produtoItem.classList.add('selected');
            
            // Atualizar estado visual
            atualizarEstadoLupa();
            esconderDropdown(true);
            
            // Focar no campo de quantidade
            produtoQuantidade.focus();
        }
        
        // Configurar eventos para a lupa e o campo de pesquisa
        searchIcon.addEventListener('click', ativarLupa);
        produtoSearch.addEventListener('focus', ativarLupa);
        produtoSearch.addEventListener('blur', function() {
            esconderDropdown();
            if (!produtoSelect.value) {
                searchIcon.classList.remove('searching');
                searchIcon.style.animation = 'none';
            }
        });
        
        // Evento para busca em tempo real enquanto digita
        produtoSearch.addEventListener('input', function() {
            if (produtoSearch.value.trim() === '') {
                produtoSelect.value = '';
                atualizarEstadoLupa();
            }
            mostrarDropdown();
            filtrarProdutos(produtoSearch.value);
        });
        
        // Evento para clique nos itens do dropdown
        produtosLista.addEventListener('click', function(e) {
            const produtoItem = e.target.closest('.produto-item');
            if (produtoItem) {
                selecionarProduto(produtoItem);
            }
        });
        
        // Eventos de teclado para navegar no dropdown
        produtoSearch.addEventListener('keydown', function(e) {
            const produtosVisiveis = Array.from(produtoItems).filter(item => item.style.display !== 'none');
            const currentIndex = produtosVisiveis.findIndex(item => item.classList.contains('selected'));
            
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentIndex < produtosVisiveis.length - 1) {
                    const nextIndex = currentIndex + 1;
                    produtoItems.forEach(item => item.classList.remove('selected'));
                    produtosVisiveis[nextIndex].classList.add('selected');
                    produtosVisiveis[nextIndex].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentIndex > 0) {
                    const prevIndex = currentIndex - 1;
                    produtoItems.forEach(item => item.classList.remove('selected'));
                    produtosVisiveis[prevIndex].classList.add('selected');
                    produtosVisiveis[prevIndex].scrollIntoView({ block: 'nearest' });
                }
            } else if (e.key === 'Enter') {
                e.preventDefault();
                const selectedItem = document.querySelector('.produto-item.selected');
                if (selectedItem && selectedItem.style.display !== 'none') {
                    selecionarProduto(selectedItem);
                } else if (produtosVisiveis.length > 0) {
                    selecionarProduto(produtosVisiveis[0]);
                }
            } else if (e.key === 'Escape') {
                e.preventDefault();
                esconderDropdown(true);
                produtoSearch.blur();
            }
        });
        
        // Inicialização - esconder o dropdown
        esconderDropdown(true);
        
        // Evento para adicionar produto
        btnAdicionar.addEventListener('click', function() {
            const produtoId = produtoSelect.value;
            if (!produtoId) {
                alert('Selecione um produto.');
                return;
            }
            
            const quantidade = parseInt(produtoQuantidade.value, 10);
            if (quantidade < 1) {
                alert('A quantidade deve ser maior que zero.');
                return;
            }
            
            // Encontrar produto selecionado nos dados armazenados
            const produtoSelecionado = todosProdutos.find(p => p.id === produtoId);
            if (!produtoSelecionado) {
                alert('Produto não encontrado.');
                return;
            }
            
            const nome = produtoSelecionado.element.dataset.nome;
            const precoUnitario = parseFloat(produtoSelecionado.element.dataset.preco);
            const estoqueDisponivel = parseInt(produtoSelecionado.element.dataset.estoque, 10);
            const fornecedor = produtoSelecionado.element.dataset.fornecedor || 'Não especificado';
            
            // Verificar se já existe este produto na lista
            const existente = produtos.findIndex(p => p.id === produtoId);
            if (existente >= 0) {
                const novaQtd = produtos[existente].quantidade + quantidade;
                if (novaQtd > estoqueDisponivel) {
                    alert(`Quantidade insuficiente em estoque. Disponível: ${estoqueDisponivel}`);
                    return;
                }
                produtos[existente].quantidade = novaQtd;
                produtos[existente].subtotal = novaQtd * precoUnitario;
            } else {
                if (quantidade > estoqueDisponivel) {
                    alert(`Quantidade insuficiente em estoque. Disponível: ${estoqueDisponivel}`);
                    return;
                }
                produtos.push({
                    id: produtoId,
                    nome: nome,
                    fornecedor: fornecedor,
                    quantidade: quantidade,
                    preco_unitario: precoUnitario,
                    subtotal: quantidade * precoUnitario
                });
            }
            
            atualizarTabela();
            produtoSelect.value = '';
            produtoSearch.value = '';
            produtoQuantidade.value = 1;
            atualizarEstadoLupa();
        });
        
        // Evento para remover produto
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('btn-remover')) {
                const produtoId = e.target.dataset.id;
                const index = produtos.findIndex(p => p.id === produtoId);
                if (index !== -1) {
                    produtos.splice(index, 1);
                    atualizarTabela();
                }
            }
        });
        
        // Evento para atualizar o desconto
        inputDesconto.addEventListener('input', function() {
            atualizarTotais();
        });
        
        function atualizarTabela() {
            // Limpar campos de input dinâmicos
            produtosInput.innerHTML = '';
            
            if (produtos.length === 0) {
                produtosTabela.innerHTML = '<tr id="sem_produtos"><td colspan="5" class="text-center text-muted">Nenhum produto adicionado</td></tr>';
            } else {
                let html = '';
                produtos.forEach((produto, index) => {
                    html += `
                    <tr>
                        <td>
                            <div class="produto-info">
                                <span class="nome-produto">${produto.nome}</span>
                                <span class="fornecedor-produto"><i class="fas fa-industry"></i> ${produto.fornecedor}</span>
                            </div>
                        </td>
                        <td>R$ ${produto.preco_unitario.toFixed(2).replace('.', ',')}</td>
                        <td>${produto.quantidade}</td>
                        <td>R$ ${produto.subtotal.toFixed(2).replace('.', ',')}</td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-remover" data-id="${produto.id}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    `;
                    
                    // Adicionar campos ocultos para enviar ao backend
                    produtosInput.innerHTML += `
                    <input type="hidden" name="produtos[${index}][id]" value="${produto.id}">
                    <input type="hidden" name="produtos[${index}][quantidade]" value="${produto.quantidade}">
                    <input type="hidden" name="produtos[${index}][preco_unitario]" value="${produto.preco_unitario}">
                    `;
                });
                produtosTabela.innerHTML = html;
            }
            
            atualizarTotais();
        }
        
        function atualizarTotais() {
            const valorTotal = produtos.reduce((total, produto) => total + produto.subtotal, 0);
            const desconto = parseFloat(inputDesconto.value) || 0;
            const valorFinal = Math.max(valorTotal - desconto, 0);
            
            valorTotalSpan.textContent = valorTotal.toFixed(2).replace('.', ',');
            valorDescontoSpan.textContent = desconto.toFixed(2).replace('.', ',');
            valorFinalSpan.textContent = valorFinal.toFixed(2).replace('.', ',');
        }
        
        // Validação ao enviar o formulário
        document.getElementById('vendaForm').addEventListener('submit', function(e) {
            if (produtos.length === 0) {
                e.preventDefault();
                alert('Adicione pelo menos um produto à venda.');
                return false;
            }
        });
    });
</script>
@endsection
