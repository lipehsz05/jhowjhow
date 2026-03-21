<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - {{ config('app.name', 'Sistema JhowJhow') }}</title>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- CSS Interno - Design Ultra Moderno -->
    <style>
        /* Variáveis CSS para tema */
        :root {
            --primary: #4361ee;
            --primary-light: #4895ef;
            --primary-dark: #3a0ca3;
            --secondary: #7209b7;
            --accent: #f72585;
            --success: #4cc9f0;
            --warning: #fb8500;
            --error: #d90429;
            --dark: #2b2d42;
            --gray-dark: #343a40;
            --gray: #6c757d;
            --gray-light: #f8f9fa;
            --white: #ffffff;
            --card-bg: rgba(255, 255, 255, 0.9);
            --input-bg: #f1f5f9;
            --input-border: #e2e8f0;
            --input-focus: #cbd5e1;
            --shadow-sm: 0 2px 4px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --gradient-primary: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            --gradient-secondary: linear-gradient(135deg, #7209b7 0%, #f72585 100%);
            --transition-fast: all 0.2s ease;
            --transition-normal: all 0.3s ease;
            --transition-slow: all 0.5s ease;
        }
        
        /* Estilos básicos */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Poppins', 'Segoe UI', sans-serif;
        }
        
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dark);
            line-height: 1.6;
            position: relative;
            background: linear-gradient(-45deg, #4361ee, #3a0ca3, #7209b7, #f72585);
            background-size: 400% 400%;
            animation: gradientBackground 15s ease infinite;
        }
        
        @keyframes gradientBackground {
            0% { background-position: 0% 50% }
            50% { background-position: 100% 50% }
            100% { background-position: 0% 50% }
        }

        /* Efeito de partículas no fundo */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.3);
            animation: float 8s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0; }
            25% { opacity: 0.8; }
            75% { opacity: 0.3; }
            50% { transform: translateY(-20px) translateX(10px); opacity: 0.5; }
        }

        .login-container {
            display: flex;
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            padding: 1rem;
        }
        
        .login-container .login-card {
            width: 100%;
            padding: 2.5rem;
            box-shadow: var(--shadow-lg);
            border-radius: 16px;
            background-color: var(--card-bg);
            position: relative;
            z-index: 1;
            overflow: hidden;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .login-header {
            text-align: center;
            margin-bottom: 30px;
            animation: fadeIn 0.8s ease;
        }
        
        .login-logo {
            margin-bottom: 15px;
            display: inline-block;
        }
        
        .login-title {
            color: var(--dark);
            font-size: 24px;
            font-weight: 700;
            margin: 8px 0 0;
        }
        
        .text-muted {
            color: var(--gray);
            font-size: 14px;
        }
        
        .mt-4 {
            margin-top: 1.5rem;
        }
        
        .login-footer {
            font-size: 12px;
            color: var(--gray);
            margin-top: 30px;
        }
        
        .login-form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        .login-form-group label {
            display: block;
            margin-bottom: 10px;
            color: var(--dark);
            font-size: 14px;
            font-weight: 500;
            transition: var(--transition-fast);
        }
        
        .login-input {
            width: 100%;
            padding: 14px 16px;
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 8px;
            font-size: 15px;
            color: var(--dark);
            transition: var(--transition-normal);
            box-sizing: border-box;
        }
        
        .login-input:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.15);
            background-color: var(--white);
        }
        
        .login-input:hover {
            border-color: var(--primary-light);
        }
        
        .login-form-group i {
            color: var(--primary);
            margin-right: 6px;
        }
        
        .remember-me {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            font-size: 14px;
            color: var(--text-color);
        }
        
        .remember-me input {
            margin-right: 8px;
            margin-top: 2px;
        }
        
        .remember-me small {
            display: block;
            color: #888;
            font-size: 12px;
            margin-top: 2px;
        }
        
        .login-button {
            display: flex;
            width: 100%;
            padding: 14px;
            background: var(--gradient-primary);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition-normal);
            justify-content: center;
            align-items: center;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-md);
        }
        
        .login-button i {
            margin-right: 10px;
        }
        
        .login-button:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-2px);
        }
        
        .login-button:active {
            transform: translateY(0);
            box-shadow: var(--shadow-sm);
        }
        
        .login-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: var(--transition-normal);
        }
        
        .login-button:hover::before {
            left: 100%;
            transition: 0.5s;
        }
        
        .invalid-feedback {
            display: block;
            color: var(--error-color);
            font-size: 13px;
            margin-top: 5px;
        }
        
        /* Animação do fundo */
        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        body {
            background-size: 200% 200%;
            animation: gradientAnimation 10s ease infinite;
        }
        
        /* Estilos para o toggle de senha */
        .password-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: transparent;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease;
        }
        
        .password-toggle:hover {
            color: var(--primary-color);
        }
    </style>
    
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Partículas decorativas -->
    <div class="particles" id="particles">
        <!-- As partículas serão adicionadas via JavaScript -->
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <i class="fas fa-store fa-3x" style="color: var(--primary)"></i>
                </div>
                <h1 class="login-title">Sistema JhowJhow</h1>
                <p class="text-muted mb-4">Gestão de Estoque e Vendas</p>
            </div>
        
        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf
            
            <div class="login-form-group">
                <label for="username">
                    <i class="fas fa-user"></i> Nome de Usuário
                </label>
                <input type="text" id="username" name="username" class="login-input @error('username') is-invalid @enderror" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="Digite seu nome de usuário">
                @error('username')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="login-form-group">
                <label for="password">
                    <i class="fas fa-lock"></i> Senha
                </label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" class="login-input @error('password') is-invalid @enderror" required autocomplete="current-password" placeholder="Digite sua senha">
                    <button type="button" class="password-toggle" id="password-toggle">
                        <i class="fas fa-eye-slash"></i>
                    </button>
                </div>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="remember-me">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">
                    Manter-me conectado
                </label>
            </div>
            
            <button type="submit" class="login-button">
                <i class="fas fa-sign-in-alt"></i>Entrar
            </button>
            
            <div class="login-footer mt-4 text-center">
                <p class="version">v1.0.2 &copy; {{ date('Y') }} - JhowJhow</p>
            </div>
        </form>
        </div>
        
        <!-- Mensagens de Erro -->
        @if ($errors->any())
        <div class="login-error">
            <div class="error-message">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        </div>
        @endif
        
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script para o mostrador de senha
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('password-toggle');
        
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Alterna o ícone
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
        
        // Criar partículas decorativas
        const particlesContainer = document.getElementById('particles');
        const particleCount = 20;
        
        for (let i = 0; i < particleCount; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            
            // Definir tamanho aleatório
            const size = Math.random() * 15 + 5;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            // Posição aleatória
            particle.style.top = `${Math.random() * 100}%`;
            particle.style.left = `${Math.random() * 100}%`;
            
            // Atraso na animação
            particle.style.animationDelay = `${Math.random() * 8}s`;
            particle.style.animationDuration = `${Math.random() * 12 + 8}s`;
            
            // Opacidade variável
            particle.style.opacity = Math.random() * 0.5 + 0.1;
            
            particlesContainer.appendChild(particle);
        }
        
        // Animação de entrada no login card
        const loginCard = document.querySelector('.login-card');
        loginCard.style.opacity = 0;
        loginCard.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            loginCard.style.transition = 'all 0.6s ease';
            loginCard.style.opacity = 1;
            loginCard.style.transform = 'translateY(0)';
        }, 200);
    });
    </script>
</body>
</html>
