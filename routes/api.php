<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/dummy', [Controller::class, 'dummy']); // Use controller method syntax
Route::get('/hello', function () {
    return response()->json(['message' => 'Hello, World!']);
});

Route::post('/register', [AuthController::class, 'register']); // Use controller method syntax
Route::post('/login', [AuthController::class, 'login']); // Use controller method syntax



Route::middleware(['auth', 'role:admin'])->group(function () {
    // Admin-only routes here
    //make a assign role api
});


Route::middleware('auth')->group(function () {
    
});

Route::middleware('auth:api')->group(function () {
    Route::get('/ledger', [TransactionController::class, 'ledger'])->name('ledger');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store')->middleware('throttle:1,2');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy')
        ->middleware(['role:admin']);

    //api endpoint to assign user role admin
    Route::put('/users/{user}/assign-admin', [Controller::class, 'assignAdminRole']);

});