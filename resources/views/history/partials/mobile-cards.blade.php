@forelse($vendas ?? [] as $item)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $item->cliente->nome ?? 'N/A' }}</h3>
            <div>
                @if($item->status == 'concluida')
                    <span class="badge bg-success">Concluída</span>
                @elseif($item->status == 'provisoria')
                    <span class="badge bg-primary">Provisória</span>
                @elseif($item->status == 'cancelada')
                    <span class="badge bg-danger">Cancelada</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>ID</dt><dd>{{ $item->id }}</dd></div>
            <div><dt>Código</dt><dd>{{ $item->codigo ?? str_pad($item->id, 6, '0', STR_PAD_LEFT) }}</dd></div>
            <div><dt>Data</dt><dd>{{ \Carbon\Carbon::parse($item->data)->format('d/m/Y H:i') }}</dd></div>
            <div><dt>Vendedor</dt><dd>{{ $item->usuario->name ?? 'N/A' }}</dd></div>
            <div><dt>Valor</dt><dd>R$ {{ number_format($item->valor_total, 2, ',', '.') }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            <a href="{{ route('history.show', $item->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> Detalhes
            </a>
            @if($item->status == 'provisoria')
                <a href="{{ route('sales.show', $item->id) }}" class="btn btn-success btn-sm">
                    <i class="fas fa-check"></i> Pagar
                </a>
            @endif
        </div>
    </article>
@empty
    <p class="text-center text-muted py-4 mb-0">Nenhum registro encontrado no período selecionado</p>
@endforelse
