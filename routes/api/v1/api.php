<?php


use App\Http\Controllers\api\v1\admin\ChargeController;
use App\Http\Controllers\api\v1\admin\MovementController;
use App\Http\Controllers\api\v1\admin\PaymentController;
use App\Http\Controllers\api\v1\admin\PDFController;
use App\Http\Controllers\api\v1\admin\propertyController;
use App\Http\Controllers\api\v1\admin\SettingController;
use App\Http\Controllers\api\v1\admin\UserController;
use App\Http\Controllers\api\v1\admin\UserPropertyController;
use App\Http\Controllers\api\v1\auth\LoginController;
use App\Http\Controllers\api\v1\auth\RegisterController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::group(['middleware' => 'auth:api'], function() {

    Route::GET('checkToken',[LoginController::class,'checkToken']);

});


Route::get('/users', [UserController::class, 'get']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::put('/users', [UserController::class, 'update']);
Route::post('/users', [UserController::class, 'store']);
Route::post('/users/delete', [UserController::class, 'delete']);

Route::get('/settings', [SettingController::class, 'get']);
Route::get('/settings/{id}', [SettingController::class, 'show']);
Route::post('/settings/update', [SettingController::class, 'update']);
Route::post('/settings', [SettingController::class, 'store']);
Route::post('/settings/delete', [SettingController::class, 'delete']);

Route::apiResource('properties', propertyController::class);

Route::get('getForUser/{id}', [propertyController::class, 'getForUser']);
Route::get('user-properties/{id}', [UserPropertyController::class, 'index']);
Route::post('user-properties', [UserPropertyController::class, 'store']);
Route::delete('user-properties/{id}', [UserPropertyController::class, 'destroy']);
Route::get('checkCanVoteProperties/{id}', [UserPropertyController::class, 'checkCanVoteProperties']);

Route::apiResource('movements', MovementController::class);
Route::apiResource('charges', ChargeController::class);
Route::apiResource('payments', PaymentController::class);


Route::post('/download-pdf', [PDFController::class, 'downloadPDF']);
