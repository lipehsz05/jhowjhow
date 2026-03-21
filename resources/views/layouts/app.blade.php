<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Sistema JhowJhow') }}</title>
    
    <!-- CSS Interno (Solução emergencial) - Design Moderno -->
    <style>
        /* Variáveis CSS para tema */
        :root {
            --primary: #4361ee;
            --primary-dark: #3a56d4;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --danger: #f72585;
            --warning: #fca311;
            --info: #4895ef;
            --dark: #242038;
            --light: #f8f9fa;
            --body-bg: #f5f8fb;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1), 0 1px 3px rgba(0, 0, 0, 0.08);
            --transition: all 0.3s ease;
        }
        
        /* Estilos básicos */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
            background-color: var(--body-bg);
            color: #333;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Sidebar estilizada */
        .sidebar {
            background: linear-gradient(145deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            width: 250px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            transition: var(--transition);
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-nav {
            padding: 20px 0;
            margin-top: 0;
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-nav li {
            margin-bottom: 2px;
        }
        
        .sidebar-nav a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            transition: var(--transition);
            border-left: 3px solid transparent;
        }
        
        .sidebar-nav a i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-nav a:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border-left: 3px solid var(--warning);
        }
        
        .sidebar-nav a.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            border-left: 3px solid var(--warning);
            font-weight: 600;
        }
        
        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.1);
            margin-top: 20px;
            padding-top: 20px;
        }
        
        .sidebar-btn-logout {
            width: 100%;
            background: none;
            border: none;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: var(--transition);
            border-left: 3px solid transparent;
            text-align: left;
            cursor: pointer;
            font-family: inherit;
            font-size: inherit;
        }
        
        .sidebar-btn-logout i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        
        .sidebar-btn-logout:hover {
            background-color: rgba(255,255,255,0.15);
            color: white;
            border-left: 3px solid var(--danger);
        }

        /* Layout principal */
        .app-container {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 80px 30px 30px;
            min-height: 100vh;
            transition: var(--transition);
            flex: 1;
            position: relative;
        }
        
        /* Sidebar brand/logo */
        .sidebar-brand {
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(0, 0, 0, 0.1);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand span {
            color: white;
            font-weight: 700;
            font-size: 18px;
        }

        /* Barra de navegação */
        .navbar {
            background-color: white;
            height: 60px;
            position: fixed;
            top: 0;
            left: 250px;
            right: 0;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            z-index: 100;
            transition: var(--transition);
        }
        
        .navbar-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }
        
        .navbar-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            margin: 0;
        }
        
        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--dark);
            cursor: pointer;
        }
        
        .navbar-right {
            display: flex;
            align-items: center;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .dropdown-toggle {
            background: none;
            border: none;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 4px;
            transition: var(--transition);
        }
        
        .dropdown-toggle:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .user-name {
            font-weight: 500;
            color: var(--dark);
            font-size: 14px;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 200px;
            background: white;
            border-radius: 4px;
            box-shadow: var(--card-shadow);
            margin-top: 5px;
            z-index: 1000;
            overflow: hidden;
            display: none;
        }
        
        .dropdown-menu.show {
            display: block;
            animation: fadeIn 0.2s ease;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 15px;
            color: var(--dark);
            text-decoration: none;
            transition: var(--transition);
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            font-family: inherit;
            font-size: 14px;
        }
        
        .dropdown-item:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }
        
        .dropdown-divider {
            height: 1px;
            background-color: #eaeaea;
            margin: 5px 0;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .dropdown {
            position: relative;
            margin-left: 15px;
        }

        /* Cards modernos */
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 25px;
            margin-bottom: 25px;
            transition: var(--transition);
            border: none;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-weight: 700;
            color: var(--dark);
            font-size: 1.2rem;
        }

        /* Botões estilizados */
        .btn {
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
            gap: 8px;
        }
        
        .btn i {
            font-size: 16px;
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.3);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #342fb4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(63, 55, 201, 0.3);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #e01b74;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(247, 37, 133, 0.3);
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #30b9e4;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(76, 201, 240, 0.3);
        }
        
        .btn-warning {
            background-color: var(--warning);
            color: white;
        }
        
        .btn-warning:hover {
            background-color: #e5940f;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(252, 163, 17, 0.3);
        }

        /* Tabelas elegantes */
        .table-container {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 0 1px #edf2f7;
            margin-bottom: 20px;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            font-size: 0.95rem;
        }
        
        .table th {
            background-color: #f8fafc;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            color: #64748b;
        }
        
        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #edf2f7;
        }
        
        .table tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .table tbody tr:last-child td {
            border-bottom: none;
        }

        /* Formulários modernos */
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #334155;
        }
        
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
        }
        
        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            color: #64748b;
        }
        
        .content-wrapper {
            margin-top: 60px;
            padding: 20px;
        }
        
        /* Badges e Status */
        .badge {
            display: inline-block;
            padding: 0.35em 0.65em;
            font-size: 0.75em;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }
        
        .badge-primary { background-color: var(--primary); }
        .badge-success { background-color: var(--success); }
        .badge-danger { background-color: var(--danger); }
        .badge-warning { background-color: var(--warning); }
        
        /* Dashboards Widgets */
        .stats-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 20px;
            text-align: center;
            transition: var(--transition);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
        }
        
        .stats-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            color: var(--primary);
        }
        
        .stats-card .number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stats-card .label {
            font-size: 0.9rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Grid Layout e Utilitários */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: -10px;
        }
        
        .col {
            flex: 1;
            padding: 10px;
            min-width: 0;
        }
        
        .col-12 { width: 100%; }
        .col-6 { width: 50%; }
        .col-4 { width: 33.33%; }
        .col-3 { width: 25%; }
        
        @media (max-width: 768px) {
            .col-6, .col-4, .col-3 {
                width: 100%;
            }
        }
        
        /* Utilitários */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-primary { color: var(--primary); }
        .text-success { color: var(--success); }
        .text-danger { color: var(--danger); }
        .text-warning { color: var(--warning); }
        
        .mt-1 { margin-top: 0.25rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 1rem; }
        .mt-4 { margin-top: 1.5rem; }
        .mt-5 { margin-top: 3rem; }
        
        .mb-1 { margin-bottom: 0.25rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 1rem; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mb-5 { margin-bottom: 3rem; }
        
        .d-flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .align-items-center { align-items: center; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-center { justify-content: center; }
        
        /* Responsividade */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-250px);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .navbar {
                left: 0;
            }
            
            .mobile-menu-open .sidebar {
                transform: translateX(0);
            }
            
            .menu-toggle {
                display: block;
            }
        }
        
        @media (min-width: 993px) {
            .menu-toggle {
                display: none;
            }
        }
        
        /* Menu toggle button */
        .menu-toggle {
            background: transparent;
            border: none;
            color: #333;
            font-size: 24px;
            cursor: pointer;
        }
        
        /* Estilização para dispositivos menores */
        @media (max-width: 576px) {
            .navbar {
                padding: 0 15px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .main-content {
                padding: 80px 15px 15px;
            }
            
            .card {
                padding: 15px;
            }
            
            .table th,
            .table td {
                padding: 10px;
            }
        }
    </style>
    
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- SweetAlert2 e animações para notificações modernas -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Estilos para notificações modernas -->
    <style>
        /* Notificações modernas */
        .notification-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            transition: all 0.3s ease;
        }
        
        .notification {
            margin-bottom: 10px;
            padding: 15px 20px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transform: translateX(100%);
            opacity: 0;
            animation: slide-in 0.4s forwards, fade-out 0.4s 3s forwards;
            display: flex;
            align-items: center;
            min-width: 280px;
            max-width: 380px;
        }
        
        .notification-success {
            background: linear-gradient(135deg, #43a047, #2e7d32);
            border-left: 4px solid #1b5e20;
            color: white;
        }
        
        .notification-error {
            background: linear-gradient(135deg, #e53935, #c62828);
            border-left: 4px solid #b71c1c;
            color: white;
        }
        
        .notification-icon {
            margin-right: 15px;
            font-size: 24px;
        }
        
        .notification-message {
            flex-grow: 1;
            font-weight: 500;
        }
        
        .notification-close {
            background: transparent;
            border: none;
            color: white;
            opacity: 0.7;
            cursor: pointer;
            font-size: 18px;
            transition: opacity 0.2s;
        }
        
        .notification-close:hover {
            opacity: 1;
        }
        
        @keyframes slide-in {
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fade-out {
            100% { opacity: 0; transform: translateY(-30px); }
        }
    </style>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">

    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    @yield('styles')
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-brand">
                <span>Sistema JhowJhow</span>
            </div>
            
            <!-- Sidebar Content -->
            @include('layouts.sidebar')
        </div>
        
        <!-- Overlay para fechar sidebar em dispositivos móveis -->
        <div id="sidebar-overlay"></div>
        
        <!-- Conteúdo Principal -->
        <div class="main-content">
            <!-- Header/Navbar -->
            <header class="navbar">
                <div class="navbar-container">
                    <!-- Botão do Menu Móvel -->
                    <button id="menu-toggle" class="menu-toggle">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <!-- Título da Página -->
                    <h1 class="navbar-title">@yield('title', 'Dashboard')</h1>
                    
                    <!-- Menu de Usuário -->
                    <div class="user-dropdown">
                        <button id="userDropdownBtn" class="dropdown-toggle">
                            <span class="user-name">{{ Auth::user()->name ?? 'Administrador' }}</span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        
                        <!-- Dropdown do Usuário -->
                        <div id="userDropdown" class="dropdown-menu">
                            @if(Auth::user()->nivel_acesso === 'administrador' || Auth::user()->nivel_acesso === 'dono')
                            <a href="{{ route('admin.create') }}" class="dropdown-item">
                                <i class="fas fa-user-plus"></i> Adicionar Usuário
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.users.index') }}" class="dropdown-item">
                                <i class="fas fa-users-cog"></i> Editar Usuários
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt"></i> Sair
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Conteúdo da Página -->
            <main class="flex-1 overflow-y-auto p-4">
                @if (session('success'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded" role="alert">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @yield('content')
            </main>
            
            <!-- Footer -->
            <footer class="bg-white p-4 border-t">
                <div class="text-center text-sm text-gray-500">
                    Sistema JhowJhow &copy; {{ date('Y') }}. Todos os direitos reservados.
                </div>
            </footer>
        </div>
    </div>
    
    <!-- jQuery e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts para menu móvel e dropdown -->
    <script>
        // Elementos DOM
        const menuToggle = document.getElementById('menu-toggle');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const sidebar = document.getElementById('sidebar');
        const userDropdownBtn = document.getElementById('userDropdownBtn');
        const userDropdown = document.getElementById('userDropdown');
        
        // Toggle menu em dispositivos móveis
        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                document.body.classList.toggle('mobile-menu-open');
                if (document.body.classList.contains('mobile-menu-open')) {
                    sidebar.style.transform = 'translateX(0)';
                    sidebarOverlay.style.display = 'block';
                } else {
                    sidebar.style.transform = '';
                    sidebarOverlay.style.display = 'none';
                }
            });
        }
        
        // Fechar sidebar ao clicar no overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                document.body.classList.remove('mobile-menu-open');
                sidebar.style.transform = '';
                sidebarOverlay.style.display = 'none';
            });
        }
        
        // Toggle dropdown do usuário
        if (userDropdownBtn && userDropdown) {
            userDropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                userDropdown.classList.toggle('show');
            });
            
            // Fechar dropdown ao clicar fora
            document.addEventListener('click', function(e) {
                if (!userDropdownBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }
    </script>
    
    @yield('scripts')
    <!-- Container para notificações modernas -->
    <div class="notification-container" id="notification-container"></div>
    
    <!-- Script para sistema de notificações -->
    <script>
        // Sistema de notificações modernas
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification notification-${type}`;
            
            // Ícone adequado ao tipo de notificação
            const icon = type === 'success' ? 'check-circle' : 'exclamation-circle';
            
            notification.innerHTML = `
                <div class="notification-icon">
                    <i class="fas fa-${icon}"></i>
                </div>
                <div class="notification-message">${message}</div>
                <button type="button" class="notification-close">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            container.appendChild(notification);
            
            // Botão de fechar
            const closeBtn = notification.querySelector('.notification-close');
            closeBtn.addEventListener('click', function() {
                notification.style.opacity = '0';
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            });
            
            // Auto-remover após 5 segundos
            setTimeout(() => {
                if (notification.parentNode === container) {
                    container.removeChild(notification);
                }
            }, 5000);
        }
    </script>
</body>
</html>
