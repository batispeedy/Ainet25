<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipFeeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
})->name('home');

// Página inicial - Loja visível a todos
Route::get('/loja', [StoreController::class, 'index'])->name('store.index');

// Rotas de utilizadores autenticados (sem exigir quota ainda)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/membership/pay', [MembershipFeeController::class, 'show'])->name('membership.show');
    Route::post('/membership/pay', [MembershipFeeController::class, 'pay'])->name('membership.pay');
});

// Rotas protegidas (só entra se estiver autenticado, verificado e com quota paga)
Route::middleware(['auth', 'verified', 'membership.paid'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Carrinho
    Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
    Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');
});

// Autenticação
require __DIR__.'/auth.php';
