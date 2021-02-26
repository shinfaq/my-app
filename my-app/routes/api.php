<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\ReportController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/test', [Controller::class, 'get']);
Route::group(['prefix' => '/product'], function () {
    Route::get('', [ProductController::class, 'getAll']);
    Route::get('/{id}', [ProductController::class, 'getProduct']);
    Route::get('/type/{type}', [ProductController::class, 'getByType']);
    Route::post('', [ProductController::class, 'createProduct']);
    Route::put('', [ProductController::class, 'updateProduct']);
    Route::delete('/{id}', [ProductController::class, 'deleteProduct']);
});
Route::group(['prefix' => '/product_type'], function () {
    Route::get('', [ProductTypeController::class, 'getAll']);
    Route::get('/{id}', [ProductTypeController::class, 'getProductType']);
    Route::post('', [ProductTypeController::class, 'createProductType']);
    Route::put('', [ProductTypeController::class, 'updateProductType']);
    Route::delete('/{id}', [ProductTypeController::class, 'deleteProductType']);
});
Route::post('/upload', [UploadController::class, 'uploadFile']);
Route::group(['prefix' => '/order'], function () {
    Route::get('', [OrderController::class, 'getAll']);
    Route::get('/{id}', [OrderController::class, 'getOrder']);
    Route::post('', [OrderController::class, 'createOrder']);
    Route::put('', [OrderController::class, 'updateProduct']);
    // Route::delete('/{id}', [OrderController::class, 'deleteProductType']);
});
Route::group(['prefix' => '/report'], function () {
    Route::get('/type', [ReportController::class, 'getTotalOrderByType']);
    Route::get('/product', [ReportController::class, 'getTotalOrderByProduct']);
    Route::get('/total_money', [ReportController::class, 'totalMoney']);

});