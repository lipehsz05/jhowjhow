@foreach ($clientesMaisCompraram as $cli)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title"><span title="{{ $cli->nome }}">{{ \Illuminate\Support\Str::limit($cli->nome, 12, '...') }}</span></h3>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Compras</dt><dd>{{ $cli->compras }}</dd></div>
            <div><dt>Total (R$)</dt><dd>R$ {{ number_format((float) $cli->total, 2, ',', '.') }}</dd></div>
        </dl>
    </article>
@endforeach
