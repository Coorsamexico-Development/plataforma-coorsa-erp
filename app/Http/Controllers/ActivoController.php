<?php

namespace App\Http\Controllers;

use App\Models\activosItem;
use App\Models\tipoActivoCampo;
use App\Models\TipoInput;
use App\Models\tipos_activo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ActivoController extends Controller
{
    //

    public function index()
    {
        //traemos todos los tipos de activos
        $tipo_activos = tipos_activo::select('tipos_activos.*')
        ->get();

        $tipo_inputs = TipoInput::all();

        return Inertia::render('Activos/ActivosIndex',
        [
           'tipo_activos' => $tipo_activos,
           'tipo_inputs' => $tipo_inputs
        ]);
    }

    public function activos($idTipo)
    {
       return activosItem::select('activos_items.*')
       ->where('tipo_activo','=', $idTipo)
       ->get();
    }

    public function storeCategory(Request $request)
    {
       $ruta_icono = "";
       if($request->has('imagen'))
       {
         if($request['imagen'] != null)
         {
           $imagen = request('imagen');
           $nombre_original = $imagen->getClientOriginalName();
           $ruta_icono = $imagen->storeAs('iconos', $nombre_original, 'gcs'); //guardamos el archivo en el storage
           $urlIcono = Storage::disk('gcs')->url($ruta_icono);
         }
       }
       //return $request;
       $newTipoActivo = tipos_activo::create([
         'nombre' => $request['nombre'] ,
         'imagen' => $urlIcono
       ]);
       
       if(count($request['arregloCampos']) > 0)
       {
         for ($i=0; $i < count($request['arregloCampos']) ; $i++) //recorremos los campos para insertarlos
         { 
           $newTipoActivoCampo =  tipoActivoCampo::create([
              'tipo_activo_id' =>$newTipoActivo->id,
              'campo' => $request['arregloCampos'][$i]['nombre'],
              'principal' => $request['arregloCampos'][$i]['principal'],
              'tipo_input_id' => $request['arregloCampos'][$i]['tipo_input_id']
            ]);
            
         }
       }
   
       return  redirect()->back();
    }
}
