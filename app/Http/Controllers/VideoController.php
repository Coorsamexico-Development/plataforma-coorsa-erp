<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VideoController extends Controller
{
    
    public function store(Request $request)
    {
        $fechaActual = date('Y-m-d H:i:s');
        if($request->has('video'))
        {
           $img = $request['video'];
           $nombre =  $img->getClientOriginalName();
           $img->storeAs('public/videos', $nombre); //guardamos el archivo en el storage
           $datosMenu =request()->except('video'); //recuperamos todo excepto el archivo
           $autor = $datosMenu['autor'];
           $activo = $datosMenu['activo'];
           DB::insert('insert into videos 
           (image, empleado_id, activo, created_at, updated_at ) 
           values (?, ?, ?, ?, ?)',
           [$nombre, $autor,$activo,$fechaActual, $fechaActual]);
           return  redirect()->back();
        }
    }
    
}
