@foreach($users as $u)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $u->name }}</h3>
            <div>
                @if($u->nivel_acesso === 'dev')
                    <span class="badge" style="background:#6f42c1;">DEV</span>
                @elseif($u->nivel_acesso === 'dono')
                    <span class="badge bg-danger">Dono</span>
                @elseif($u->nivel_acesso === 'administrador')
                    <span class="badge bg-primary">Administrador</span>
                @elseif($u->nivel_acesso === 'vendedor')
                    <span class="badge bg-success">Vendedor</span>
                @elseif($u->nivel_acesso === 'estoquista')
                    <span class="badge bg-info">Estoquista</span>
                @else
                    <span class="badge bg-secondary">{{ $u->nivel_acesso }}</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div class="mobile-data-card__meta-full"><dt>Usuário</dt><dd><code>{{ $u->username }}</code></dd></div>
        </dl>
        <div class="mobile-data-card__actions flex-column align-items-stretch">
            <form method="post" action="{{ route('dev.users.role', $u) }}" class="d-flex flex-wrap gap-2 align-items-center">
                @csrf
                <select name="nivel_acesso" class="form-select form-select-sm flex-grow-1" style="min-width: 0;">
                    <option value="administrador" @selected($u->nivel_acesso === 'administrador')>Administrador</option>
                    <option value="vendedor" @selected($u->nivel_acesso === 'vendedor')>Vendedor</option>
                    <option value="estoquista" @selected($u->nivel_acesso === 'estoquista')>Estoquista</option>
                    <option value="dono" @selected($u->nivel_acesso === 'dono')>Dono</option>
                    <option value="dev" @selected($u->nivel_acesso === 'dev')>DEV</option>
                </select>
                <button type="submit" class="btn btn-sm btn-primary">Aplicar</button>
            </form>
        </div>
    </article>
@endforeach
