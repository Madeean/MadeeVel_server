<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\LogoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('admin')->group(function(){
    
    Route::post('/login',LoginController::class,['as'=>'admin']);

    Route::group(['middleware' => 'auth:api'],function(){
        Route::get('/user',function(Request $request){
            return $request->user();
        })->name('user');
        Route::post('/logout',LogoutController::class,['as'=>'admin']);
        Route::get('/dashboard',DashboardController::class,['as'=>'admin']);

        Route::apiResource('/categories',CategoryController::class,['except' => ['create','edit'], 'as' => 'admin']);
    });

});