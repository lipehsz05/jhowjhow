@forelse($vendas ?? [] as $venda)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $venda->cliente->nome ?? 'Cliente não registrado' }}</h3>
            <div>
                @if($venda->status == 'concluida')
                    <span class="badge bg-success">Concluída</span>
                @elseif($venda->status == 'pendente')
                    <span class="badge bg-warning text-dark">Pendente</span>
                @elseif($venda->status == 'provisoria')
                    <span class="badge bg-info">Provisória</span>
                @elseif($venda->status == 'cancelada')
                    <span class="badge bg-danger">Cancelada</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Código</dt><dd>{{ $venda->codigo }}</dd></div>
            <div><dt>Data</dt><dd>{{ $venda->data->format('d/m/Y H:i') }}</dd></div>
            <div><dt>Vendedor</dt><dd>{{ $venda->usuario->name ?? '—' }}</dd></div>
            <div><dt>Valor</dt><dd>R$ {{ number_format($venda->valor_total, 2, ',', '.') }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            <a href="{{ route('sales.show', $venda->id) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye"></i> Detalhes
            </a>
            @if($venda->status != 'cancelada')
                <form action="{{ route('sales.destroy', $venda->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Tem certeza que deseja cancelar esta venda?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm w-100">
                        <i class="fas fa-ban"></i> Cancelar
                    </button>
                </form>
            @endif
        </div>
    </article>
@empty
    <p class="text-center text-muted py-4 mb-0">Nenhuma venda registrada</p>
@endforelse
