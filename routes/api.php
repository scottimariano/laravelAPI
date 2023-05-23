<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscountController;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function ()
{
    Route::get('logout', [AuthController::class, 'logout']);
    Route::get('discount/download', [DiscountController::class, 'downloadCSV']);
    Route::get('discount/', [DiscountController::class, 'index']);
    Route::get('discount/{id}', [DiscountController::class, 'show']);
    Route::post('discount/', [DiscountController::class, 'store']);
    Route::put('discount/{id}', [DiscountController::class, 'update']);
    Route::delete('discount/{id}', [DiscountController::class, 'destroy']);
    Route::put('discount/{id}/restore', [DiscountController::class, 'restore']);
});