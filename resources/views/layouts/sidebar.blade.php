<nav class="sidebar-nav">
    <ul>
        {{-- Dashboard: acessível para administrador e dono --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'dono')
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @endif
        
        {{-- Estoque: acessível para todos, mas com permissões diferenciadas --}}
        <li>
            <a href="{{ route('inventory.index') }}" class="{{ request()->routeIs('inventory.*') && !request()->routeIs('inventory.movimentacoes.*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i>
                <span>Estoque</span>
            </a>
        </li>
        <li>
            <a href="{{ route('categories.create') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
                <i class="fas fa-folder-plus"></i>
                <span>Nova categoria</span>
            </a>
        </li>
        
        {{-- Vendas: acessível para administrador, dono e vendedor --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'dono' || Auth::user()->nivel_acesso === 'vendedor')
        <li>
            <a href="{{ route('sales.index') }}" class="{{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <i class="fas fa-cash-register"></i>
                <span>Vendas</span>
            </a>
        </li>
        <li>
            <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Clientes</span>
            </a>
        </li>
        @endif
        
        {{-- Histórico: acessível para administrador, dono e vendedor --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'dono' || Auth::user()->nivel_acesso === 'vendedor')
        <li>
            <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Histórico</span>
            </a>
        </li>
        @endif
        
        {{-- Movimentações: acessível para administrador, dono e estoquista --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'dono' || Auth::user()->nivel_acesso === 'estoquista')
        <li>
            <a href="{{ route('inventory.movimentacoes.index') }}" class="{{ request()->routeIs('inventory.movimentacoes.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Movimentações</span>
            </a>
        </li>
        @endif
        
        <li class="sidebar-divider">
            <a href="{{ route('profile') }}">
                <i class="fas fa-user-cog"></i>
                <span>Meu Perfil</span>
            </a>
        </li>
        
        <li>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-btn-logout">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Sair</span>
                </button>
            </form>
        </li>
    </ul>
</nav>
