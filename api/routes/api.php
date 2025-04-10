<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{Admin\CategoryBackController,
    BudgetController,
    CategoryController,
    ImageController,
    TransactionController,
    TranslationController,
    JwtAuthController};

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('locale')->group(function () {
    Route::get('translations', [TranslationController::class, 'getAll']);
    Route::post('login', [JwtAuthController::class, 'login']);
    Route::post('register', [JwtAuthController::class, 'register']);

    Route::middleware(['auth:api', 'auth.csrf.jwt'])->group(function () {
        Route::controller(JwtAuthController::class)->group(function () {
            Route::get('profile', 'profile');
            Route::get('refresh', 'refreshToken');
            Route::get('logout', 'logout');
        });

        Route::get('budget', [BudgetController::class, 'get']);

        Route::prefix('transactions')
            ->controller(TransactionController::class)
            ->group(function () {
                Route::post('/', 'create');
                Route::put('/{transaction_number}', 'update');
                Route::get('/', 'getAll');
                Route::get('/{transaction_number}', 'get');
                Route::delete('/{transaction_number}', 'delete');
            });

        Route::get('categories', [CategoryController::class, 'getAll']);

        Route::get('images/categoryIcons/{fileName}', [ImageController::class, 'getCategoryIcon']);

        Route::prefix('admin/categories')
            ->middleware('admin')
            ->controller(CategoryBackController::class)
            ->group(function () {
                Route::post('/', 'create');
                Route::get('/', 'getAll');
                Route::get('/{category_id}', 'get');
                Route::put('/{category_id}', 'update');
                Route::delete('/{category_id}', 'delete');
            });
    });
});

