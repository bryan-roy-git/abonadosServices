<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('abonado', App\Http\Controllers\api\AbonadoController::class)->except(['update']);
Route::post('/abonado/update', [App\Http\Controllers\api\AbonadoController::class,'update'])->name('abonado.update');

Route::get('/abonado/searchAbonados/{term}', [App\Http\Controllers\api\AbonadoController::class,'search'])->name('abonado.search');
// Route::get('foto/{path}', [App\Http\Controllers\api\AbonadoController::class,'getFoto'])->name('abonado.foto');

Route::get('tarifas', [App\Http\Controllers\api\TarifaController::class,'index']);






