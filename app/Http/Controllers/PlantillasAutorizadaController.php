<?php

namespace App\Http\Controllers;

use App\Models\PlantillasAutorizada;
use App\Models\puesto;
use App\Models\Ubicacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
                        ->leftJoin('empleados_puestos', function ($join) {
                            $join->on('plantillas_autorizadas.puesto_id', '=', 'empleados_puestos.puesto_id')
                                ->on('empleados_puestos.activo', '=', DB::raw(1));
                        })
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

    public function store(Request $request)
    {
        $validated =  $request->validate([
            'puesto_id' =>  ['required', 'exists:puestos,id'],
            'ubicacione_id' => ['required', 'exists:ubicaciones,id'],
            'cantidad' => ['required', 'numeric', 'min:0'],
        ]);
        //me aseguro de no doplicar registros en el mismo puesto y ubicacion
        $plantillasAutorizada =  PlantillasAutorizada::updateOrCreate([
            'puesto_id' =>   $validated['puesto_id'],
            'ubicacione_id' =>  $validated['ubicacione_id'],
        ], [
            'cantidad' =>   $validated['cantidad'],
        ]);
        return response()->json($plantillasAutorizada);
    }


    public function update(Request $request, PlantillasAutorizada $plantillasAutorizada)
    {
        $validated =  $request->validate([
            'cantidad' => ['required', 'numeric', 'min:0'],
        ]);

        $plantillasAutorizada->update($validated);
        return response()->json([
            'message' => 'updated'
        ]);
    }
}
