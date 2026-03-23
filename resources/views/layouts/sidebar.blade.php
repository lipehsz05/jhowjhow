<nav class="sidebar-nav">
    <ul>
        {{-- Dashboard: acessível para administrador e dono --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->hasDonoLevelAccess())
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
            <a href="{{ url('/categories') }}" class="{{ request()->is('categories') || request()->is('categories/*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i>
                <span>Categorias</span>
            </a>
        </li>
        
        {{-- Vendas: acessível para administrador, dono e vendedor --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->hasDonoLevelAccess() || Auth::user()->nivel_acesso === 'vendedor')
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
        <li>
            <a href="{{ route('relatorio.index') }}" class="{{ request()->routeIs('relatorio.*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Relatório</span>
            </a>
        </li>
        @endif
        
        {{-- Histórico: acessível para administrador, dono e vendedor --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->hasDonoLevelAccess() || Auth::user()->nivel_acesso === 'vendedor')
        <li>
            <a href="{{ route('history.index') }}" class="{{ request()->routeIs('history.*') ? 'active' : '' }}">
                <i class="fas fa-history"></i>
                <span>Histórico</span>
            </a>
        </li>
        @endif
        
        {{-- Movimentações: acessível para administrador, dono e estoquista --}}
        @if (Auth::user()->nivel_acesso === 'administrador' || Auth::user()->hasDonoLevelAccess() || Auth::user()->nivel_acesso === 'estoquista')
        <li>
            <a href="{{ route('inventory.movimentacoes.index') }}" class="{{ request()->routeIs('inventory.movimentacoes.*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Movimentações</span>
            </a>
        </li>
        @endif

        @if(Auth::user()->isDev())
        <li class="sidebar-dev-wrap">
            <details class="sidebar-dev-details" @if(request()->routeIs('dev.*')) open @endif>
                <summary class="sidebar-dev-summary">
                    <i class="fas fa-code"></i>
                    <span>Configurações</span>
                </summary>
                <ul class="sidebar-dev-submenu">
                    <li>
                        <a href="{{ route('dev.index') }}" class="{{ request()->routeIs('dev.index') ? 'active' : '' }}">
                            <i class="fas fa-home"></i><span>Visão geral</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dev.general') }}" class="{{ request()->routeIs('dev.general') ? 'active' : '' }}">
                            <i class="fas fa-sliders-h"></i><span>Gerais</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dev.users') }}" class="{{ request()->routeIs('dev.users') ? 'active' : '' }}">
                            <i class="fas fa-users-cog"></i><span>Usuários e cargos</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dev.cache') }}" class="{{ request()->routeIs('dev.cache') ? 'active' : '' }}">
                            <i class="fas fa-bolt"></i><span>Cache e Artisan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dev.about') }}" class="{{ request()->routeIs('dev.about') ? 'active' : '' }}">
                            <i class="fas fa-info-circle"></i><span>Sobre o app</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('dev.migrations') }}" class="{{ request()->routeIs('dev.migrations') ? 'active' : '' }}">
                            <i class="fas fa-database"></i><span>Status das migrações</span>
                        </a>
                    </li>
                </ul>
            </details>
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
