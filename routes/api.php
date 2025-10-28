<?php

use Illuminate\Http\Request;

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

// Public API endpoints para Nuxt Frontend
Route::prefix('data')->group(function () {
    Route::get('/requerimientos', 'Api\DataController@requerimientos');
    Route::get('/productos', 'Api\DataController@productos');
    Route::get('/reportes', 'Api\DataController@reportes');
    Route::get('/usuarios', 'Api\DataController@usuarios');
});
