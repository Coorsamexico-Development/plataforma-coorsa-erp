<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticiaController extends Controller
{
    //
    public function store(Request $request)
    {
        $fechaActual = date('Y-m-d H:i:s');
        if($request->has('imagen'))
        {
           $img = $request['imagen'];
           $nombre =  $img->getClientOriginalName();
           $img->storeAs('public/noticias', $nombre); //guardamos el archivo en el storage
           $datosMenu =request()->except('imagen'); //recuperamos todo excepto el archivo
           $autor = $datosMenu['autor'];
           $activo = $datosMenu['activo'];
           DB::insert('insert into noticias 
           (image, empleado_id, activo, created_at, updated_at ) 
           values (?, ?, ?, ?, ?)',
           [$nombre, $autor,$activo,$fechaActual, $fechaActual]);
           return  redirect()->back();
        }
    }
}
