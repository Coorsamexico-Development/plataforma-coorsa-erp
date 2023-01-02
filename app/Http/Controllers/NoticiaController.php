<?php

namespace App\Http\Controllers;

use App\Models\noticia;
use App\Models\noticiaDescr;
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
            $newNoticia = noticia::create([
             'titulo' => $request['titulo'],
             'image' => $urlNoticia,
             'empleado_id' => $request['autor']
           ]);

           if($request->has('contenidoImg') &&  $request['contenidoImg'] != null)
           {
              $contenido = request('contenidoImg');
              $nombreCont = $contenido->getClientOriginalName();
              $ruta_contenido = $contenido ->storeAs('noticias/contenidos', $nombreCont, 'gcs'); 
              $urlContenido = Storage::disk('gcs')->url($ruta_contenido);


               
              noticiaDescr::create([
               'descripcion' => $request['descripcion'],
               'noticia_id' => $newNoticia->id,
               'image' => $urlContenido,
              ]);
           }
          
           if($request->has('contenidoVideo') &&  $request['contenidoVideo'] != null)
           {
               noticiaDescr::create([
                'descripcion' => $request['descripcion'],
                'image' => $request['contenidoVideo'],
                'noticia_id' => $newNoticia->id
               ]);
           }
        }
        return  redirect()->back();
    }
}
