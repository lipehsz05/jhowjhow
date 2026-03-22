@extends('layouts.app')

@section('title', 'Editar Usuário')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar Usuário</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Gerenciar Usuários</a></li>
        <li class="breadcrumb-item active">Editar Usuário</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-user-edit me-1"></i>
            Editar Usuário: {{ $user->name }}
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('admin.users.update', $user) }}" method="POST" id="editUserForm">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" name="name" type="text" placeholder="Nome" value="{{ old('name', $user->name) }}" required />
                            <label for="name">Nome</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="username" name="username" type="text" value="{{ $user->username }}" disabled readonly />
                            <label for="username">Nome de Usuário</label>
                            <div class="form-text">O nome de usuário não pode ser alterado.</div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label mb-2"><i class="fas fa-user-tag me-1"></i> Cargo do Usuário</label>
                        @if($user->nivel_acesso === 'dev')
                            <div class="alert alert-secondary mb-0">
                                <strong>DEV</strong> — nível acima do dono, atribuído apenas pelo sistema (ex.: <code>php artisan user:promote-dev</code>). O cargo não pode ser alterado por esta tela.
                            </div>
                        @else
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-primary text-white"><i class="fas fa-briefcase"></i></span>
                                <select class="form-select custom-select" id="cargo" name="cargo" {{ Auth::id() == $user->id ? 'disabled' : 'required' }}>
                                    <option value="">Selecione um cargo</option>
                                    <option value="administrador" {{ old('cargo', $user->nivel_acesso) == 'administrador' ? 'selected' : '' }}>Administrador</option>
                                    <option value="vendedor" {{ old('cargo', $user->nivel_acesso) == 'vendedor' ? 'selected' : '' }}>Vendedor</option>
                                    <option value="estoquista" {{ old('cargo', $user->nivel_acesso) == 'estoquista' ? 'selected' : '' }}>Estoquista</option>
                                    @if(Auth::user()->hasDonoLevelAccess())
                                        <option value="dono" {{ old('cargo', $user->nivel_acesso) == 'dono' ? 'selected' : '' }}>Dono</option>
                                    @endif
                                </select>
                            </div>
                            @if(Auth::id() == $user->id)
                                <div class="form-text text-danger"><i class="fas fa-exclamation-triangle"></i> Você não pode alterar seu próprio cargo.</div>
                            @elseif($user->nivel_acesso == 'administrador' && !Auth::user()->hasDonoLevelAccess())
                                <div class="form-text text-danger"><i class="fas fa-exclamation-triangle"></i> Apenas dono ou desenvolvedor pode editar administradores.</div>
                            @endif
                        @endif
                    </div>
                </div>
                
                <style>
                    .custom-select {
                        border-top-right-radius: 0.25rem;
                        border-bottom-right-radius: 0.25rem;
                        font-weight: 500;
                    }
                    .custom-select:focus {
                        border-color: #86b7fe;
                        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
                    }
                </style>

                <div class="card mb-4">
                    <div class="card-header">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="alterarSenha">
                            <label class="form-check-label" for="alterarSenha">Alterar senha</label>
                        </div>
                    </div>
                    <div class="card-body senha-fields" style="display: none;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="password" name="password" type="password" placeholder="Senha" disabled />
                                    <label for="password">Nova Senha</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating mb-3">
                                    <input class="form-control" id="password_confirmation" name="password_confirmation" type="password" placeholder="Confirme a Senha" disabled />
                                    <label for="password_confirmation">Confirme a Nova Senha</label>
                                </div>
                            </div>
                        </div>
                        <div class="alert alert-info">
                            <h5>Requisitos de senha:</h5>
                            <ul class="mb-0">
                                <li>Pelo menos 8 caracteres</li>
                                <li>Pelo menos uma letra maiúscula</li>
                                <li>Pelo menos um número</li>
                                <li>Pelo menos um caractere especial (@, $, !, %, *, ?, &)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4 mb-0">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Alterações</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alterarSenhaCheckbox = document.getElementById('alterarSenha');
        const senhaFields = document.querySelector('.senha-fields');
        const passwordField = document.getElementById('password');
        const passwordConfirmationField = document.getElementById('password_confirmation');
        
        alterarSenhaCheckbox.addEventListener('change', function() {
            if (this.checked) {
                senhaFields.style.display = 'block';
                passwordField.disabled = false;
                passwordConfirmationField.disabled = false;
            } else {
                senhaFields.style.display = 'none';
                passwordField.disabled = true;
                passwordConfirmationField.disabled = true;
                passwordField.value = '';
                passwordConfirmationField.value = '';
            }
        });
        
        // Validação do formulário
        document.getElementById('editUserForm').addEventListener('submit', function(event) {
            if (alterarSenhaCheckbox.checked) {
                const password = passwordField.value;
                const passwordConfirmation = passwordConfirmationField.value;
                
                if (password !== passwordConfirmation) {
                    event.preventDefault();
                    alert('As senhas não conferem.');
                    return false;
                }
                
                // Validar requisitos da senha
                const hasUpperCase = /[A-Z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[@$!%*?&]/.test(password);
                const hasMinLength = password.length >= 8;
                
                if (!hasUpperCase || !hasNumber || !hasSpecial || !hasMinLength) {
                    event.preventDefault();
                    alert('A senha não atende aos requisitos de segurança.');
                    return false;
                }
            }
            
            return true;
        });
    });
</script>
@endsection
