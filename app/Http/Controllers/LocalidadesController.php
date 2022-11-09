<?php

namespace App\Http\Controllers;

use App\Models\DireccionMunicipio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocalidadesController extends Controller
{
    //
    public function getLocalidades (DireccionMunicipio $municipio) 
    {
        $municipios = DB::table(DB::raw('direccion_localidades'))
        ->select(DB::raw(
            '*'
        ))
        ->where('direccion_municipio_id','=', $municipio['id'])
        ->get();

        return $municipios;
    }
}
