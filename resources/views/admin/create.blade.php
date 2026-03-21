@extends('layouts.app')

@section('title', 'Adicionar Novo Usuário')

@section('content')
<div class="content-wrapper">
    <div class="container mx-auto px-4 py-6">
        <div class="admin-form-card">
            <h2 class="admin-form-title">Adicionar Novo Usuário</h2>
            
            <form action="{{ route('admin.store') }}" method="POST" id="adminForm">
                @csrf
                
                <div class="form-group">
                    <label for="name">Nome do Administrador</label>
                    <input type="text" id="name" name="name" class="form-input @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required autofocus>
                    <small class="form-text text-muted">O nome completo do administrador (pode conter letras maiúsculas e minúsculas).</small>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="username">Nome de Usuário</label>
                    <input type="text" id="username" name="username" class="form-input @error('username') is-invalid @enderror" 
                           value="{{ old('username') }}" required>
                    <small class="form-text text-muted">O nome de usuário será salvo em minúsculas.</small>
                    @error('username')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cargo">Cargo</label>
                    <select id="cargo" name="cargo" class="form-input @error('cargo') is-invalid @enderror" required>
                        <option value="" selected disabled>Selecione um cargo</option>
                        @foreach($cargos as $valor => $nome)
                            <option value="{{ $valor }}" {{ old('cargo') == $valor ? 'selected' : '' }}>{{ $nome }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">
                        <ul class="cargo-info">
                            <li><strong>Administrador:</strong> Acesso completo a todas as funcionalidades</li>
                            <li><strong>Vendedor:</strong> Acesso apenas às funcionalidades de vendas</li>
                            <li><strong>Estoquista:</strong> Acesso apenas ao gerenciamento de estoque</li>
                        </ul>
                    </small>
                    @error('cargo')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Senha</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-input @error('password') is-invalid @enderror" required>
                        <button type="button" id="togglePassword" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    
                    <!-- Requisitos de senha -->
                    <div class="password-requirements">
                        <p>A senha deve conter:</p>
                        <ul>
                            <li id="req-length"><i class="fas fa-times-circle text-danger"></i> Mínimo de 8 caracteres</li>
                            <li id="req-uppercase"><i class="fas fa-times-circle text-danger"></i> Pelo menos uma letra maiúscula</li>
                            <li id="req-number"><i class="fas fa-times-circle text-danger"></i> Pelo menos um número</li>
                            <li id="req-special"><i class="fas fa-times-circle text-danger"></i> Pelo menos um caractere especial</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Senha</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                        <button type="button" id="toggleConfirmation" class="password-toggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <span id="password-match-message"></span>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary" id="submitBtn" disabled>Criar Usuário</button>
                    <a href="{{ route('dashboard') }}" class="btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-form-card {
        background: white;
        border-radius: 12px;
        box-shadow: var(--card-shadow);
        padding: 30px;
        max-width: 600px;
        margin: 30px auto;
        transition: var(--transition);
    }

    .admin-form-title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 25px;
        color: var(--dark);
        text-align: center;
        border-bottom: 1px solid #e9ecef;
        padding-bottom: 15px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #495057;
    }
    
    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ced4da;
        border-radius: 6px;
        font-size: 16px;
        transition: border-color 0.15s ease-in-out;
    }
    
    .form-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.25);
    }
    
    .form-input.is-invalid {
        border-color: var(--danger);
    }
    
    .invalid-feedback {
        display: block;
        color: var(--danger);
        font-size: 14px;
        margin-top: 5px;
    }
    
    .form-text {
        display: block;
        margin-top: 5px;
        font-size: 14px;
        color: #6c757d;
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-top: 30px;
    }
    
    .btn-primary, .btn-secondary {
        flex: 1;
        text-align: center;
    }
    
    .password-requirements {
        margin-top: 15px;
        background: #f8f9fa;
        padding: 15px;
        border-radius: 6px;
        font-size: 14px;
    }
    
    .password-requirements p {
        margin-top: 0;
        margin-bottom: 8px;
        font-weight: 500;
    }
    
    .password-requirements ul {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }
    
    .password-requirements li {
        margin-bottom: 5px;
        transition: color 0.3s ease;
    }
    
    .text-danger {
        color: var(--danger);
    }
    
    .text-success {
        color: #28a745;
    }
    
    .password-input-wrapper {
        position: relative;
    }
    
    .password-toggle {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #6c757d;
    }
    
    .cargo-info {
        list-style: none;
        padding-left: 0;
        margin-top: 10px;
        background-color: #f8f9fa;
        padding: 12px;
        border-radius: 6px;
        border-left: 4px solid var(--primary);
    }
    
    .cargo-info li {
        margin-bottom: 8px;
        font-size: 13px;
        padding-left: 20px;
        position: relative;
    }
    
    .cargo-info li:last-child {
        margin-bottom: 0;
    }
    
    .cargo-info li strong {
        color: var(--primary);
        font-weight: 600;
    }
    
    .cargo-info li::before {
        content: '•';
        color: var(--primary);
        font-size: 18px;
        position: absolute;
        left: 0;
        top: -2px;
    }
    
    @media (max-width: 768px) {
        .admin-form-card {
            padding: 20px;
            margin: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
    }
</style>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const cargoSelect = document.getElementById('cargo');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmation = document.getElementById('toggleConfirmation');
    const submitBtn = document.getElementById('submitBtn');
    const reqLength = document.getElementById('req-length');
    const reqUppercase = document.getElementById('req-uppercase');
    const reqNumber = document.getElementById('req-number');
    const reqSpecial = document.getElementById('req-special');
    const passwordMatchMessage = document.getElementById('password-match-message');
    
    // Flags para verificar requisitos
    let meetsLengthReq = false;
    let meetsUppercaseReq = false;
    let meetsNumberReq = false;
    let meetsSpecialReq = false;
    let passwordsMatch = false;
    let cargoSelected = false;
    
    // Função para verificar todos os requisitos
    function checkAllRequirements() {
        submitBtn.disabled = !(meetsLengthReq && meetsUppercaseReq && meetsNumberReq && 
                             meetsSpecialReq && passwordsMatch && confirmInput.value && cargoSelected);
    }
    
    // Validar requisitos da senha em tempo real
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Verificar comprimento mínimo
        if (password.length >= 8) {
            reqLength.innerHTML = '<i class="fas fa-check-circle text-success"></i> Mínimo de 8 caracteres';
            meetsLengthReq = true;
        } else {
            reqLength.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Mínimo de 8 caracteres';
            meetsLengthReq = false;
        }
        
        // Verificar letra maiúscula
        if (/[A-Z]/.test(password)) {
            reqUppercase.innerHTML = '<i class="fas fa-check-circle text-success"></i> Pelo menos uma letra maiúscula';
            meetsUppercaseReq = true;
        } else {
            reqUppercase.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Pelo menos uma letra maiúscula';
            meetsUppercaseReq = false;
        }
        
        // Verificar número
        if (/[0-9]/.test(password)) {
            reqNumber.innerHTML = '<i class="fas fa-check-circle text-success"></i> Pelo menos um número';
            meetsNumberReq = true;
        } else {
            reqNumber.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Pelo menos um número';
            meetsNumberReq = false;
        }
        
        // Verificar caractere especial
        if (/[^A-Za-z0-9]/.test(password)) {
            reqSpecial.innerHTML = '<i class="fas fa-check-circle text-success"></i> Pelo menos um caractere especial';
            meetsSpecialReq = true;
        } else {
            reqSpecial.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Pelo menos um caractere especial';
            meetsSpecialReq = false;
        }
        
        // Verificar se senhas conferem
        if (confirmInput.value) {
            checkPasswordsMatch();
        }
        
        // Verificar todos os requisitos
        checkAllRequirements();
    });
    
    // Verificar se as senhas conferem
    function checkPasswordsMatch() {
        if (passwordInput.value === confirmInput.value && confirmInput.value !== '') {
            passwordMatchMessage.innerHTML = '<i class="fas fa-check-circle text-success"></i> Senhas conferem';
            passwordMatchMessage.className = 'text-success';
            passwordsMatch = true;
        } else {
            passwordMatchMessage.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Senhas não conferem';
            passwordMatchMessage.className = 'text-danger';
            passwordsMatch = false;
        }
        checkAllRequirements();
    }
    
    // Verificar senhas quando a confirmação for alterada
    confirmInput.addEventListener('input', checkPasswordsMatch);
    
    // Verificar quando um cargo é selecionado
    cargoSelect.addEventListener('change', function() {
        cargoSelected = cargoSelect.value !== "" && cargoSelect.value !== null;
        checkAllRequirements();
    });
    
    // Verificar se já existe um cargo selecionado no carregamento da página
    if (cargoSelect.value !== "" && cargoSelect.value !== null) {
        cargoSelected = true;
        checkAllRequirements();
    }
    
    // Toggle mostrar/ocultar senha
    togglePassword.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            passwordInput.type = 'password';
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
    
    // Toggle mostrar/ocultar confirmação de senha
    toggleConfirmation.addEventListener('click', function() {
        if (confirmInput.type === 'password') {
            confirmInput.type = 'text';
            toggleConfirmation.innerHTML = '<i class="fas fa-eye-slash"></i>';
        } else {
            confirmInput.type = 'password';
            toggleConfirmation.innerHTML = '<i class="fas fa-eye"></i>';
        }
    });
});
</script>
@endsection
