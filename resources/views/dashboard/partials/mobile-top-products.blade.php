@foreach ($produtosMaisVendidos as $produto)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $produto->nome }}</h3>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Quantidade</dt><dd>{{ $produto->quantidade_vendida }}</dd></div>
            <div><dt>Total (R$)</dt><dd>R$ {{ number_format($produto->total_vendido, 2, ',', '.') }}</dd></div>
        </dl>
    </article>
@endforeach
