<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AuthController extends Controller
{
    /**
     * Mostra a página de login
     */
    public function showLoginForm()
    {
        return View::make('auth.login');
    }

    /**
     * Processa a tentativa de login
     */
    public function login(Request $request)
    {
        // Sanitização básica de entrada (proteção contra SQL Injection)
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);
        
        // Laravel usa 'email' como campo padrão, então precisamos ajustar manualmente
        // para usar 'username' em vez de 'email'
        
        // Manter-me conectado: checkbox envia "remember" (boolean)
        $remember = $request->boolean('remember');
        $rememberMinutes = (int) config('auth.remember_minutes', 10080);

        if ($remember) {
            // Sessão e cookie "remember me" alinhados a 7 dias (ou AUTH_REMEMBER_MINUTES)
            config([
                'session.lifetime' => $rememberMinutes,
                'session.expire_on_close' => false,
            ]);
            $guard = Auth::guard();
            if (method_exists($guard, 'setRememberDuration')) {
                $guard->setRememberDuration($rememberMinutes);
            }
        } else {
            config(['session.expire_on_close' => true]);
        }

        // Autenticar usando o campo username
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            
            // Atualizar status do usuário para online
            $user = Auth::user();
            if ($user) {
                User::where('id', $user->id)->update([
                    'is_online' => true,
                    'last_activity' => now()
                ]);
            }
            
            // Redirecionar com base no nível de acesso
            if ($user->nivel_acesso === 'vendedor') {
                return redirect('sales');
            } elseif ($user->nivel_acesso === 'estoquista') {
                return redirect('inventory');
            } else {
                return Redirect::intended('dashboard');
            }
        }

        return Redirect::back()->withErrors([
            'username' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ]);
    }

    /**
     * Processa o logout do usuário
     */
    public function logout(Request $request)
    {
        // Marca o usuário como offline antes de logout
        if (Auth::check()) {
            $user = Auth::user();
            User::where('id', $user->id)->update([
                'is_online' => false,
                'last_activity' => now()
            ]);
        }
        
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return Redirect::route('login');
    }
    
    /**
     * Mostra o formulário de perfil do usuário
     */
    public function showProfileForm()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }
    
    /**
     * Atualiza os dados do perfil do usuário
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $data = ['name' => $request->name];

        if ($user->isDev()) {
            $data['show_in_online_users'] = $request->boolean('show_in_online_users');
        }

        User::where('id', $user->id)->update($data);
        
        return Redirect::route('profile')
            ->with('success', 'Perfil atualizado com sucesso!');
    }
    
    /**
     * Exibe o formulário de alteração de senha
     */
    public function showChangePasswordForm()
    {
        return View::make('auth.change-password');
    }
    
    /**
     * Processa a alteração de senha
     */
    public function changePassword(Request $request)
    {
        $user = Auth::user();
        
        $validator = $request->validate([
            'current_password' => ['required'],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[A-Z]/',      // Pelo menos uma letra maiúscula
                'regex:/[0-9]/',      // Pelo menos um número
                'regex:/[^\w\s]/'    // Pelo menos um caractere especial
            ],
        ]);
        
        $user = Auth::user();
        
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'A senha atual está incorreta']);
        }
        
        User::where('id', $user->id)->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return back()->with('password_success', 'Senha alterada com sucesso!');
    }
}
