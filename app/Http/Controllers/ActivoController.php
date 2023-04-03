<?php

namespace App\Http\Controllers;

use App\Models\activos_empleado;
use App\Models\activosItem;
use App\Models\empleados_puesto;
use App\Models\EvidenciaEntrega;
use App\Models\evidenciasActivo;
use App\Models\EvientregaActivoempleado;
use App\Models\Fila;
use App\Models\tipoActivoCampo;
use App\Models\tipoEvidencia;
use App\Models\TipoInput;
use App\Models\tipos_activo;
use App\Models\valorCampoActivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ActivoController extends Controller
{
    //
    public function index(Request $request)
    {
        //traemos todos los tipos de activos
        $tipo_activos = tipos_activo::select('tipos_activos.*')
        ->with([
         'activos_items' => function($query) use ($request)
         {
            $query->select(
              'activos_items.*',
            )
            //->where('valor_campo_activos.valor','LIKE','%Q3%')
            ->with([
             'activos_empleados'  => function ($query1)  
             {
                  $query1->select(
                   'activos_empleados.*',
                   'users.numero_empleado',
                   'users.name',
                   'users.apellido_paterno',
                   'users.apellido_materno',
                   'users.email',
                   'users.fotografia',
                   'users.profile_photo_path',
                   'puestos.name AS puesto',
                   'cecos.nombre AS departamento',
                   'ubicaciones.name AS ubicacion'
                   )
                 ->leftjoin('users','activos_empleados.empleado_id', 'users.id')
                 ->leftjoin('empleados_puestos','empleados_puestos.empleado_id','users.id')
                 ->leftjoin('puestos','empleados_puestos.puesto_id','puestos.id')
                 ->leftjoin('cecos','empleados_puestos.departamento_id','cecos.id')
                 ->leftJoin('ubicaciones','cecos.ubicacione_id','ubicaciones.id')
                 ->where('activos_empleados.status','=',1);
                 ;
             },
             'valor_campos_activos' =>function ($query2)
              {
                 $query2->select('valor_campo_activos.*')
                 ->leftjoin('tipo_activo_campos','valor_campo_activos.tipo_activo_campo_id','tipo_activo_campos.id')
                 ->leftjoin('filas', 'valor_campo_activos.fila_id','filas.id');
              },
             'evidencias_activo'])
             ->leftjoin('valor_campo_activos','valor_campo_activos.activo_id','activos_items.id')
             //->where('valor_campo_activos.valor','LIKE','%Q3%')
             ->groupBy('activos_items.id');

             //$query->where('valor_campo_activos.valor','LIKE','%Q3%');
          
             if ($request->has("search"))
             {
               $search = strtr($request->search, array("'" => "\\'", "%" => "\\%"));
               $query->where("valor_campo_activos.valor", "LIKE", "%" . $search . "%");
             }
             
         } 
         ]);

        $tipo_inputs = TipoInput::all();
       
        $tipo_evidencias = tipoEvidencia::all();

        return Inertia::render('Activos/ActivosIndex',
        [
           'tipo_activos' => fn () => $tipo_activos->get(),
           'tipo_inputs' => $tipo_inputs,
           'tipo_evidencias' =>$tipo_evidencias,
           'filters' => fn () => request()->all(['search']),
        ]);
    }

    public function valorCampo ($id)
    {
        //Labels edit campos
       // return tipoActivoCampo
    }

    public function activos( Request $request, $idTipo)
    {
        $activos_item = activosItem::query()
       ->with([
               'activos_empleados'  => function ($query) 
               {
                   return   $query->select(
                     'activos_empleados.*',
                     'users.numero_empleado',
                     'users.name',
                     'users.apellido_paterno',
                     'users.apellido_materno',
                     'users.email',
                     'users.fotografia',
                     'users.profile_photo_path',
                     'puestos.name AS puesto',
                     'cecos.nombre AS departamento',
                     'ubicaciones.name AS ubicacion'
                     )
                   ->join('users','activos_empleados.empleado_id', 'users.id')
                   ->join('empleados_puestos','empleados_puestos.empleado_id','users.id')
                   ->leftjoin('puestos','empleados_puestos.puesto_id','puestos.id')
                   ->leftjoin('cecos','empleados_puestos.departamento_id','cecos.id')
                   ->leftJoin('ubicaciones','cecos.ubicacione_id','ubicaciones.id')
                   ->where('activos_empleados.status','=',1);
                   ;
               },'valor_campos_activos',
               'evidencias_activo'])
       ->where('tipo_activo','=', $idTipo)
       ->get();

       return $activos_item;
    }

    public function storeCategory(Request $request)
    {

      $request->validate([
        'nombre' => 'required',
        'imagen' => 'required'
       ]);

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
           // return $request['arregloCampos'];
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


    public function storeItem (Request $request) 
    {
      
       
     activosItem::create([
          'tipo_activo' => $request['tipo_activo'],
          'fecha' => $request['fecha'],
          'status' => $request['status'],
          'status_activo_id' => $request['status_activo_id']
        ]);



        return  redirect()->back(); 
    }

    public function storeTipoEvidencia (Request $request)
    {
      
        tipoEvidencia::create(
          [
            'nombre' => $request['nombre'],
            'fecha' => $request['fecha']
          ] 
        );
        return  redirect()->back(); 
    }

    public function getTipoEvidencia ()
    {
       return [];
    }
  
    public function EvidenciaActivoUser (Request $request)
    {
      
       $activos_empleado = activos_empleado::updateOrCreate([
          'empleado_id' => $request['empleado_id'], 
          'activo_id' => $request['activo_item_id'],
          'status' => $request['activo'],
          'fecha' => $request['fechaAlta']
       ]);

       activosItem::where('id','=', $request['activo_item_id'])
       ->update(['status_activo_id' =>1]);
       
       if($request['imagenes'] !== null)
       {
          for ($i=0; $i < count($request['imagenes']) ; $i++)
          { 
             $imagen = $request['imagenes'][$i];
             $nombre_original = $imagen->getClientOriginalName();
             $ruta_foto = $imagen->storeAs('evidencias/fotos', $nombre_original, 'gcs'); //guardamos el archivo en el storage
             $urlFoto = Storage::disk('gcs')->url($ruta_foto);
  
            EvientregaActivoempleado::updateOrCreate([
             'activo_empleado_id' => $activos_empleado->id,
             'foto' =>  $urlFoto
           ]);
             
          }
       }
       else
       {
        
        EvientregaActivoempleado::updateOrCreate([
          'activo_empleado_id' => $activos_empleado->id,
        ]);
       }
    }

    public function getImages ($id, $activo_id)
    {
        return activos_empleado::select('evientrega_activoempleados.foto')
        ->join('evientrega_activoempleados','evientrega_activoempleados.activo_empleado_id','activos_empleados.id')
        ->join('activos_items','activos_items.id','activos_empleados.activo_id')
        ->where('activos_empleados.empleado_id','=', $id)
        ->where('activos_items.id','=', $activo_id)
        ->get();
    }

    public function getInfoEmpleado($id)
    {
       return empleados_puesto::select(
        'puestos.name AS puesto', 
       'cecos.nombre AS departamento')
       ->join('puestos', 'empleados_puestos.puesto_id', 'puestos.id','ubicaciones.name AS ubicacion')
       ->join('cecos', 'empleados_puestos.departamento_id','cecos.id')
       ->leftjoin('ubicaciones', 'cecos.ubicacione_id','ubicaciones.id')
       ->where('empleado_id','=',$id)
       ->get();
    }


    public function changeStatusActivoEmpleado($id, $activo_id)
    {
       activos_empleado::where('empleado_id',$id)
       ->where('activo_id',$activo_id)
       ->update(['status' => false]);

       
    }

    public function changeStatusActivoItem($id)
    {
       activosItem::where('id',$id)
       ->update([
          'status' => false,
          'status_activo_id' =>3
       ]);
    }

    public function getAllCampos($id)
    {
         $tipoActivoCampos = tipoActivoCampo::select(
          'valor_campo_activos.valor AS valor',
          'valor_campo_activos.tipo_activo_campo_id  AS tipoCampoValor',

          'tipo_activo_campos.id AS tipo_activo_campo_id',


          'tipo_activo_campos.campo AS labelName',
          'tipo_inputs.nombre AS tipo_input',
        )
        ->leftJoin('valor_campo_activos', 'valor_campo_activos.tipo_activo_campo_id','tipo_activo_campos.id')
        ->join('tipo_inputs', 'tipo_activo_campos.tipo_input_id', 'tipo_inputs.id')
        ->where('activo_id','=',$id)
        ->get();


        $tipoEvidencias = tipoEvidencia::all();

         return [$tipoActivoCampos, $tipoEvidencias];
           /*valorCampoActivo::select(
          'valor_campo_activos.valor AS valor',
          'valor_campo_activos.tipo_activo_campo_id  AS tipoCampoValor',

          'tipo_activo_campos.id AS tipo_activo_campo_id',


          'tipo_activo_campos.campo AS labelName',
          'tipo_inputs.nombre AS tipo_input',
          )
        ->leftjoin('tipo_activo_campos', 'valor_campo_activos.tipo_activo_campo_id','tipo_activo_campos.id')
        ->leftjoin('tipo_inputs', 'tipo_activo_campos.tipo_input_id', 'tipo_inputs.id')
        ->where('activo_id','=',$id)
        ->get();*/
    }

    public function saveEditCampos(Request $request, $id)
    {
      $request->validate([
        'activo_id' => 'required',
        'tipo_activo_campo_id' => 'required',
       ]);

      $busquedaPor = ['activo_id' => request('activo_id'),
       'tipo_activo_campo_id' => request('tipo_activo_campo_id'),
       'fila_id' => request('fila_id')];
       
      if(request()->has('file'))  //falta funcionalidad para guardar la imagen como tal y no el temp
      {
           $file = $request['file'];
           $nombre_original = $file->getClientOriginalName();
           $ruta_file = $file->storeAs('activos/archivos', $nombre_original, 'gcs'); //guardamos el archivo en el storage
           $urlFile = Storage::disk('gcs')->url($ruta_file);

          valorCampoActivo::updateOrCreate(
            $busquedaPor,
            ['valor' =>  $urlFile],
        );
      }
      else //si es tipo texto o numero
      {   
          valorCampoActivo::updateOrCreate(
            $busquedaPor,
            ['valor' =>  request('valor')],
        );
      }
      return  redirect()->back(); 
    }

    public function changeStatusActivoItemLibre ($id)
    {
      activosItem::where('id',$id)
      ->update([
         'status' => true,
         'status_activo_id' => 2
      ]);
    }

    public function saveEvidencias (Request $request)
    {
      $request->validate([
        'nombre' => 'required',
       ]);

       for ($i=0; $i < count($request['imagenes']) ; $i++) 
       { 
            $imagen = $request['imagenes'][$i];
            $nombre_original = $imagen->getClientOriginalName();
            $ruta_foto = $imagen->storeAs('evidencias/evidenciasItems', $nombre_original, 'gcs'); //guardamos el archivo en el storage
            $urlFoto = Storage::disk('gcs')->url($ruta_foto);

             evidenciasActivo::create([
             'nombre' => $request['nombre'],
             'imagen' => $urlFoto,
             'tipo_evidencia_id' => $request['tipoEvidencia'], 
             'user_id' => $request['usuario'],
             'activo_id' => $request['activo_id']
            ]);
       }
    }

    public function storeColum(Request $request)
    {
       tipoActivoCampo::create([
        'tipo_activo_id' => request('tipo_activo_id'), 
        'tipo_input_id' => request('tipo_input_id'),
        'tabla_id' => request('tabla_id'),
        'principal' => request('principal'),
        'campo' =>  request('campo')
       ]);

       return  redirect()->back(); 
      
    }

    public function updateColum(Request $request)
    {
 
       tipoActivoCampo::where('id','=',$request['id'])
       ->update([
        'tipo_activo_id' => request('tipo_activo_id'), 
        'tipo_input_id' => request('tipo_input_id'),
        'tabla_id' => request('tabla_id'),
        'principal' => request('principal'),
        'campo' =>  request('campo')
       ]);
      
       return  redirect()->back(); 
    }

    public function getCampos($idCampo, $idTipoActivo)
    {
       
     return tipoActivoCampo::select(
        'tipo_activo_campos.id AS id',
        'tipo_activo_campos.campo AS campo',
        'tipo_inputs.id AS input_id',
        'tipo_inputs.nombre',
      )
      ->join('tipo_inputs','tipo_activo_campos.tipo_input_id','tipo_inputs.id')
      ->where('tipo_activo_campos.tabla_id','=', $idCampo)
      ->where('tipo_activo_campos.tipo_activo_id', '=', $idTipoActivo)
      ->get();
    }

    public function columasCampos ($campo_id, $idActivo)
    {
      
       tipoActivoCampo::select( //columnas con el tipo de input que es
          'valor_campo_activos.activo_id AS idActivo',
          'tipo_activo_campos.id AS campoId',
          'tipo_activo_campos.campo AS campo',
          'tipo_inputs.nombre AS tipo_input',
          'valor_campo_activos.valor AS valor',
          'valor_campo_activos.fila_id'
        )
        ->leftjoin('tipo_inputs','tipo_activo_campos.tipo_input_id','tipo_inputs.id')
        ->leftjoin('valor_campo_activos', function ($join) use($idActivo) {
          $join->on('valor_campo_activos.tipo_activo_campo_id','tipo_activo_campos.id')
          ->on('valor_campo_activos.activo_id','=', DB::raw($idActivo));
        })
        ->leftjoin('tipos_activos','tipo_activo_campos.tipo_activo_id','tipos_activos.id')
        ->where('tipo_activo_campos.tabla_id','=', $campo_id)
        // ->where('valor_campo_activos.activo_id','=', $idActivo)
        //->OrWhere('valor_campo_activos.activo_id','=', null)
       // ->where('tipo_activo_campos.tabla_id','=',$campo_id)
        ->get();


        /// Nueva forma -.- ///
      $columnas = tipoActivoCampo::select( //columnas
        'tipo_activo_campos.id',
        'tipo_activo_campos.campo AS campo',
        'tipo_inputs.id AS tipo_input_id',
        'tipo_inputs.nombre AS tipo_input_nombre')
        ->join('tipo_inputs', 'tipo_activo_campos.tipo_input_id','tipo_inputs.id')
        ->where('tipo_activo_campos.tabla_id','=',$campo_id)
        ->get();

      $filas = Fila::select('filas.*')
      ->where('filas.tipo_activo_campo_id','=',$campo_id)
      ->where('filas.activos_item_id','=', $idActivo)
      ->get();


       $valores = valorCampoActivo::select(  //valores
          'valor_campo_activos.valor',
          'valor_campo_activos.tipo_activo_campo_id AS columna_id',
          'valor_campo_activos.fila_id'
        )
        ->join('tipo_activo_campos','valor_campo_activos.tipo_activo_campo_id','tipo_activo_campos.id')
        ->join('tipo_inputs', 'tipo_activo_campos.tipo_input_id','tipo_inputs.id')
        ->where('tipo_activo_campos.tabla_id','=',$campo_id)
        ->where('valor_campo_activos.activo_id','=', $idActivo)
        ->get();

        return [$columnas, $filas, $valores];
    }

    public function columasCamposFila ($campo_id, $idActivo, $fila_id)
    {
              /// Nueva forma -.- ///
              $columnas = tipoActivoCampo::select( //columnas
                'tipo_activo_campos.id',
                'tipo_activo_campos.campo AS campo',
                'tipo_inputs.id AS tipo_input_id',
                'tipo_inputs.nombre AS tipo_input_nombre')
                ->join('tipo_inputs', 'tipo_activo_campos.tipo_input_id','tipo_inputs.id')
                ->where('tipo_activo_campos.tabla_id','=',$campo_id)
                ->get();
        
             $filas = Fila::select('filas.*')
              ->where('filas.tipo_activo_campo_id','=',$campo_id)
              ->where('filas.activos_item_id','=', $idActivo)
              ->where('filas.fila_id','=', $fila_id)
              ->get();
        
        
               $valores = valorCampoActivo::select(  //valores
                  'valor_campo_activos.valor',
                  'valor_campo_activos.tipo_activo_campo_id AS columna_id',
                  'valor_campo_activos.fila_id'
                )
                ->join('tipo_activo_campos','tipo_activo_campos.id','valor_campo_activos.tipo_activo_campo_id')
                ->where('tipo_activo_campos.tabla_id','=',$campo_id)
                ->where('valor_campo_activos.activo_id','=', $idActivo)
                ->get();
        
                return [$columnas, $filas, $valores];
    }

    public function saveNewValorColum(Request $request)
    {    
       $request->validate([
          'valor' => 'required',
          'tipo_activo_campo_id' => 'required',
          'activo_id' => 'required'      
       ]);

       valorCampoActivo::create([
         'valor' => $request['valor'],
         'tipo_activo_campo_id' => $request['tipo_activo_campo_id'],
         'activo_id' => $request['activo_id']
       ]);

       return  redirect()->back(); 
    }

    public function newFila (Request $request)
    {

      if($request['fila_id'])
      {
        Fila::create([
          'activos_item_id' => $request['activos_items_id'],
          'tipo_activo_campo_id' => $request['tipo_activo_campo_id'],
           'fila_id'=> $request['fila_id'],
         ]);
      }
      else
      {
        Fila::create([
          'activos_item_id' => $request['activos_items_id'],
         'tipo_activo_campo_id' => $request['tipo_activo_campo_id'],
 
         ]);
      }
    }
}

 