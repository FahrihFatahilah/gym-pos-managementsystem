<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Auth::routes();

// Home redirect to dashboard after login
Route::get('/home', function () {
    if (auth()->check() && auth()->user()->role === 'pt') {
        return redirect()->route('pt-members.index');
    }
    return redirect()->route('dashboard');
})->name('home');

// Protected routes
Route::middleware(['auth', 'redirect.pt'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Members Management
    Route::resource('members', MemberController::class);
    Route::get('members-expired', [MemberController::class, 'expired'])->name('members.expired');
    Route::get('members/{member}/renew', [MemberController::class, 'renew'])->name('members.renew');
    Route::post('members/{member}/renew', [MemberController::class, 'processRenewal'])->name('members.process-renewal');
    
    // Memberships Management
    Route::resource('memberships', MembershipController::class);
    Route::post('memberships/{membership}/extend', [MembershipController::class, 'extend'])->name('memberships.extend');
    
    // Products Management
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Admin only product actions
    Route::middleware(['role:admin'])->group(function () {
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });
    
    // Stock Management
    Route::get('stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('stocks/history', [StockController::class, 'history'])->name('stocks.history');
    
    // POS System
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        Route::post('/transaction', [POSController::class, 'processTransaction'])->name('transaction');
        Route::get('/receipt/{transaction}', [POSController::class, 'printReceipt'])->name('receipt');
        Route::get('/search-product', [POSController::class, 'searchProduct'])->name('search-product');
        Route::get('/history', [POSController::class, 'history'])->name('history');
    });
    
    // Reports
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
        Route::get('/members', [ReportController::class, 'members'])->name('members');
        Route::get('/memberships', [ReportController::class, 'memberships'])->name('memberships');
        Route::get('/stocks', [ReportController::class, 'stocks'])->name('stocks');
        Route::get('/export/sales', [ReportController::class, 'exportSales'])->name('export.sales');
        Route::get('/export/stocks', [ReportController::class, 'exportStocks'])->name('export.stocks');
        Route::get('/export/members', [ReportController::class, 'exportMembers'])->name('export.members');
        Route::get('/export/memberships', [ReportController::class, 'exportMemberships'])->name('export.memberships');
    });
    
    // Transactions
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionController::class, 'index'])->name('index');
        Route::get('/{transaction}', [TransactionController::class, 'show'])->name('show');
        Route::get('/{transaction}/receipt', [TransactionController::class, 'receipt'])->name('receipt');
    });
    
    // Accounting - Simple
    Route::get('/accounting', [App\Http\Controllers\AccountingController::class, 'simple'])->name('accounting.index');
    Route::post('/accounting/expense', [App\Http\Controllers\AccountingController::class, 'storeExpense'])->name('accounting.expense.store');
    Route::delete('/accounting/expense/{expense}', [App\Http\Controllers\AccountingController::class, 'deleteExpense'])->name('accounting.expense.delete');
    Route::post('/accounting/expense/{id}/restore', [App\Http\Controllers\AccountingController::class, 'restoreExpense'])->name('accounting.expense.restore');
    Route::post('/accounting/expenses/restore-all', [App\Http\Controllers\AccountingController::class, 'restoreAllExpenses'])->name('accounting.expenses.restore-all');
    
    // PT Member Management
    Route::middleware(['auth'])->prefix('pt-members')->name('pt-members.')->group(function () {
        Route::get('/', [App\Http\Controllers\PTMemberController::class, 'index'])->name('index');
        Route::get('/{member}', [App\Http\Controllers\PTMemberController::class, 'show'])->name('show');
    });
    
    // Daily Users - accessible by both admin and staff
    Route::get('daily-users', [App\Http\Controllers\DailyUserController::class, 'index'])->name('daily-users.index');
    Route::get('daily-users/create', [App\Http\Controllers\DailyUserController::class, 'create'])->name('daily-users.create');
    Route::post('daily-users', [App\Http\Controllers\DailyUserController::class, 'store'])->name('daily-users.store');
    Route::get('daily-users/{dailyUser}', [App\Http\Controllers\DailyUserController::class, 'show'])->name('daily-users.show');
    Route::post('daily-users/check-history', [App\Http\Controllers\DailyUserController::class, 'checkHistory'])->name('daily-users.check-history');
    
    // Settings (Admin only)
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::delete('/settings/remove-logo', [App\Http\Controllers\SettingController::class, 'removeLogo'])->name('settings.remove-logo');
        Route::delete('/settings/remove-favicon', [App\Http\Controllers\SettingController::class, 'removeFavicon'])->name('settings.remove-favicon');
        
        Route::post('stocks/update', [StockController::class, 'updateStock'])->name('stocks.update');
        
        Route::put('daily-users/{dailyUser}', [App\Http\Controllers\DailyUserController::class, 'update'])->name('daily-users.update');
        Route::get('daily-users/{dailyUser}/edit', [App\Http\Controllers\DailyUserController::class, 'edit'])->name('daily-users.edit');
        Route::delete('daily-users/{dailyUser}', [App\Http\Controllers\DailyUserController::class, 'destroy'])->name('daily-users.destroy');
        
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        
        Route::resource('personal-trainers', App\Http\Controllers\PersonalTrainerController::class);
        
        // Branch Management
        Route::resource('branches', App\Http\Controllers\BranchController::class);
        
        // User Management
        Route::resource('users', App\Http\Controllers\UserManagementController::class);
    });
    
});

// API routes for AJAX calls
Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::get('/products/search', [POSController::class, 'searchProduct']);
    Route::get('/members/search', function(Illuminate\Http\Request $request) {
        $query = $request->get('q');
        $members = App\Models\Member::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->take(10)
            ->get(['id', 'name', 'phone']);
        return response()->json($members);
    });
});

// CSRF Token refresh route
Route::get('/csrf-token', function() {
    return response()->json(['csrf_token' => csrf_token()]);
})->middleware('web');

// PWA Manifest route
Route::get('/manifest.json', function() {
    return response()->file(public_path('manifest.json'))
        ->header('Content-Type', 'application/json');
});

// Service Worker route
Route::get('/sw.js', function() {
    return response()->file(public_path('sw.js'))
        ->header('Content-Type', 'application/javascript');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
