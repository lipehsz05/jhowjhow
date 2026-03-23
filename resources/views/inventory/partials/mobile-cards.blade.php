@forelse($produtos ?? [] as $produto)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $produto->nome }}</h3>
            <div>
                @if($produto->quantidade_estoque > 10)
                    <span class="badge" style="background: var(--primary); color: white;">Em estoque</span>
                @elseif($produto->quantidade_estoque > 0)
                    <span class="badge" style="background: var(--warning); color: white;">Estoque baixo</span>
                @else
                    <span class="badge" style="background: var(--danger); color: white;">Sem estoque</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Código</dt><dd>{{ $produto->codigo }}</dd></div>
            <div><dt>Tamanho</dt><dd>{{ $produto->tamanho ?? '—' }}</dd></div>
            <div><dt>Categoria</dt><dd>{{ $produto->categoria->nome }}</dd></div>
            <div><dt>Preço</dt><dd>{{ 'R$ '.number_format($produto->preco_venda, 2, ',', '.') }}</dd></div>
            <div><dt>Qtd.</dt><dd>{{ $produto->quantidade_estoque }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            @if (!auth()->user()->isVendedor())
                <a href="{{ route('inventory.edit', $produto->id) }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-edit"></i> Editar
                </a>
                <form action="{{ route('inventory.destroy', $produto->id) }}" method="POST" id="form-delete-{{ $produto->id }}" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button type="button" class="btn btn-outline-danger btn-sm delete-btn" data-id="{{ $produto->id }}" data-name="{{ $produto->nome }}">
                    <i class="fas fa-trash-alt"></i> Excluir
                </button>
            @else
                <span class="text-muted small">Apenas visualização</span>
            @endif
        </div>
    </article>
@empty
    <p class="text-center text-muted py-4 mb-0">Nenhum produto encontrado</p>
@endforelse
