@foreach ($vendasPorCategoria as $categoria)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $categoria->nome }}</h3>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Qtd. produtos</dt><dd>{{ $categoria->quantidade_vendida }}</dd></div>
            <div><dt>Total (R$)</dt><dd>R$ {{ number_format($categoria->total_vendido, 2, ',', '.') }}</dd></div>
        </dl>
    </article>
@endforeach
