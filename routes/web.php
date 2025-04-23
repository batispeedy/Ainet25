<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    ProfileController,
    MembershipFeeController,
    StoreController,
    CartController,
    CheckoutController,
    SupplyOrderController,
    OrderController,
    InventoryController,
    EmployeeOrderController,
    CardController,
    CategoryController,
    ProductController,
    ShippingCostController,
    StatsController,
    UserController,
    BusinessSettingsController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página inicial
Route::get('/', fn() => view('home'))->name('home');

// Loja pública
Route::get('/loja', [StoreController::class, 'index'])->name('store.index');

// Carrinho (acesso livre)
Route::get('/carrinho', [CartController::class, 'index'])->name('cart.index');
Route::post('/carrinho/adicionar/{id}', [CartController::class, 'add'])->name('cart.add');
Route::match(['put', 'post'], '/carrinho/atualizar/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/carrinho/remover/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/carrinho/limpar', [CartController::class, 'clear'])->name('cart.clear');

// Rotas autenticadas (qualquer utilizador logado)
Route::middleware('auth')->group(function () {
    // Quota de associado
    Route::get('/membership/pay', [MembershipFeeController::class, 'show'])->name('membership.show');
    Route::post('/membership/pay', [MembershipFeeController::class, 'pay'])->name('membership.pay');
    Route::get('/membership/confirmation', fn() => view('membership.confirmation'))
        ->name('membership.confirmation');

    // Perfil de utilizador
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/topup', [ProfileController::class, 'topup'])->name('profile.topup');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Checkout de encomenda
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/confirm', [CheckoutController::class, 'confirm'])->name('checkout.confirm');

    // Pedidos do utilizador
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Cartão e histórico
    Route::get('/card', [CardController::class, 'show'])->name('card.show');
    Route::get('/card/history', [CardController::class, 'history'])->name('card.history');
});

// Dashboard (autenticado + e-mail verificado)
Route::middleware(['auth', 'verified'])
    ->get('/dashboard', fn() => view('dashboard'))
    ->name('dashboard');

// Supply Orders (apenas board)
Route::middleware(['auth', 'can:manage-inventory'])->group(function () {
    Route::get('/supply-orders',                [SupplyOrderController::class, 'index'])->name('supply_orders.index');
    Route::get('/supply-orders/create',         [SupplyOrderController::class, 'create'])->name('supply_orders.create');
    Route::post('/supply-orders',               [SupplyOrderController::class, 'store'])->name('supply_orders.store');
    Route::post('/supply-orders/{order}/complete', [SupplyOrderController::class, 'complete'])
        ->name('supply_orders.complete');
});

// Inventário (apenas roles com permissão)
Route::middleware(['auth', 'can:manage-inventory'])->group(function () {
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/inventory/low', [InventoryController::class, 'lowStock'])->name('inventory.low');
    Route::get('/inventory/out', [InventoryController::class, 'outOfStock'])->name('inventory.out');
    Route::post('/inventory/supply', [InventoryController::class, 'createSupplyOrder'])
        ->name('inventory.supply.create');
    Route::post('/inventory/supply/{id}/complete', [InventoryController::class, 'completeSupplyOrder'])
        ->name('inventory.supply.complete');
    Route::post('/inventory/{id}/adjust', [InventoryController::class, 'adjustStock'])
        ->name('inventory.adjust');
});

// Gestão de Categorias, Produtos, Portes e Utilizadores (apenas settings)
Route::middleware(['auth', 'can:manage-settings'])
    ->prefix('settings')
    ->name('settings.')
    ->group(function () {
        Route::resource('categories',     CategoryController::class)->except(['show']);
        Route::resource('products',       ProductController::class)->except(['show']);
        Route::resource('shipping_costs', ShippingCostController::class)->except(['show']);
        Route::resource('users',          UserController::class)->except(['show']);

        // Business Settings: quota de associado
        Route::get('membership-fee',   [BusinessSettingsController::class, 'editMembershipFee'])
            ->name('membership_fee.edit');
        Route::post('membership-fee',  [BusinessSettingsController::class, 'updateMembershipFee'])
            ->name('membership_fee.update');

        // Ações extra sobre utilizadores
        Route::post('users/{user}/block',    [UserController::class, 'block'])->name('users.block');
        Route::post('users/{user}/unblock',  [UserController::class, 'unblock'])->name('users.unblock');
        Route::post('users/{user}/promote',  [UserController::class, 'promote'])->name('users.promote');
        Route::post('users/{user}/demote',   [UserController::class, 'demote'])->name('users.demote');
        Route::post('users/{user}/restore',  [UserController::class, 'restore'])->name('users.restore');
    });

// Gestão interna de encomendas (EmployeeOrderController)
Route::middleware(['auth', 'can:manage-orders'])->group(function () {
    Route::get('/admin/orders', [EmployeeOrderController::class, 'index'])
        ->name('admin.orders.index');
    Route::post('/admin/orders/{id}/complete', [EmployeeOrderController::class, 'complete'])
        ->name('admin.orders.complete');
    Route::post('/admin/orders/{id}/cancel', [EmployeeOrderController::class, 'cancel'])
        ->name('admin.orders.cancel');
});

// Estatísticas
Route::middleware(['auth', 'can:manage-stats'])->group(function () {
    Route::get('stats', [StatsController::class, 'index'])->name('stats.index');
});
Route::middleware('auth')->group(function () {
    Route::get('stats/personal', [StatsController::class, 'personal'])->name('stats.personal');
});

// Autenticação (login, registo, reset, verificação de e-mail…)
require __DIR__ . '/auth.php';
