@forelse($movimentacoes ?? [] as $movimentacao)
    <article class="mobile-data-card" style="{{ $movimentacao->tipo == 'entrada' ? 'border-left: 4px solid #198754' : 'border-left: 4px solid #ffc107' }}">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $movimentacao->produto->nome ?? 'Produto não encontrado' }}</h3>
            <div>
                @if($movimentacao->tipo == 'entrada')
                    <span class="badge bg-success">Entrada</span>
                @else
                    <span class="badge bg-warning text-dark">Saída</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>ID</dt><dd>{{ $movimentacao->id }}</dd></div>
            <div><dt>Data</dt><dd>{{ $movimentacao->created_at->format('d/m/Y H:i') }}</dd></div>
            <div><dt>Quantidade</dt><dd>{{ $movimentacao->quantidade }}</dd></div>
            <div><dt>Responsável</dt><dd>{{ $movimentacao->user->name ?? '—' }}</dd></div>
            <div class="mobile-data-card__meta-full"><dt>Observação</dt><dd>{{ $movimentacao->observacao ?? 'Nenhuma observação' }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            <a href="{{ route('inventory.movimentacoes.show', $movimentacao->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> Detalhes
            </a>
        </div>
    </article>
@empty
    <p class="text-center text-muted py-4 mb-0">Nenhuma movimentação registrada</p>
@endforelse
