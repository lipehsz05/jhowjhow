@extends('layouts.app')

@section('title', 'Editar cliente — '.$cliente->nome)

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clientes</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clients.show', $cliente) }}">{{ $cliente->nome }}</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit me-1"></i> Dados</span>
            <a href="{{ route('clients.show', $cliente) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Voltar</a>
        </div>
        <div class="card-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            <form action="{{ route('clients.update', $cliente) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="nome">Nome *</label>
                        <input type="text" class="form-control @error('nome') is-invalid @enderror" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required>
                        @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold" for="email">E-mail *</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $cliente->email) }}" required autocomplete="email" placeholder="nome@exemplo.com">
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="telefone">Telefone</label>
                        <input type="text" class="form-control @error('telefone') is-invalid @enderror" id="telefone" name="telefone" value="{{ old('telefone', \App\Support\BrFormat::telefoneDisplay($cliente->telefone)) }}" placeholder="(83) 99999-9999" maxlength="15" inputmode="numeric" autocomplete="tel">
                        @error('telefone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="cpf_cnpj">CPF / CNPJ</label>
                        <input type="text" class="form-control @error('cpf_cnpj') is-invalid @enderror" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', \App\Support\BrFormat::cpfCnpjDisplay($cliente->cpf_cnpj)) }}" placeholder="000.000.000-00" maxlength="18" inputmode="numeric">
                        @error('cpf_cnpj')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @php
                        $semEnderecoPadrao = ! $cliente->cep && ! $cliente->endereco && ! $cliente->cidade && ! $cliente->estado;
                        $semEnderecoMarcado = old('sem_endereco') !== null ? (old('sem_endereco') == '1') : $semEnderecoPadrao;
                    @endphp
                    <div class="col-12 mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="sem_endereco" value="1" id="sem_endereco" @checked($semEnderecoMarcado)>
                            <label class="form-check-label fw-semibold" for="sem_endereco">Cliente sem endereço</label>
                        </div>
                        <small class="text-muted">Marcado: CEP e endereço ficam em branco e não são obrigatórios.</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="cep">CEP</label>
                        <input type="text" class="form-control @error('cep') is-invalid @enderror" id="cep" name="cep" value="{{ old('cep', \App\Support\BrFormat::cepDisplay($cliente->cep)) }}" placeholder="00000-000" maxlength="9" inputmode="numeric" autocomplete="postal-code">
                        <small class="text-muted">Busca automática no ViaCEP ao completar 8 dígitos.</small>
                        @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold" for="endereco">Endereço</label>
                        <input type="text" class="form-control @error('endereco') is-invalid @enderror" id="endereco" name="endereco" value="{{ old('endereco', $cliente->endereco) }}">
                        @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold" for="cidade">Cidade</label>
                        <input type="text" class="form-control @error('cidade') is-invalid @enderror" id="cidade" name="cidade" value="{{ old('cidade', $cliente->cidade) }}">
                        @error('cidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold" for="estado">UF</label>
                        <input type="text" class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" value="{{ old('estado', $cliente->estado) }}" maxlength="2">
                        @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@include('clients.partials.br-masks')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.BrClienteMasks) {
        BrClienteMasks.bindClienteForm(document);
    }
});
</script>
@endsection
