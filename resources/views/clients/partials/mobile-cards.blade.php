@forelse($clientes as $c)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $c->nome }}</h3>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Telefone</dt><dd>{{ \App\Support\BrFormat::telefoneDisplay($c->telefone) ?: '—' }}</dd></div>
            <div><dt>E-mail</dt><dd>{{ $c->email ?: '—' }}</dd></div>
            <div><dt>Compras</dt><dd>{{ $c->vendas_count }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            <a href="{{ route('clients.show', $c) }}" class="btn btn-info btn-sm" title="Detalhes">
                <i class="fas fa-eye"></i>
            </a>
            <a href="{{ route('clients.edit', $c) }}" class="btn btn-primary btn-sm" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
            @if($c->whatsappUrl())
                <a href="{{ $c->whatsappUrl() }}" target="_blank" rel="noopener noreferrer" class="btn btn-whatsapp btn-sm" title="WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
            @else
                <span class="btn btn-secondary btn-sm disabled" title="Sem telefone cadastrado">
                    <i class="fa-brands fa-whatsapp"></i>
                </span>
            @endif
            <button type="button" class="btn btn-danger btn-sm btn-delete-cliente" title="Excluir" data-id="{{ $c->id }}">
                <i class="fas fa-trash-alt"></i>
            </button>
            <form action="{{ route('clients.destroy', $c) }}" method="POST" id="form-delete-cliente-{{ $c->id }}" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </div>
    </article>
@empty
    <p class="text-center text-muted py-4 mb-0">Nenhum cliente encontrado.</p>
@endforelse
