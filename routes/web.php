<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MembershipFeeController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SupplyOrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página inicial
Route::get('/', function () {
    return view('home');
})->name('home');

// Loja visível a todos
Route::get('/loja', [StoreController::class, 'index'])->name('store.index');

// Carrinho (acesso livre)
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

// Autenticados
Route::middleware(['auth'])->group(function () {

    // Checkout (apenas membros)
    Route::middleware('member.only')->group(function () {
        Route::get('/checkout', fn () => view('checkout.index'))->name('checkout');
        Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');
    });

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/topup', [ProfileController::class, 'topup'])->name('profile.topup');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Pagamento de Quota (visível a todos os autenticados)
    Route::get('/membership/pay', [MembershipFeeController::class, 'show'])->name('membership.show');
    Route::post('/membership/pay', [MembershipFeeController::class, 'pay'])->name('membership.pay');
});

// Dashboard (autenticado + verificado)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
});

// Supply Orders (só para direção)
Route::middleware(['auth', 'board.only'])->group(function () {
    Route::get('/supply-orders', [SupplyOrderController::class, 'index'])->name('supply_orders.index');
    Route::get('/supply-orders/create', [SupplyOrderController::class, 'create'])->name('supply_orders.create');
    Route::post('/supply-orders', [SupplyOrderController::class, 'store'])->name('supply_orders.store');
    Route::post('/supply-orders/{order}/complete', [SupplyOrderController::class, 'complete'])->name('supply_orders.complete');
});

// Auth scaffolding
require __DIR__.'/auth.php';
