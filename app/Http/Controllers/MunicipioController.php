<?php

namespace App\Http\Controllers;

use App\Models\DireccionEstado;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class MunicipioController extends Controller
{
    //
    public function getMunicipiosEstado(DireccionEstado $estado)
    {
        
        $municipios = DB::table(DB::raw('direccion_municipios'))
        ->select(DB::raw(
            '*'
        ))
        ->where('direccion_estado_id','=', $estado['id'])
        ->get();

        return $municipios;
    }
}
