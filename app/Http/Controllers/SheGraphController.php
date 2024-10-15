<?php

namespace App\Http\Controllers;

use App\Models\DataShe;
use App\Models\SitiosShe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SheGraphController extends Controller
{
    public function sheIndex()
    {
        return Inertia::render('SHE/SheDash');
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
            ->groupBy('ss.id')
            ->get();
        $tipo = SitiosShe::all();
        return response()->json([
            'data' => $data,
            'series' => $tipo,
        ]);
    }
}