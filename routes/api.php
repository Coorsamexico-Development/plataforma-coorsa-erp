<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\VariablesNominaController;

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

Route::post('empleados/send-sign', [EmpleadoController::class, 'sendFirma']);
Route::get('empleados/show-firma', [EmpleadoController::class, 'showFirma']);

Route::controller(VariablesNominaController::class)->group(function () {
    Route::post('getVariablesNomina', 'getVariablesNomina')->name('getVariablesNomina');
    Route::post('actualizarSalarios', 'actualizarSalarios')->name('actualizarSalarios');
});

Route::controller(ChatBotController::class)->group(function () {
    //Gets
    /* Route::get('chatBot', 'chatBot')->name('chatBot'); */
    //Posts
    Route::post('chatBotData', 'chatBotData')->name('chatBotData');
});