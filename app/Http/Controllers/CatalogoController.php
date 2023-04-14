<?php

namespace App\Http\Controllers;

use App\Models\DireccionEstado;

class CatalogoController extends Controller
{
    //
    public function getEstados()
    {
        $direccionEstado = DireccionEstado::select('id', 'nombre', 'activo')->where('activo', 1)->get();

        return response()->json([
            'estadosDireccion' => $direccionEstado
        ]);
    }
}
