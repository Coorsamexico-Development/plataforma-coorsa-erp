<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\NominasController;
use App\Http\Controllers\FiniquitoController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\CronsController;
use App\Http\Controllers\EmpleadoController as ControllersEmpleadoController;
use App\Http\Controllers\PoliticController;
use App\Http\Controllers\VariablesNominaController;
use App\Http\Controllers\SolicitudVacacionsController;
use App\Http\Controllers\UserController;

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

//Rutas ChatBot
Route::controller(ChatBotController::class)->group(function () {
    //Gets
    Route::get('/webhook', 'chatBot')->name('chatBot');
    //Posts
    Route::post('/webhook', 'chatBotData')->name('chatBotData');
});

//Ruta Finiquitos
Route::controller(FiniquitoController::class)->group(function () {
    //Post
    Route::post('getIsrCalculado', 'getIsrCalculado')->name('getIsrCalculado');
});

//Ruta Nominas
Route::controller(NominasController::class)->group(function () {
    Route::post('getNominasByUser', 'getNominasByUser')->name('getNominasByUser');
});

//Rutas vacaciones
Route::controller(SolicitudVacacionsController::class)->group(function () {
    Route::post('getDataCalendarVacaciones', 'getDataCalendarVacaciones')->name('getDataCalendarVacaciones');
    Route::post('reporteVacaciones', 'reporteVacaciones')->name('reporteVacaciones');
});

//Crons
Route::controller(CronsController::class)->group(function () {
    Route::post('actualizarVacaciones', 'actualizarVacaciones')->name('actualizarVacaciones');
    Route::post('addVacaciones', 'addVacaciones')->name('addVacaciones');
});

/* Rutas de usuarios */
Route::controller(UserController::class)->group(function () {
    Route::post('getUsers', 'getUsers')->name('getUsers');
    Route::post('getMeails', 'getMeails')->name('getMeails');
    Route::post('sendMail', 'sendMail')->name('sendMail');
});

/* Rutas nueva plataforma */
Route::controller(PoliticController::class)->group(function () {
    Route::post('getPolitics', 'getPolitics');
});

Route::controller(EmpleadoController::class)->group(function () {
    Route::post('empleados/pruebas', 'pruebaPass');
});

/*  */