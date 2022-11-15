<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    //
    public function store(Request $request)
    {
        $fechaActual = date('Y-m-d H:i:s');
        $descripcion = $request['descripcion'];
        $autor = $request['autor'];
        $activo = $request['activo'];
        DB::insert(
            'insert into menus
           ( descripcion, empleado_id, activo, created_at, updated_at )
           values (?, ?, ?, ?, ?)',
            [$descripcion, $autor, $activo, $fechaActual, $fechaActual]
        );
        return  redirect()->back();
    }
}
