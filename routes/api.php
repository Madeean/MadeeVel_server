<?php

use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\LoginController;
use App\Http\Controllers\Api\Admin\LogoutController;
use App\Http\Controllers\Api\Admin\PlaceController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Web\CategoryController as WebCategoryController;
use App\Http\Controllers\Api\Web\PlaceController as WebPlaceController;
use App\Http\Controllers\Api\Web\SliderController as WebSliderController;
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
        Route::apiResource('/places',PlaceController::class,['except' => ['create','edit'], 'as' => 'admin']);
        Route::apiResource('/sliders',SliderController::class,['except' => ['create','edit','show','update'], 'as' => 'admin']);
        Route::apiResource('/users',UserController::class,['except' => ['create','edit'], 'as' => 'admin']);
    });


    
});
Route::prefix('web')->group(function(){
    Route::get('/categories',[WebCategoryController::class,'index',['as'=>'web']]);
    Route::get('/categories/{slug?}',[WebCategoryController::class,'show',['as'=>'web']]);

    Route::get('/places',[WebPlaceController::class,'index',['as'=>'web']]);
    Route::get('/places/{slug?}',[WebPlaceController::class,'show',['as'=>'web']]);
    Route::get('/all_places',[WebPlaceController::class,'all_places',['as'=>'web']]);
    
    Route::get('/sliders',[WebSliderController::class,'index',['as'=>'web']]);
});