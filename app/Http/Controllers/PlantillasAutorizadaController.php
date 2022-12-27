<?php

namespace App\Http\Controllers;

use App\Models\PlantillasAutorizada;
use App\Models\puesto;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlantillasAutorizadaController extends Controller
{
    public function index()
    {
        request()->validate([
            'direction' => ['in:asc,desc']
        ]);



        //Get catologos
        $puestos = puesto::select('puestos.id', 'puestos.name')
            ->with([
                'plantillasAutorizadas' => function ($query) {
                    $query->select('plantillas_autorizadas.*')
                        ->selectRaw('count(empleados_puestos.id) as cantidad_activa')
                        ->join('empleados_puestos', 'plantillas_autorizadas.puesto_id', '=', 'empleados_puestos.puesto_id')
                        ->where('empleados_puestos.activo', '=', 1)
                        ->groupBy('plantillas_autorizadas.id');
                }
            ])
            ->orderBy('puestos.name', 'asc');
        $ubicaciones = Ubicacion::select('ubicaciones.id', 'ubicaciones.name')
            ->orderBy('ubicaciones.name', 'asc');

        return Inertia::render('PlantillasAutorizadas/PlantillasAutorizadasIndex', [
            'puestos' => fn () => $puestos->get(),
            'ubicaciones' => fn () => $ubicaciones->get(),
        ]);
    }
}
