@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('styles')
<style>
    .profile-page {
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 2rem;
    }
    .profile-page .page-heading {
        margin-bottom: 1.75rem;
    }
    .profile-page ol.breadcrumb {
        margin-bottom: 0;
    }
    .profile-page .page-heading h1 {
        margin: 0 0 0.5rem;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--dark);
    }
    .profile-cards-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.75rem;
        align-items: start;
    }
    @media (min-width: 992px) {
        .profile-cards-grid {
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
    }
    .profile-cards-grid .card {
        margin-bottom: 0;
    }
    .profile-cards-grid .card-body {
        padding-top: 1.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4 profile-page">
    <div class="page-heading">
        <h1>Meu Perfil</h1>
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Meu Perfil</li>
        </ol>
    </div>
    
    <div class="profile-cards-grid">
        <div>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user me-1"></i>
                    Informações Pessoais
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="username" class="form-label">Nome de Usuário</label>
                            <input type="text" class="form-control" id="username" value="{{ Auth::user()->username }}" disabled readonly>
                            <div class="form-text">O nome de usuário não pode ser alterado.</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="cargo" class="form-label">Cargo</label>
                            <input type="text" class="form-control" id="cargo" value="{{ ucfirst(Auth::user()->nivel_acesso) }}" disabled readonly>
                            <div class="form-text">Seu cargo atual no sistema.</div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div>
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-key me-1"></i>
                    Alterar Senha
                </div>
                <div class="card-body">
                    @if(session('password_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('password_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('password.update') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Senha Atual</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Nova Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Requisitos de senha -->
                        <div class="password-requirements mb-3">
                            <p class="mb-1">A nova senha deve conter:</p>
                            <ul class="ps-3">
                                <li id="req-length"><i class="fas fa-times-circle text-danger"></i> Mínimo de 8 caracteres</li>
                                <li id="req-uppercase"><i class="fas fa-times-circle text-danger"></i> Pelo menos uma letra maiúscula</li>
                                <li id="req-number"><i class="fas fa-times-circle text-danger"></i> Pelo menos um número</li>
                                <li id="req-special"><i class="fas fa-times-circle text-danger"></i> Pelo menos um caractere especial</li>
                            </ul>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" id="changePasswordBtn" disabled>Alterar Senha</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const input = this.previousElementSibling;
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
        
        // Password validation
        const passwordInput = document.getElementById('password');
        const confirmInput = document.getElementById('password_confirmation');
        const changePasswordBtn = document.getElementById('changePasswordBtn');
        
        const reqLength = document.getElementById('req-length');
        const reqUppercase = document.getElementById('req-uppercase');
        const reqNumber = document.getElementById('req-number');
        const reqSpecial = document.getElementById('req-special');
        
        function validatePassword() {
            const password = passwordInput.value;
            const confirmPassword = confirmInput.value;
            
            // Check requirements
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasNumber = /[0-9]/.test(password);
            const hasSpecial = /[^A-Za-z0-9]/.test(password);
            const passwordsMatch = password === confirmPassword && password.length > 0;
            
            // Update indicators
            reqLength.innerHTML = hasLength 
                ? '<i class="fas fa-check-circle text-success"></i> Mínimo de 8 caracteres' 
                : '<i class="fas fa-times-circle text-danger"></i> Mínimo de 8 caracteres';
                
            reqUppercase.innerHTML = hasUppercase 
                ? '<i class="fas fa-check-circle text-success"></i> Pelo menos uma letra maiúscula' 
                : '<i class="fas fa-times-circle text-danger"></i> Pelo menos uma letra maiúscula';
                
            reqNumber.innerHTML = hasNumber 
                ? '<i class="fas fa-check-circle text-success"></i> Pelo menos um número' 
                : '<i class="fas fa-times-circle text-danger"></i> Pelo menos um número';
                
            reqSpecial.innerHTML = hasSpecial 
                ? '<i class="fas fa-check-circle text-success"></i> Pelo menos um caractere especial' 
                : '<i class="fas fa-times-circle text-danger"></i> Pelo menos um caractere especial';
            
            // Enable/disable submit button
            changePasswordBtn.disabled = !(hasLength && hasUppercase && hasNumber && hasSpecial && passwordsMatch);
        }
        
        if (passwordInput && confirmInput) {
            passwordInput.addEventListener('input', validatePassword);
            confirmInput.addEventListener('input', validatePassword);
        }
    });
</script>
@endsection
