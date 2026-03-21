<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\MovimentacaoEstoqueController;

// Rotas de autenticação (sem middleware para evitar loops)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->withoutMiddleware(\App\Http\Middleware\RoleRedirect::class);
Route::post('/login', [AuthController::class, 'login'])->withoutMiddleware(\App\Http\Middleware\RoleRedirect::class);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Redirecionar a raiz para o dashboard ou login, dependendo da autenticação
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check()) {
        return \Illuminate\Support\Facades\Redirect::route('dashboard');
    } else {
        return \Illuminate\Support\Facades\Redirect::route('login');
    }
});

// Rotas da API de clientes acessíveis sem autenticação
Route::post('/api/clientes', [App\Http\Controllers\ClientApiController::class, 'store']);
// Adicionar rota alternativa para clientes (caso a API não funcione)
Route::post('/cadastrar-cliente', [App\Http\Controllers\ClientApiController::class, 'store'])->name('clientes.api.store');

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/dados', [DashboardController::class, 'getDashboardData'])->name('dashboard.dados');
    
    // Perfil do usuário
    Route::get('/profile', [AuthController::class, 'showProfileForm'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // Administradores
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/create', [\App\Http\Controllers\AdminController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\AdminController::class, 'store'])->name('store');
        
        // Rotas para gerenciamento de usuários
        Route::get('/users', [\App\Http\Controllers\AdminController::class, 'index'])->name('users.index');
        Route::get('/users/{user}/edit', [\App\Http\Controllers\AdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'destroy'])->name('users.destroy');
    });
    
    // Estoque
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
        Route::get('/create', [InventoryController::class, 'create'])->name('create');
        Route::post('/', [InventoryController::class, 'store'])->name('store');
        Route::get('/{produto}/edit', [InventoryController::class, 'edit'])->name('edit');
        Route::put('/{produto}', [InventoryController::class, 'update'])->name('update');
        Route::delete('/{produto}', [InventoryController::class, 'destroy'])->name('destroy');
        
        // Movimentações de estoque
        Route::prefix('movimentacoes')->name('movimentacoes.')->group(function () {
            Route::get('/', [MovimentacaoEstoqueController::class, 'index'])->name('index');
            Route::get('/entrada', [MovimentacaoEstoqueController::class, 'createEntrada'])->name('entrada.create');
            Route::post('/entrada', [MovimentacaoEstoqueController::class, 'storeEntrada'])->name('entrada.store');
            Route::get('/saida', [MovimentacaoEstoqueController::class, 'createSaida'])->name('saida.create');
            Route::post('/saida', [MovimentacaoEstoqueController::class, 'storeSaida'])->name('saida.store');
            Route::get('/{movimentacao}', [MovimentacaoEstoqueController::class, 'show'])->name('show');
        });
    });
    
    // Vendas
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/create', [SalesController::class, 'create'])->name('create');
        Route::post('/', [SalesController::class, 'store'])->name('store');
        Route::get('/{venda}', [SalesController::class, 'show'])->name('show');
        Route::put('/{venda}/status', [SalesController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{venda}', [SalesController::class, 'destroy'])->name('destroy');
    });
    
    // Histórico
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryController::class, 'index'])->name('index');
        Route::get('/{venda}', [HistoryController::class, 'show'])->name('show');
    });
    
    // Clientes
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::get('/create', [ClientController::class, 'create'])->name('create');
        Route::post('/', [ClientController::class, 'store'])->name('store');
        Route::get('/{cliente}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::put('/{cliente}', [ClientController::class, 'update'])->name('update');
        Route::delete('/{cliente}', [ClientController::class, 'destroy'])->name('destroy');
    });
    
    // API para o dashboard e outros dados
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.data');
        
        // API para busca de produtos em vendas
        Route::get('/produtos/busca', [InventoryController::class, 'search'])->name('produtos.search');
        
        // API para busca de clientes em vendas
        Route::get('/clientes/busca', [ClientController::class, 'search'])->name('clientes.search');
    });
});
