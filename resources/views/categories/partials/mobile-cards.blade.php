@foreach ($categorias as $categoria)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $categoria->nome }}</h3>
            <div>
                @if ($categoria->ativa)
                    <span class="badge bg-success">Ativa</span>
                @else
                    <span class="badge bg-secondary">Inativa</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Tamanho</dt><dd>{{ $categoria->tipo_tamanho_label }}</dd></div>
            <div><dt>Produtos</dt><dd>{{ $categoria->produtos_count }}</dd></div>
            <div class="mobile-data-card__meta-full"><dt>Descrição</dt><dd>{{ $categoria->descricao ? \Illuminate\Support\Str::limit($categoria->descricao, 120) : '—' }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            <a href="{{ url('/categories/' . $categoria->id . '/edit') }}" class="btn btn-outline-primary btn-sm" title="Editar">
                <i class="fas fa-edit"></i> Editar
            </a>
            <form action="{{ url('/categories/' . $categoria->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Excluir esta categoria? Esta ação não pode ser desfeita.');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="btn btn-outline-danger btn-sm w-100"
                        @if ($categoria->produtos_count > 0)
                            disabled
                            title="Existem produtos vinculados a esta categoria"
                        @else
                            title="Excluir"
                        @endif>
                    <i class="fas fa-trash-alt"></i> Excluir
                </button>
            </form>
        </div>
    </article>
@endforeach
