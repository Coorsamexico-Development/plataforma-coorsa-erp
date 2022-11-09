<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartamentoController extends Controller
{
    //
    public function listPuestoDep (Ceco $departamento)
    {
     $puestos = DB::table(DB::raw('departamento_puestos'))
     ->select(DB::raw(
         'puestos.name'
     ))
     ->join('puestos','departamento_puestos.puesto_id','puestos.id')
     ->where('departamento_id','=', $departamento['id'])
     ->get();

     return $puestos;
    }
}
