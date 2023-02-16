<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    CountryController,
    NewsDataController,
    NewsSourceController,
};


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/source', [NewsSourceController::class, 'index']);
Route::get('/countries', [CountryController::class, 'index']);
Route::get('/countries/{code}', [CountryController::class, 'show']);
Route::post('/countries/{code}/categories/{categoryName}', [CountryController::class, 'addCategory']);
Route::delete('/countries/{code}/categories/{categoryName}', [CountryController::class, 'removeCategory']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::post('/news', [NewsDataController::class, 'index']);
    Route::get('/country-news/{countryCode}/{page?}', [NewsDataController::class, 'countryNewsData']);
});
