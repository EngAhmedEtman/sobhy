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

    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // ─── Products ───────────────────────────────────────────────────────────────
    Route::get('/products', [ProductController::class, 'index'])->name('products.index')->middleware('permission:products.view');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store')->middleware('permission:products.create');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show')->middleware('permission:products.view');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update')->middleware('permission:products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy')->middleware('permission:products.delete');

    // ─── Customers ──────────────────────────────────────────────────────────────
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:customers.view');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('permission:customers.create');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show')->middleware('permission:customers.view');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update')->middleware('permission:customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy')->middleware('permission:customers.delete');

    // ─── Suppliers ──────────────────────────────────────────────────────────────
    Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index')->middleware('permission:suppliers.view');
    Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store')->middleware('permission:suppliers.create');
    Route::get('/suppliers/{supplier}', [SupplierController::class, 'show'])->name('suppliers.show')->middleware('permission:suppliers.view');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update')->middleware('permission:suppliers.update');
    Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy')->middleware('permission:suppliers.delete');

    // ─── Debts ──────────────────────────────────────────────────────────────────
    Route::get('/debts', [App\Http\Controllers\DebtController::class, 'index'])->name('debts.index')->middleware('permission:debts.view');

    // ─── Settings ───────────────────────────────────────────────────────────────
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index')->middleware('permission:settings.manage');
    Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update')->middleware('permission:settings.manage');

    // ─── Reports ────────────────────────────────────────────────────────────────
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index')->middleware('permission:reports.view');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales')->middleware('permission:reports.view');
    Route::get('/reports/purchases', [App\Http\Controllers\ReportController::class, 'purchases'])->name('reports.purchases')->middleware('permission:reports.view');
    Route::get('/reports/customers', [App\Http\Controllers\ReportController::class, 'customers'])->name('reports.customers')->middleware('permission:reports.view');
    Route::get('/reports/suppliers', [App\Http\Controllers\ReportController::class, 'suppliers'])->name('reports.suppliers')->middleware('permission:reports.view');
    Route::get('/reports/products', [App\Http\Controllers\ReportController::class, 'products'])->name('reports.products')->middleware('permission:reports.view');
    Route::get('/reports/profit', [App\Http\Controllers\ReportController::class, 'profit'])->name('reports.profit')->middleware('permission:reports.view');
    Route::get('/reports/debts', [App\Http\Controllers\ReportController::class, 'debts'])->name('reports.debts')->middleware('permission:reports.view');

    // ─── Users ──────────────────────────────────────────────────────────────────
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware('permission:users.view');
    Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware('permission:users.create');
    Route::put('/users/{user}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update')->middleware('permission:users.update');
    Route::delete('/users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:users.delete');

    // ─── Roles ──────────────────────────────────────────────────────────────────
    Route::get('/roles', [App\Http\Controllers\RoleController::class, 'index'])->name('roles.index')->middleware('permission:roles.view');
    Route::post('/roles', [App\Http\Controllers\RoleController::class, 'store'])->name('roles.store')->middleware('permission:roles.create');
    Route::put('/roles/{role}', [App\Http\Controllers\RoleController::class, 'update'])->name('roles.update')->middleware('permission:roles.update');
    Route::delete('/roles/{role}', [App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:roles.delete');

    // ─── Transactions ───────────────────────────────────────────────────────────
    Route::resource('transactions', TransactionController::class)->only(['show', 'update', 'destroy']);
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // ─── Purchases ──────────────────────────────────────────────────────────────
    Route::get('/purchases', [PurchaseController::class, 'index'])->name('purchases.index')->middleware('permission:purchases.view');
    Route::post('/purchases', [PurchaseController::class, 'store'])->name('purchases.store')->middleware('permission:purchases.create');
    Route::get('/purchases/{purchase}', [PurchaseController::class, 'show'])->name('purchases.show')->middleware('permission:purchases.view');
    Route::put('/purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update')->middleware('permission:purchases.update');
    Route::delete('/purchases/{purchase}', [PurchaseController::class, 'destroy'])->name('purchases.destroy')->middleware('permission:purchases.delete');

    // ─── Sales ──────────────────────────────────────────────────────────────────
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index')->middleware('permission:sales.view');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store')->middleware('permission:sales.create');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show')->middleware('permission:sales.view');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update')->middleware('permission:sales.update');
    Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy')->middleware('permission:sales.delete');

    // ─── API Routes ─────────────────────────────────────────────────────────────
    Route::get('/api/products/{product}/price-info', [ProductController::class, 'priceInfo'])->name('api.products.price-info');
    Route::get('/api/global-search', [App\Http\Controllers\SearchController::class, 'globalSearch'])->name('api.global-search');

    // ─── Print Routes ───────────────────────────────────────────────────────────
    Route::get('/sales/{sale}/print', [App\Http\Controllers\PrintController::class, 'sale'])->name('print.sale')->middleware('permission:sales.view');
    Route::get('/purchases/{purchase}/print', [App\Http\Controllers\PrintController::class, 'purchase'])->name('print.purchase')->middleware('permission:purchases.view');
    Route::get('/customers/{customer}/print', [App\Http\Controllers\PrintController::class, 'customerStatement'])->name('print.customer')->middleware('permission:customers.view');
    Route::get('/suppliers/{supplier}/print', [App\Http\Controllers\PrintController::class, 'supplierStatement'])->name('print.supplier')->middleware('permission:suppliers.view');
    Route::get('/print/customers-report', [App\Http\Controllers\PrintController::class, 'customersReport'])->name('print.customers-report')->middleware('permission:reports.view');
    Route::get('/print/suppliers-report', [App\Http\Controllers\PrintController::class, 'suppliersReport'])->name('print.suppliers-report')->middleware('permission:reports.view');
    Route::get('/print/sales-report', [App\Http\Controllers\PrintController::class, 'salesReport'])->name('print.sales-report')->middleware('permission:reports.view');
    Route::get('/print/purchases-report', [App\Http\Controllers\PrintController::class, 'purchasesReport'])->name('print.purchases-report')->middleware('permission:reports.view');

    // ─── Customer & Supplier Payments and Returns ────────────────────────────────
    Route::post('/customers/{id}/payment', [CustomerController::class, 'storePayment'])->name('customers.payment')->middleware('permission:customers.update');
    Route::post('/customers/{id}/return', [CustomerController::class, 'storeReturn'])->name('customers.return')->middleware('permission:customers.update');

    Route::post('/suppliers/{id}/payment', [SupplierController::class, 'storePayment'])->name('suppliers.payment')->middleware('permission:suppliers.update');
    Route::post('/suppliers/{id}/return', [SupplierController::class, 'storeReturn'])->name('suppliers.return')->middleware('permission:suppliers.update');

    // ─── Profile Routes ─────────────────────────────────────────────────────────
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
