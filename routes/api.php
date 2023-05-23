<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscountController;
use App\Models\Discount;
use Illuminate\Http\Request;
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
    Route::get('discount', [DiscountController::class, 'index']);
    Route::post('discount', [DiscountController::class, 'create']);
});