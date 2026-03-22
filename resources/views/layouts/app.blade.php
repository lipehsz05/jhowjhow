<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@hasSection('title')@yield('title') — {{ $layoutSiteTitle ?? config('app.name', 'Sistema JhowJhow') }}@else{{ $layoutSiteTitle ?? config('app.name', 'Sistema JhowJhow') }}@endif</title>
    @php
        $__favicon = asset('logo/jhow-jhow-mark.png');
        if (is_file(public_path('logo/jhow-jhow-mark.png'))) {
            $__favicon .= '?v='.filemtime(public_path('logo/jhow-jhow-mark.png'));
        }
    @endphp
    <link rel="icon" type="image/png" href="{{ $__favicon }}" sizes="any">
    <link rel="shortcut icon" type="image/png" href="{{ $__favicon }}">
    <link rel="apple-touch-icon" href="{{ $__favicon }}">
    
    <!-- CSS Interno (Solução emergencial) - Design Moderno -->
    <style>
        /* Variáveis CSS para tema */
        /* Tema preto & branco (cinzas neutros para hierarquia e foco) */
        :root {
            --primary: #0a0a0a;
            --primary-dark: #000000;
            --secondary: #262626;
            --success: #404040;
            --danger: #1a1a1a;
            --warning: #737373;
            --info: #525252;
            --dark: #0a0a0a;
            --light: #fafafa;
            --body-bg: #f0f0f2;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.08), 0 1px 3px rgba(0, 0, 0, 0.06);
            --transition: all 0.3s ease;
            /* Scrollbar (fallback se color-mix não existir) */
            --scrollbar-site-track: #e4e4e8;
            --scrollbar-site-thumb: #3f3f46;
            --scrollbar-site-thumb-hover: #27272a;
            --scrollbar-sidebar-track: rgba(255, 255, 255, 0.06);
            --scrollbar-sidebar-thumb: rgba(255, 255, 255, 0.3);
            --scrollbar-sidebar-thumb-hover: rgba(255, 255, 255, 0.48);
            --scrollbar-size: 10px;
            --scrollbar-size-thin: 7px;
        }

        /* Sobrescreve tema quando definido no painel DEV (site_settings) */
        :root {
            --primary: {{ $layoutPrimaryColor ?? '#0a0a0a' }};
            --primary-dark: {{ $layoutPrimaryDark ?? '#000000' }};
            --dark: {{ $layoutPrimaryColor ?? '#0a0a0a' }};
            --body-bg: {{ $layoutBodyBg ?? '#f0f0f2' }};
            /* Barras de rolagem — derivam do tema (área principal clara) */
            --scrollbar-site-track: color-mix(in srgb, var(--body-bg) 90%, var(--primary) 10%);
            --scrollbar-site-thumb: color-mix(in srgb, var(--primary) 40%, var(--body-bg) 60%);
            --scrollbar-site-thumb-hover: color-mix(in srgb, var(--primary) 62%, var(--body-bg) 38%);
            /* Sidebar escura: trilho sutil, thumb claro */
            --scrollbar-sidebar-track: rgba(255, 255, 255, 0.06);
            --scrollbar-sidebar-thumb: rgba(255, 255, 255, 0.3);
            --scrollbar-sidebar-thumb-hover: rgba(255, 255, 255, 0.48);
            --scrollbar-size: 10px;
            --scrollbar-size-thin: 7px;
        }
        
        /* Estilos básicos */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        html {
            overflow-x: hidden;
            max-width: 100%;
        }

        body {
            font-family: 'Nunito', 'Segoe UI', Arial, sans-serif;
            background-color: var(--body-bg);
            color: #1a1a1a;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            transition: var(--transition);
            overflow-x: hidden;
            max-width: 100%;
            position: relative;
        }

        /* Scrollbar — área principal / página (Firefox) */
        html {
            scrollbar-gutter: stable;
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-site-thumb) var(--scrollbar-site-track);
        }
        /* Scrollbar — área principal (Chrome, Edge, Safari) */
        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: var(--scrollbar-size);
            height: var(--scrollbar-size);
        }
        html::-webkit-scrollbar-track,
        body::-webkit-scrollbar-track {
            background: var(--scrollbar-site-track);
            border-radius: 999px;
        }
        html::-webkit-scrollbar-thumb,
        body::-webkit-scrollbar-thumb {
            background: var(--scrollbar-site-thumb);
            border-radius: 999px;
            border: 2px solid var(--scrollbar-site-track);
        }
        html::-webkit-scrollbar-thumb:hover,
        body::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-site-thumb-hover);
        }
        html::-webkit-scrollbar-corner,
        body::-webkit-scrollbar-corner {
            background: var(--scrollbar-site-track);
        }

        main.overflow-y-auto {
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-site-thumb) var(--scrollbar-site-track);
        }
        main.overflow-y-auto::-webkit-scrollbar {
            width: var(--scrollbar-size);
            height: var(--scrollbar-size);
        }
        main.overflow-y-auto::-webkit-scrollbar-track {
            background: var(--scrollbar-site-track);
            border-radius: 999px;
        }
        main.overflow-y-auto::-webkit-scrollbar-thumb {
            background: var(--scrollbar-site-thumb);
            border-radius: 999px;
            border: 2px solid var(--scrollbar-site-track);
        }
        main.overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-site-thumb-hover);
        }
        main.overflow-y-auto::-webkit-scrollbar-corner {
            background: var(--scrollbar-site-track);
        }

        /* Sidebar estilizada */
        .sidebar {
            background: linear-gradient(180deg, var(--primary) 0%, #141414 100%);
            color: #ffffff;
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
            scrollbar-width: thin;
            scrollbar-color: var(--scrollbar-sidebar-thumb) var(--scrollbar-sidebar-track);
        }
        .sidebar-nav::-webkit-scrollbar {
            width: var(--scrollbar-size-thin);
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: var(--scrollbar-sidebar-track);
            border-radius: 999px;
            margin: 6px 0;
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: var(--scrollbar-sidebar-thumb);
            border-radius: 999px;
        }
        .sidebar-nav::-webkit-scrollbar-thumb:hover {
            background: var(--scrollbar-sidebar-thumb-hover);
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
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-left: 3px solid #ffffff;
        }
        
        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.12);
            color: #ffffff;
            border-left: 3px solid #ffffff;
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
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-left: 3px solid #a3a3a3;
        }

        /* Submenu DEV (somente conta desenvolvedor) */
        .sidebar-dev-wrap {
            list-style: none;
            margin: 0 0 2px 0;
        }
        .sidebar-dev-details {
            margin: 0;
            padding: 0;
        }
        .sidebar-dev-details summary {
            list-style: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.85);
            border-left: 3px solid transparent;
            user-select: none;
        }
        .sidebar-dev-details summary::-webkit-details-marker {
            display: none;
        }
        .sidebar-dev-details summary i {
            margin-right: 10px;
            font-size: 18px;
            width: 20px;
            text-align: center;
        }
        .sidebar-dev-details summary:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #fff;
        }
        .sidebar-dev-details[open] summary {
            background-color: rgba(255, 255, 255, 0.06);
            border-left-color: #a78bfa;
        }
        .sidebar-dev-submenu {
            list-style: none;
            margin: 0;
            padding: 0 0 6px 0;
            background: rgba(0, 0, 0, 0.2);
        }
        .sidebar-dev-submenu li {
            margin: 0;
        }
        .sidebar-dev-submenu a {
            padding-left: 36px !important;
            font-size: 0.92rem;
            border-left-width: 3px !important;
        }
        .sidebar-dev-submenu a.active {
            border-left-color: #a78bfa !important;
        }

        /* Layout principal */
        .app-container {
            display: flex;
            min-height: 100vh;
            max-width: 100%;
            overflow-x: hidden;
        }
        
        .main-content {
            margin-left: 250px;
            /* 60px navbar + pequeno respiro — evita duplicar com .content-wrapper */
            padding: 68px 24px 24px;
            min-height: 100vh;
            transition: var(--transition);
            flex: 1;
            min-width: 0;
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
        }
        
        /* Sidebar brand/logo */
        .sidebar-brand {
            padding: 16px 12px;
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background-color: var(--primary);
            border-bottom: 1px solid rgba(255, 255, 255, 0.12);
            user-select: none;
            -webkit-user-select: none;
            -webkit-touch-callout: none;
        }

        .sidebar-brand__mark,
        .sidebar-brand__wordmark {
            filter: invert(1);
            user-select: none;
            -webkit-user-select: none;
        }

        .sidebar-brand__mark {
            height: 42px;
            width: auto;
            max-width: 38%;
            object-fit: contain;
            flex-shrink: 0;
            display: block;
        }

        .sidebar-brand__wordmark {
            height: 32px;
            width: auto;
            max-width: 56%;
            min-width: 0;
            object-fit: contain;
            object-position: left center;
            flex: 1 1 auto;
            display: block;
        }

        @media (max-width: 992px) {
            .sidebar-brand {
                gap: 10px;
                padding: 14px 10px;
            }
            .sidebar-brand__mark {
                height: 38px;
            }
            .sidebar-brand__wordmark {
                height: 28px;
            }
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
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Card com header+body (padrão Bootstrap): sem padding duplo que estoura a largura no mobile */
        .card:has(> .card-body) {
            padding: 0;
            overflow: hidden;
        }
        .card:has(> .card-body) > .card-header {
            padding: 1rem clamp(0.75rem, 3vw, 1.25rem);
            margin-bottom: 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
            font-weight: 700;
            color: var(--dark);
            font-size: clamp(1rem, 2.8vw, 1.2rem);
        }
        .card > .card-body {
            padding: clamp(0.75rem, 2.5vw, 1.25rem);
            max-width: 100%;
            box-sizing: border-box;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .card:not(:has(> .card-body)) .card-header {
            border-bottom: 1px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
            font-weight: 700;
            color: var(--dark);
            font-size: 1.2rem;
        }

        .card .card {
            max-width: 100%;
        }

        img.img-thumbnail,
        .card img {
            max-width: 100%;
            height: auto;
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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }
        
        .btn-secondary {
            background-color: var(--secondary);
            color: #ffffff;
        }
        
        .btn-secondary:hover {
            background-color: #171717;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: #ffffff;
        }
        
        .btn-danger:hover {
            background-color: #000000;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        .btn-success {
            background-color: var(--success);
            color: #ffffff;
        }
        
        .btn-success:hover {
            background-color: #2a2a2a;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* WhatsApp no tema P&B: preto/branco (reconhecível pelo ícone) */
        .btn-whatsapp {
            background-color: #0a0a0a;
            color: #fff !important;
            border: 1px solid #0a0a0a;
        }

        .btn-whatsapp:hover {
            background-color: #ffffff;
            color: #0a0a0a !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .btn-whatsapp:focus {
            color: #fff !important;
        }
        
        .btn-warning {
            background-color: var(--warning);
            color: #ffffff;
        }
        
        .btn-warning:hover {
            background-color: #525252;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
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
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.12);
        }
        
        .form-text {
            display: block;
            margin-top: 5px;
            font-size: 14px;
            color: #64748b;
        }
        
        .content-wrapper {
            margin-top: 0;
            padding: 0;
        }

        /* Breadcrumb (classes tipo Bootstrap; sem bootstrap.css o <ol> virava lista 1. 2. 3.) */
        ol.breadcrumb {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0;
            padding: 0;
            margin: 0 0 1rem;
            list-style: none;
            font-size: 0.875rem;
        }

        ol.breadcrumb .breadcrumb-item {
            display: flex;
            align-items: center;
        }

        ol.breadcrumb .breadcrumb-item + .breadcrumb-item::before {
            content: '/';
            float: none;
            padding: 0 0.5rem;
            color: #94a3b8;
            font-weight: 400;
        }

        /* Links = páginas “anteriores”; página atual = .active em destaque azul */
        ol.breadcrumb .breadcrumb-item a {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
        }

        ol.breadcrumb .breadcrumb-item a:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        ol.breadcrumb .breadcrumb-item.active {
            color: var(--primary);
            font-weight: 600;
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
        .badge-info { background-color: var(--info); }
        .badge.bg-success { background-color: #404040 !important; color: #fff !important; }
        .badge.bg-danger { background-color: #1a1a1a !important; color: #fff !important; }
        .badge.bg-warning { background-color: #e5e5e5 !important; color: #0a0a0a !important; }
        .badge.bg-info { background-color: #525252 !important; color: #fff !important; }
        .badge.bg-primary { background-color: var(--primary) !important; color: #fff !important; }
        .badge.bg-dark { background-color: var(--dark) !important; color: #fff !important; }
        .badge.bg-secondary { background-color: #737373 !important; color: #fff !important; }
        
        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #0a0a0a;
        }
        .invalid-feedback {
            display: block;
            width: 100%;
            font-size: 0.875rem;
            color: #0a0a0a;
            margin-top: 0.35rem;
        }
        
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
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }
        
        .col {
            flex: 1;
            padding-left: 0.75rem;
            padding-right: 0.75rem;
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
        .d-block { display: block; }
        .flex-wrap { flex-wrap: wrap; }
        .flex-fill { flex: 1 1 auto; }
        .align-items-center { align-items: center; }
        .align-items-start { align-items: flex-start; }
        .justify-content-between { justify-content: space-between; }
        .justify-content-center { justify-content: center; }
        .justify-content-end { justify-content: flex-end; }
        
        .flex-1 {
            flex: 1 1 auto;
            min-width: 0;
            max-width: 100%;
        }

        main.flex-1 {
            overflow-x: hidden;
        }

        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 1rem; }
        
        .w-100 { width: 100% !important; }
        .h-100 { height: 100%; }
        .mb-0 { margin-bottom: 0 !important; }
        .me-1 { margin-right: 0.25rem; }
        .me-2 { margin-right: 0.5rem; }
        .me-3 { margin-right: 1rem; }
        .ms-1 { margin-left: 0.25rem; }
        .ms-2 { margin-left: 0.5rem; }
        .ms-3 { margin-left: 1rem; }
        .ms-4 { margin-left: 1.5rem; }
        .px-4 { padding-left: 1rem; padding-right: 1rem; }
        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
        .p-4 { padding: 1rem; }
        .text-muted { color: #64748b !important; }
        .fw-bold { font-weight: 700; }
        .shadow-sm { box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08); }
        .border { border: 1px solid #e2e8f0; }
        
        .container-fluid {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            min-width: 0;
        }
        
        /* Tabelas: rolagem horizontal em telas estreitas */
        .table-responsive {
            width: 100%;
            max-width: 100%;
            min-width: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 0.5rem;
        }

        /* DataTables não pode forçar largura maior que a tela */
        .dataTables_wrapper {
            width: 100% !important;
            max-width: 100%;
            overflow-x: auto;
        }
        .table-responsive .table {
            margin-bottom: 0;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #e2e8f0;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }
        
        .btn-sm {
            padding: 0.35rem 0.65rem;
            font-size: 0.875rem;
            gap: 0.35rem;
        }
        .btn-info {
            background-color: #404040;
            color: #ffffff;
        }
        .btn-info:hover {
            background-color: #262626;
            color: #ffffff;
        }
        .btn-outline-secondary {
            background: transparent;
            color: #64748b;
            border: 1px solid #cbd5e1;
        }
        .btn-outline-secondary:hover {
            background: #f1f5f9;
            color: #334155;
        }
        
        .form-select {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 16px;
            background-color: #fff;
            cursor: pointer;
        }
        .form-select:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.12);
        }
        
        .input-group {
            display: flex;
            flex-wrap: nowrap;
            width: 100%;
            align-items: stretch;
        }
        .input-group .form-control {
            flex: 1 1 auto;
            min-width: 0;
        }
        .input-group-text {
            display: flex;
            align-items: center;
            padding: 0 12px;
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-right: none;
            font-size: 0.9rem;
            color: #64748b;
            white-space: nowrap;
        }
        .input-group > *:not(:last-child) {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .input-group > *:not(:first-child) {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            margin-left: -1px;
        }
        .input-group > *:only-child {
            border-radius: 6px !important;
            margin-left: 0;
        }
        .input-group .input-group-text:first-child {
            border-radius: 6px 0 0 6px;
        }
        .input-group .btn {
            flex-shrink: 0;
        }
        
        /* Cabeçalhos de card: título + botão empilham no mobile */
        .card-header .d-flex {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        @media (max-width: 576px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: stretch !important;
            }
            .card-header .d-flex > a.btn,
            .card-header .d-flex > .btn {
                width: 100%;
            }
        }
        
        /* Ações em linhas de tabela */
        .table td .d-flex {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        @media (max-width: 576px) {
            .table td .d-flex {
                flex-direction: column;
                align-items: stretch;
            }
            .table td .d-flex .btn,
            .table td .d-flex a.btn {
                width: 100%;
                min-width: 0;
            }
        }
        
        /* Grid tipo Bootstrap: col-md-* (breakpoint 768px) */
        .row > [class*="col-"] {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
            box-sizing: border-box;
        }
        .row > .col-12 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        .row > [class*="col-md-"],
        .row > [class*="offset-md-"] {
            flex: 0 0 100%;
            max-width: 100%;
        }
        @media (min-width: 768px) {
            .row > .col-md-3 { flex: 0 0 25%; max-width: 25%; }
            .row > .col-md-4 { flex: 0 0 33.333333%; max-width: 33.333333%; }
            .row > .col-md-5 { flex: 0 0 41.666667%; max-width: 41.666667%; }
            .row > .col-md-6 { flex: 0 0 50%; max-width: 50%; }
            .row > .col-md-8 { flex: 0 0 66.666667%; max-width: 66.666667%; }
            .row > .col-md-12 { flex: 0 0 100%; max-width: 100%; }
            .row > .offset-md-1 { margin-left: 8.333333%; }
            .row > .offset-md-6 { margin-left: 50%; }
        }
        
        @media (max-width: 767px) {
            /* Margens negativas do .row somadas ao padding geram scroll horizontal */
            .row {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .row > [class*="col-"] {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                max-width: 100%;
            }
            .row > [class*="offset-md-"] {
                margin-left: 0 !important;
            }
            .row > [class*="col-md-"].px-4 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }
            .card .card.ms-3,
            .card .card.ms-4,
            .row .card.ms-3,
            .row .card.ms-4 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
            .card:hover {
                transform: none;
                box-shadow: var(--card-shadow);
            }
            .stats-card:hover {
                transform: none;
            }
            .card .d-flex.justify-content-end.gap-3 {
                flex-direction: column;
                align-items: stretch !important;
            }
            .card .d-flex.justify-content-end.gap-3 > .btn,
            .card .d-flex.justify-content-end.gap-3 > a.btn {
                width: 100%;
            }
        }
        
        .container-fluid > h1.mt-4,
        .container-fluid h1.mt-4 {
            font-size: clamp(1.2rem, 4vw, 1.5rem);
            line-height: 1.3;
            word-break: break-word;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 1px solid transparent;
        }
        .alert-success {
            background: #f4f4f5;
            border-color: #d4d4d4;
            color: #0a0a0a;
        }
        .alert-danger {
            background: #f4f4f5;
            border-color: #737373;
            color: #0a0a0a;
        }
        .alert ul { margin: 0; padding-left: 1.25rem; }
        .alert-dismissible { padding-right: 2.5rem; position: relative; }
        .btn-close {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 1.25rem;
            height: 1.25rem;
            border: none;
            background: transparent;
            cursor: pointer;
            opacity: 0.5;
            font-size: 1.25rem;
            line-height: 1;
        }
        .btn-close:hover { opacity: 1; }
        
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
                padding: 0 12px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
            
            .navbar-title {
                font-size: 1rem;
                max-width: min(220px, 42vw);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            
            .main-content {
                padding: 68px 12px 12px;
            }
            
            /* Não recolocar padding no card que já usa header/body */
            .card:not(:has(> .card-body)) {
                padding: 15px;
            }
            .card:has(> .card-body) {
                padding: 0;
            }
            
            .table {
                font-size: 0.8125rem;
            }
            
            .table th,
            .table td {
                padding: 8px 10px;
                word-break: break-word;
            }
            
            .container-fluid.px-4 {
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
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
            background: linear-gradient(135deg, #262626, #0a0a0a);
            border-left: 4px solid #ffffff;
            color: #ffffff;
        }
        
        .notification-error {
            background: linear-gradient(135deg, #404040, #171717);
            border-left: 4px solid #ffffff;
            color: #ffffff;
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
                <img
                    class="sidebar-brand__mark"
                    src="{{ asset('logo/jhow-jhow-mark.png') }}"
                    width="120"
                    height="120"
                    alt="Ícone"
                    draggable="false"
                    loading="eager"
                    decoding="async">
                <img
                    class="sidebar-brand__wordmark"
                    src="{{ asset('logo/jhow-jhow-wordmark.png') }}"
                    width="280"
                    height="80"
                    alt="Logo"
                    draggable="false"
                    loading="eager"
                    decoding="async">
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
                            @if(Auth::user()->nivel_acesso === 'administrador' || Auth::user()->hasDonoLevelAccess())
                            <a href="{{ route('admin.create') }}" class="dropdown-item">
                                <i class="fas fa-user-plus"></i> Adicionar Usuário
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('admin.users.index') }}" class="dropdown-item">
                                <i class="fas fa-users-cog"></i> Editar Usuários
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif
                            @if(Auth::user()->isDev())
                            <a href="{{ route('dev.index') }}" class="dropdown-item">
                                <i class="fas fa-code"></i> Painel DEV
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
            <main class="flex-1 overflow-y-auto pt-2 px-3 pb-4">
                @if (session('success'))
                    @php $flashSuccess = session('success'); @endphp
                    <div class="alert alert-success" role="alert">
                        @if(is_array($flashSuccess))
                            <p class="mb-0">{{ $flashSuccess['title'] ?? 'Operação concluída.' }}</p>
                        @else
                            <p class="mb-0" style="white-space: pre-wrap;">{{ $flashSuccess }}</p>
                        @endif
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        <p class="mb-0">{{ session('error') }}</p>
                    </div>
                @endif
                
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
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
                    {{ $layoutSiteTitle ?? config('app.name', 'Sistema JhowJhow') }} &copy; {{ date('Y') }}. Todos os direitos reservados.
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
