<?php

use App\Http\Controllers\CatalogoController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DepartamentosAuditoriaController;
use App\Http\Controllers\DocsPoliticasController;
use App\Http\Controllers\DocumentosCalificacionMesController;
use App\Http\Controllers\EmpleadoControlller;
use App\Http\Controllers\LocalidadesController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\PlantillasAutorizadaController;
use App\Http\Controllers\PoliticController;
use App\Http\Controllers\PuestoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VideoController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('inicio')->middleware('guest');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        $fechaActual = date('Y-m-d');

        $noticias = DB::table(DB::raw('noticias'))
            ->selectRaw('*')
            ->where('noticias.activo', '=', '1')
            ->get();


        $videos = DB::table(DB::raw('videos'))
            ->selectRaw('*')
            ->where('videos.activo', '=', '1')
            ->get();

        $menus = DB::table(DB::raw('menus'))
            ->selectRaw(
                '*'
            )
            ->where('menus.created_at', 'LIKE', '%' . $fechaActual . '%')
            ->get();
        return Inertia::render(
            'Dashboard',
            [
                'menus' => $menus,
                'noticias' => $noticias,
                'videos' => $videos
            ]
        );
    })->name('dashboard');


    Route::get('/control-interno/departamentos-aditorias', [DepartamentosAuditoriaController::class, 'index'])
        ->name('control-interno.departamentos-aditorias.index');

    Route::post('departamentos-aditorias/{departamentosAuditoria}/calificacion', [DepartamentosAuditoriaController::class, 'storeCalificacion'])
        ->name('departamentos-aditorias.calificacion.store')->middleware('can:calificacion.create');
    Route::delete('documentos-calificacion-mes/{documentosCalificacionMes}', [DocumentosCalificacionMesController::class, 'destroy'])
        ->name('documentos-calificacion-mes.destroy')->middleware('can:calificacion.delete');

    Route::apiResource('politics', PoliticController::class)->name('index', 'control-interno.politics.index');

    Route::get('users/list', [UserController::class, 'list'])->name('users.list');
    Route::apiResource('roles', RoleController::class)->middleware('can:roles.manager');
    Route::get('role/{role}/permissions', [RoleController::class, 'permissions'])->name('roles.permissions');
    Route::put('role/{role}/permissions', [RoleController::class, 'setPermission'])->name('roles.permissions');

    Route::apiResource('/menu', MenuController::class);
    Route::apiResource('/noticia', NoticiaController::class);
    Route::apiResource('/video', VideoController::class);

    Route::get('plantillas-autorizadas', [PlantillasAutorizadaController::class, 'index'])->name('rh.plantillas-autorizadas.index');
});

Route::get('empleados/create', [EmpleadoControlller::class, 'createNewEmpleado'])->name('empleado.create');

Route::apiResource('/DocsPoliticas', DocsPoliticasController::class);
Route::get('empleados/{activo}', [EmpleadoControlller::class, 'index'])->name('empleado.indexmanual');
Route::post('empleados/store', [EmpleadoControlller::class, 'store'])->name('empleado.store');
Route::get('/catalogos/formulario/empelado', [CatalogoController::class, 'formularioEmpleado'])->name('catalogos.formularioEmpleado'); //ruta para los diferentes catalogos
Route::get('/municipio/{estado}', [MunicipioController::class, 'getMunicipiosEstado'])->name('municipos.estado');
Route::get('/localidades/{municipio}', [LocalidadesController::class, 'getLocalidades'])->name('localidades.municipio');
Route::get('/departamentos/{departamento}/list-puestos', [DepartamentoController::class, 'listPuestoDep'])->name('departamento.puestos.list');
Route::get('/empleados/edit/{id}', [EmpleadoControlller::class, 'edit'])->name('empleado.edit');
Route::post('empleados/update', [EmpleadoControlller::class, 'update'])->name('empleado.update');
Route::get('departamentos', [DepartamentoController::class, 'index'])->name('dptos.index');
Route::apiResource('/puestos', PuestoController::class);
Route::get('/departamentos/{departamento}/puestos', [DepartamentoController::class, 'puestosIndex'])->name('departamento.puestos.index');
Route::put('/departamentos/{departamento}/puesto', [DepartamentoController::class, 'puestosUpdate'])->name('departamento.puestos.update');

Route::get('users/export/{activo}', [UserController::class, 'export'])->name('export.empleados');

Route::get('card/user', [UserController::class, 'viewCard'])->name('view.card');
