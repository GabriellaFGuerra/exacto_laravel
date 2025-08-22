<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CustomerController;
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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Budget routes
    Route::resource('budgets', BudgetController::class);
    Route::get('customer/budgets', [BudgetController::class, 'customerBudgets'])->name('customer.budgets');
    Route::get('budgets/{budget}/download/{fileIndex}', [BudgetController::class, 'downloadFile'])->name('budgets.download');
    Route::get('budgets/{budget}/provider/{provider}/download', [BudgetController::class, 'downloadProviderFile'])->name('budgets.provider.download');
    Route::post('budgets/{budget}/providers', [BudgetController::class, 'addProvider'])->name('budgets.providers.add');
    Route::put('budgets/{budget}/providers/{budgetProvider}', [BudgetController::class, 'updateProvider'])->name('budgets.providers.update');
    Route::delete('budgets/{budget}/providers/{budgetProvider}', [BudgetController::class, 'removeProvider'])->name('budgets.providers.remove');

    // Customer routes
    Route::resource('customers', CustomerController::class);

    // Infraction routes
    Route::resource('infractions', InfractionController::class);

    // Document routes
    Route::resource('documents', DocumentController::class);

    // Provider routes
    Route::resource('providers', ProviderController::class);

    // Service Type routes
    Route::resource('service-types', ServiceTypeController::class)->names('service_types');

    // Document Type routes
    Route::resource('document-types', DocumentTypeController::class)->names('document_types');

    // Manager routes
    Route::resource('managers', ManagerController::class);
});

require __DIR__ . '/auth.php';
