<?php

namespace App\Http\Controllers;

use App\Events\SheGraph;
use App\Models\CamposShe;
use Exception;
use Inertia\Inertia;
use App\Models\DataShe;
use App\Models\SitiosShe;
use App\Models\TablasShe;
use Illuminate\Http\Request;
use App\Models\TablasCamposShe;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\Switch_;

class SheGraphController extends Controller
{
    public function sheIndex()
    {
        return Inertia::render('SHE/SheDash');
    }

    public function getTableCDUQ(Request $request)
    {
        $data = DataShe::select(
            'cs.name as tipo',
            DB::raw('sum(data_shes.value) as cantidad'),
            'año_mes as date'
        )
            ->join('tablas_campos_shes as tcs', 'tcs.id', 'data_shes.tabla_campo_id')
            ->join('campos_shes as cs', 'cs.id', 'tcs.campo_id')
            ->where('tabla_id', $request->table)
            ->groupBy(['cs.id', 'año_mes'])
            ->get();

        $series =
            TablasCamposShe::select(
                'cs.name'
            )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', $request->table)
            ->get();
        return response()->json([
            'data' => $data,
            'series' => $series,
        ]);
    }

    public function getSeries(Request $request)
    {
        return response()->json(TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', $request->table)
            ->get());
    }

    public function addSeriesSitios(Request $request): void
    {
        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 1]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Diciplina'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 2]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Incidentes no Incapacitantes'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 3]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Incidentes Incapacitantes'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 4]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Siniestros'],
        ]);

        event(new SheGraph($request->channel));
    }

    public function addCapacitacion(Request $request): void
    {
        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 5]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['CDU'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 6]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Procter'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 7]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['WLM'],
        ]);

        event(new SheGraph($request->channel));
    }

    public function addSeafty(Request $request): void
    {
        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 8]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Capacitacion'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 9]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Recorridos'],
        ]);

        DataShe::updateOrCreate([
            'tabla_campo_id' => TablasCamposShe::where([['tabla_id', $request->table], ['campo_id', 10]])->first()->id,
            'año_mes' => "{$request->date}-02",
        ], [
            'value' => $request['Matriz repeticion de eventos'],
        ]);

        event(new SheGraph($request->channel));
    }
}