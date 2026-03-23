@foreach ($venda->itens as $index => $item)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">#{{ $index + 1 }} — {{ $item->produto->nome }}@if($item->produto->tamanho) <span class="text-muted fw-normal">(tam. {{ $item->produto->tamanho }})</span>@endif</h3>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Qtd</dt><dd>{{ $item->quantidade }}</dd></div>
            <div><dt>Preço unit.</dt><dd>R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</dd></div>
            <div class="mobile-data-card__meta-full"><dt>Subtotal</dt><dd>R$ {{ number_format($item->quantidade * $item->preco_unitario, 2, ',', '.') }}</dd></div>
        </dl>
    </article>
@endforeach
