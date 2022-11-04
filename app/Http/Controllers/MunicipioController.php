<?php

namespace App\Http\Controllers;

use App\Models\DireccionEstado;
use Illuminate\Http\Request;
use Exception;

class MunicipioController extends Controller
{
    //
    public function getMunicipiosEstado(DireccionEstado $estado){

        try{

        return $estado->municipios()->select('id', 'nombre')->where('activo', 1)->get();
        }catch(Exception $e){
            return response()->json($e->getMessage(),500);
        }


    }
}
