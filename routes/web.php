<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InfractionController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProviderController;
use App\Http\Controllers\ServiceTypeController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\ManagerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
})->middleware(['guest']);

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rotas compartilhadas para usuários autenticados
Route::middleware(['auth'])->group(function () {
    // Adicionar 'active' como middleware em rotas que necessitam verificar se o usuário está ativo
    Route::middleware(['active'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // Documento - acesso para admin e customer
        Route::get('documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
    });
});

// Rotas exclusivas para administradores
Route::middleware(['auth', 'admin'])->group(function () {
    // Budget routes para admin
    Route::resource('budgets', BudgetController::class);
    Route::get('budgets/{budget}/download/{fileIndex}', [BudgetController::class, 'downloadFile'])->name('budgets.download');
    Route::get('budgets/{budget}/provider/{provider}/download', [BudgetController::class, 'downloadProviderFile'])->name('budgets.provider.download');
    Route::post('budgets/{budget}/providers', [BudgetController::class, 'addProvider'])->name('budgets.providers.add');
    Route::put('budgets/{budget}/providers/{budgetProvider}', [BudgetController::class, 'updateProvider'])->name('budgets.providers.update');
    Route::delete('budgets/{budget}/providers/{budgetProvider}', [BudgetController::class, 'removeProvider'])->name('budgets.providers.remove');

    // Rotas administrativas
    Route::resource('customers', CustomerController::class);
    Route::resource('infractions', InfractionController::class);
    Route::resource('documents', DocumentController::class)->except(['index', 'show']);
    Route::resource('providers', ProviderController::class);
    Route::resource('service-types', ServiceTypeController::class)->names('service_types');
    Route::resource('document-types', DocumentTypeController::class)->names('document_types');
    Route::resource('managers', ManagerController::class);
});

// Rotas exclusivas para clientes
Route::middleware(['auth', 'customer'])->group(function () {
    Route::middleware(['active'])->group(function () {
        Route::get('customer/budgets', [BudgetController::class, 'customerBudgets'])->name('customer.budgets');
    });
});

require __DIR__ . '/auth.php';
