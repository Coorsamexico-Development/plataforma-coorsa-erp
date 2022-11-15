<?php

namespace App\Http\Controllers;

use App\Models\noticia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NoticiaController extends Controller
{
    //
    public function store(Request $request)
    {
        //$fechaActual = date('Y-m-d H:i:s');
        if($request->has('imagen'))
        {
           $noticia = request('imagen');
           $nombre =  $noticia->getClientOriginalName();//rescatamos el nombre original
           $ruta_noticia = $noticia->storeAs('noticias', $nombre, 'gcs'); //guardamos el archivo en el storage
           $urlNoticia = Storage::disk('gcs')->url($ruta_noticia);

           //Creacion de noticia
            noticia::create([
             'image' => $urlNoticia,
             'empleado_id' => $request['autor']
           ]);
        }
        return  redirect()->back();
    }
}
