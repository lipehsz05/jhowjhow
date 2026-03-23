@foreach($users as $user)
    <article class="mobile-data-card">
        <div class="mobile-data-card__top">
            <h3 class="mobile-data-card__title">{{ $user->name }}</h3>
            <div>
                @if($user->nivel_acesso == 'dev')
                    <span class="badge" style="background:#6f42c1;">DEV</span>
                @elseif($user->nivel_acesso == 'dono')
                    <span class="badge bg-danger">Dono</span>
                @elseif($user->nivel_acesso == 'administrador')
                    <span class="badge bg-primary">Administrador</span>
                @elseif($user->nivel_acesso == 'vendedor')
                    <span class="badge bg-success">Vendedor</span>
                @elseif($user->nivel_acesso == 'estoquista')
                    <span class="badge bg-info">Estoquista</span>
                @else
                    <span class="badge bg-secondary">{{ $user->nivel_acesso }}</span>
                @endif
            </div>
        </div>
        <dl class="mobile-data-card__meta">
            <div><dt>Usuário</dt><dd>{{ $user->username }}</dd></div>
            <div><dt>Criado em</dt><dd>{{ $user->created_at->format('d/m/Y H:i') }}</dd></div>
        </dl>
        <div class="mobile-data-card__actions">
            @if($currentUser->id == $user->id ||
                $currentUser->nivel_acesso == 'dev' ||
                ($currentUser->nivel_acesso == 'dono' && $user->nivel_acesso != 'dev') ||
                ($currentUser->nivel_acesso == 'administrador' && !in_array($user->nivel_acesso, ['administrador', 'dono', 'dev'])))
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                    <i class="fas fa-edit"></i> Editar
                </a>
            @endif
            @if($currentUser->id != $user->id && (
                $currentUser->nivel_acesso == 'dev' ||
                ($currentUser->nivel_acesso == 'dono' && !in_array($user->nivel_acesso, ['dono', 'dev'])) ||
                ($currentUser->nivel_acesso == 'administrador' && !in_array($user->nivel_acesso, ['administrador', 'dono', 'dev']))
            ))
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger w-100">
                        <i class="fas fa-trash"></i> Excluir
                    </button>
                </form>
            @endif
        </div>
    </article>
@endforeach
