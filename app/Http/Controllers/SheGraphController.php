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

class SheGraphController extends Controller
{
    public function sheIndex()
    {
        return Inertia::render('SHE/SheDash');
    }

    public function getSitios()
    {
        return response()->json(SitiosShe::all());
    }

    public function getAnalistas()
    {
        return response()->json(TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', 2)
            ->get());
    }

    public function getSeafty()
    {
        return response()->json(TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', 3)
            ->get());
    }

    public function getTableSitio(Request $request)
    {
        $data = DataShe::select(
            'ss.name as tipo',
            DB::raw('sum(data_shes.value) as cantidad'),
            'año_mes as date'
        )
            ->join('tablas_campos_shes as tcs', 'tcs.id', 'data_shes.tabla_campo_id')
            ->join('sitios_shes as ss', 'ss.id', 'tcs.sitio_id')
            ->groupBy(['ss.id', 'año_mes'])
            ->get();
        $tipo = SitiosShe::all();
        return response()->json([
            'data' => $data,
            'series' => $tipo,
        ]);
    }

    public function getTableSitioLine(Request $request)
    {
        $data = DataShe::select(
            'cs.name as tipo',
            DB::raw('sum(data_shes.value) as cantidad'),
            'año_mes as date'
        )
            ->join('tablas_campos_shes as tcs', 'tcs.id', 'data_shes.tabla_campo_id')
            ->join('campos_shes as cs', 'cs.id', 'tcs.campo_id')
            ->where('tcs.sitio_id', $request->sitio)
            ->groupBy(['cs.id', 'año_mes'])
            ->orderBy('año_mes')
            ->get();

        $series = TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', 1)
            ->groupBy('cs.name')
            ->get();

        return response()->json([
            'data' => $data,
            'series' => $series,
            'sitio' => SitiosShe::where('id', $request->sitio)->first(),
        ]);
    }

    public function getTableAnalista(Request $request)
    {

        $data = DataShe::select(
            'cs.name as tipo',
            DB::raw('sum(data_shes.value) as cantidad'),
            'año_mes as date'
        )
            ->join('tablas_campos_shes as tcs', 'tcs.id', 'data_shes.tabla_campo_id')
            ->join('campos_shes as cs', 'cs.id', 'tcs.campo_id')
            ->where('tabla_id', 2)
            ->groupBy(['cs.id', 'año_mes'])
            ->get();


        $series = TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', 2)
            ->get();

        return response()->json([
            'data' => $data,
            'series' => $series,
        ]);
    }

    public function getTableSeafty(Request $request)
    {

        $data = DataShe::select(
            'cs.name as tipo',
            DB::raw('sum(data_shes.value) as cantidad'),
            'año_mes as date'
        )
            ->join('tablas_campos_shes as tcs', 'tcs.id', 'data_shes.tabla_campo_id')
            ->join('campos_shes as cs', 'cs.id', 'tcs.campo_id')
            ->where('tabla_id', 3)
            ->groupBy(['cs.id', 'año_mes'])
            ->get();

        $series = TablasCamposShe::select(
            'cs.name'
        )
            ->join('campos_shes as cs', 'cs.id', 'tablas_campos_shes.campo_id')
            ->where('tabla_id', 3)
            ->get();

        return response()->json([
            'data' => $data,
            'series' => $series,
        ]);
    }

    public function addSitiosData(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date']
        ]);

        try {
            $sitios = SitiosShe::all();
            DB::beginTransaction();
            foreach ($sitios as $sitio) {
                $data = (object)$request[$sitio->name];
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where([['sitio_id', $sitio->id], ['campo_id', 1]])->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->ecologia ?? 0,
                ]);
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where([['sitio_id', $sitio->id], ['campo_id', 2]])->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->salud ?? 0,
                ]);
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where([['sitio_id', $sitio->id], ['campo_id', 3]])->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->seguridad ?? 0,
                ]);
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where([['sitio_id', $sitio->id], ['campo_id', 4]])->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->documental ?? 0,
                ]);
            }
            DB::commit();
            event(new SheGraph('sitio'));
        } catch (Exception $e) {
            DB::rollBack();

            throw ValidationException::withMessages([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function addAnalistaData(Request $request)
    {
        $analistas = CamposShe::select('campos_shes.*')
            ->join('tablas_campos_shes as tcs', 'tcs.campo_id', 'campos_shes.id')
            ->where('tcs.tabla_id', 2)
            ->get();

        $request->validate([
            'date' => ['required', 'date']
        ]);

        try {
            DB::beginTransaction();
            foreach ($analistas as $analista) {
                $data = (object)$request[$analista->name];
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where('campo_id', $analista->id)->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->porcentaje ?? 0,
                ]);
            }
            DB::commit();
            event(new SheGraph('analista'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function addSeaftyData(Request $request)
    {
        $seaftys = CamposShe::select('campos_shes.*')
            ->join('tablas_campos_shes as tcs', 'tcs.campo_id', 'campos_shes.id')
            ->where('tcs.tabla_id', 3)
            ->get();

        $request->validate([
            'date' => ['required', 'date']
        ]);

        try {
            DB::beginTransaction();
            foreach ($seaftys as $seafty) {
                $data = (object)$request[$seafty->name];
                DataShe::updateOrCreate([
                    'tabla_campo_id' => TablasCamposShe::where('campo_id', $seafty->id)->first()->id,
                    'año_mes' => "{$request->date}-02",
                ], [
                    'value' => $data->porcentaje ?? 0,
                ]);
            }
            DB::commit();
            event(new SheGraph('seafty'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => $e->getMessage(),
            ]);
        }
    }
}