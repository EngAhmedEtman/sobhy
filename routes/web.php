<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('products', ProductController::class)->except(['create', 'edit']);
    
    Route::resource('customers', CustomerController::class)->except(['create', 'edit']);

    Route::resource('suppliers', SupplierController::class)->except(['create', 'edit']);

    Route::get('/debts', [App\Http\Controllers\DebtController::class, 'index'])->name('debts.index');

    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/purchases', [App\Http\Controllers\ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('/reports/customers', [App\Http\Controllers\ReportController::class, 'customers'])->name('reports.customers');
    Route::get('/reports/suppliers', [App\Http\Controllers\ReportController::class, 'suppliers'])->name('reports.suppliers');
    Route::get('/reports/products', [App\Http\Controllers\ReportController::class, 'products'])->name('reports.products');
    Route::get('/reports/profit', [App\Http\Controllers\ReportController::class, 'profit'])->name('reports.profit');
    Route::get('/reports/debts', [App\Http\Controllers\ReportController::class, 'debts'])->name('reports.debts');

    Route::resource('users', App\Http\Controllers\UserController::class)->except(['create', 'edit', 'show']);
    Route::resource('roles', App\Http\Controllers\RoleController::class)->except(['create', 'edit', 'show']);

    Route::resource('transactions', TransactionController::class)->only(['show', 'update', 'destroy']);
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    Route::resource('purchases', PurchaseController::class);
    Route::resource('sales', SaleController::class);

    Route::get('/api/products/{product}/price-info', [ProductController::class, 'priceInfo'])->name('api.products.price-info');
    Route::get('/api/global-search', [\App\Http\Controllers\SearchController::class, 'globalSearch'])->name('api.global-search');

    // Print Routes
    Route::get('/sales/{sale}/print', [\App\Http\Controllers\PrintController::class, 'sale'])->name('print.sale');
    Route::get('/purchases/{purchase}/print', [\App\Http\Controllers\PrintController::class, 'purchase'])->name('print.purchase');
    Route::get('/customers/{customer}/print', [\App\Http\Controllers\PrintController::class, 'customerStatement'])->name('print.customer');
    Route::get('/suppliers/{supplier}/print', [\App\Http\Controllers\PrintController::class, 'supplierStatement'])->name('print.supplier');
    Route::get('/transactions/{transaction}/print', [\App\Http\Controllers\TransactionController::class, 'print'])->name('transactions.print');

    // Detailed pages
    Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('customers.show');
    Route::post('/customers/{id}/payment', [CustomerController::class, 'storePayment'])->name('customers.payment');
    Route::post('/customers/{id}/return', [CustomerController::class, 'storeReturn'])->name('customers.return');
    
    Route::get('/suppliers/{id}', [SupplierController::class, 'show'])->name('suppliers.show');
    Route::post('/suppliers/{id}/payment', [SupplierController::class, 'storePayment'])->name('suppliers.payment');
    Route::post('/suppliers/{id}/return', [SupplierController::class, 'storeReturn'])->name('suppliers.return');

    // Profile Routes
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
