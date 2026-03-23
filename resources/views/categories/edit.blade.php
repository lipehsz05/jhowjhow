@extends('layouts.app')

@section('title', 'Editar Categoria')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Editar categoria</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/categories') }}">Categorias</a></li>
        <li class="breadcrumb-item active">{{ $categoria->nome }}</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-tags me-1"></i>
                    Editar: {{ $categoria->nome }}
                </div>
                <a href="{{ url('/categories') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Voltar à lista
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            @endif

            <form action="{{ url('/categories/' . $categoria->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8 col-lg-6">
                        <div class="mb-3">
                            <label for="nome" class="form-label fw-bold">
                                <i class="fas fa-font me-1"></i> Nome da categoria*
                            </label>
                            <input type="text"
                                   class="form-control @error('nome') is-invalid @enderror"
                                   id="nome"
                                   name="nome"
                                   value="{{ old('nome', $categoria->nome) }}"
                                   required
                                   maxlength="255"
                                   placeholder="Ex.: Bebidas, Limpeza…">
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label fw-bold">
                                <i class="fas fa-align-left me-1"></i> Descrição
                            </label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror"
                                      id="descricao"
                                      name="descricao"
                                      rows="4"
                                      maxlength="5000"
                                      placeholder="Opcional">{{ old('descricao', $categoria->descricao) }}</textarea>
                            @error('descricao')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tipo_tamanho" class="form-label fw-bold">
                                <i class="fas fa-ruler-combined me-1"></i> Tipo de grade de tamanho*
                            </label>
                            <select class="form-select @error('tipo_tamanho') is-invalid @enderror"
                                    id="tipo_tamanho"
                                    name="tipo_tamanho"
                                    required>
                                @foreach(\App\Support\TamanhosBrasil::labelsTipo() as $valor => $rotulo)
                                    <option value="{{ $valor }}" @selected(old('tipo_tamanho', $categoria->tipo_tamanho ?? \App\Support\TamanhosBrasil::TIPO_UNICO) === $valor)>
                                        {{ $rotulo }}
                                    </option>
                                @endforeach
                            </select>
                            @error('tipo_tamanho')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Alterar o tipo afeta apenas novos cadastros; use também volumes em ml/litros para categorias como perfumes.</div>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox"
                                   class="form-check-input @error('ativa') is-invalid @enderror"
                                   id="ativa"
                                   name="ativa"
                                   value="1"
                                   @checked(old('ativa', $categoria->ativa))>
                            <label class="form-check-label" for="ativa">Categoria ativa</label>
                            @error('ativa')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Categorias inativas não aparecem ao cadastrar produtos.</div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Salvar alterações
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
