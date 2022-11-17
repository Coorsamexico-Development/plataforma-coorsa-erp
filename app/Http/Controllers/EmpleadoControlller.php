<?php

namespace App\Http\Controllers;

use App\Models\bajasEmpleado;
use App\Models\Banco;
use App\Models\catEstadosCiviles;
use App\Models\catTipoSangre;
use App\Models\Ceco;
use App\Models\direccione;
use App\Models\empleados_puesto;
use App\Models\Escolaridad;
use App\Models\expediente;
use App\Models\finiquito;
use App\Models\puesto;
use App\Models\Role;
use App\Models\tipoContrato;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class EmpleadoControlller extends Controller
{
    //
    public function index($activo)
    {
        $empleados = DB::table(DB::raw('users'))
        ->selectRaw(
            'id,
            name,
            numero_empleado,
            apellido_paterno,
            apellido_materno,
            profile_photo_path,
            telefono,
            activo
                '
        );
     
        if($activo === 'activo')
        {
            $empleados = $empleados->where('activo', 1);
            if( request('search'))
            {
                 $busqueda = request('search');
                 $empleados = $empleados->where(
                     'name','LIKE','%'.$busqueda.'%'   
                    );
             }
        }
        else if($activo === 'inactivo')
        {
            $empleados = $empleados->where('activo', 0);
            if( request('search'))
            {
                $busqueda = request('search');
                $empleados = $empleados->where(
                    'name','LIKE','%'.$busqueda.'%'   
                );
            }
        }
  

         return Inertia::render('RH/Empleados/EmpleadosIndex',
         [
            'empleados' => fn() => $empleados->get(),
            'activo' => $activo,
            'filters' => request()->all(['search'])
         ]);
    }

    public function createNewEmpleado (Request $request)
    {
         $escolaridades = Escolaridad::all();
         $estado_civiles = catEstadosCiviles::all();
         $tipos_sangre = catTipoSangre::all();
         $bancos = Banco::all(); 
         $departamentos = Ceco::all();
         $tipos_contrato = tipoContrato::select('id', 'nombre','activo')->where('activo',1 )->get();
         $roles = Role::all();

         return Inertia::render('RH/Empleados/Create/CreateIndex',
         [
            'escolaridades' => $escolaridades,
            'estados_civiles' => $estado_civiles,
            'cat_tipo_sangre' => $tipos_sangre,
            'bancos' => $bancos,
            'departamentos' => $departamentos,
            'tipos_contrato' => $tipos_contrato,
            'roles' => $roles
         ]);
    }


    public function store (Request $request)
    {
           $newEmpleado =  $request;
           $ruta_fotografia = "";

           if($request->has('fotografia'))
           {
             $fotografia = request('fotografia');
             $nombre_fotografia =  $fotografia->getClientOriginalName();//rescatamos el nombre original
             $ruta_fotografia = $fotografia->storeAs('expedientes/fotografia/',$nombre_fotografia ,'gcs'); //guardamos el archivo en el storage
             $urlFotografia = Storage::disk('gcs')->url($ruta_fotografia); 
           }
           
            //creamos la direccion
            $direccion = direccione::create([
                'direccion_localidade_id' => $newEmpleado['direccion_localidade_id'],
                'calle' => $newEmpleado['calle'],
                'numero' => $newEmpleado['numero'],
                'colonia' => $newEmpleado['colonia'],
                'codigo_postal'=> $newEmpleado['codigo_postal'],
                'lote' =>$newEmpleado['lote'],
                'manzana' => $newEmpleado['manzana'],
            ]);
        
         $empleado = User::create([
            'numero_empleado' => $newEmpleado['numero_empleado'],
            'name' => $newEmpleado['nombre'],
            'apellido_paterno' => $newEmpleado['apellido_paterno'],
            'apellido_materno' => $newEmpleado['apellido_materno'],
            'email' => $newEmpleado['correo_electronico'],
            'fecha_nacimiento' => $newEmpleado['fecha_nacimiento'],
            'fecha_ingreso' => $newEmpleado['fecha_ingreso'],
            'fecha_ingreso_real' => $newEmpleado['fecha_ingreso_real'],
            'nss' => $newEmpleado['nss'],
            'curp' =>$newEmpleado['curp'],
            'rfc' =>$newEmpleado['rfc'],
            'contacto_emergencia' => $newEmpleado['contacto_emergencia'],
            'telefono' => $newEmpleado['telefono'],
            'hijos' => $newEmpleado['hijos'],
            'clave_bancaria' => $newEmpleado['clave_bancaria'],
            'numero_cuenta_bancaria' => $newEmpleado['numero_cuenta_bancaria'],
            'salario_diario' =>$newEmpleado['salario_diario'],
            'salario_bruto' => $newEmpleado['salario_bruto'],
            'salario_imss' => $newEmpleado['salario_imss'],
            'bono_puntualidad' =>$newEmpleado['bono_puntualidad'],
            'bono_asistencia' => $newEmpleado['bono_asistencia'],
            'despensa' => $newEmpleado['despensa'],
            'fondo_ahorro' => $newEmpleado['fondo_ahorro'],
            'horario' => $newEmpleado['horario'],
            'alergias' =>$newEmpleado['alergias'],
            'enfermedades_cronicas' =>$newEmpleado['enfermedades_cronicas'],
            'direccion_id' => $direccion->id,
            'estado_civil_id' => $newEmpleado['cat_estados_civile_id'],
            'banco_id' =>$newEmpleado['banco_id'],
            'escolaridad_id'=>$newEmpleado['escolaridade_id'],
            'cat_tipos_nomina_id' =>$newEmpleado['cat_tipos_nomina_id'],
            'tipos_contrato_id' => $newEmpleado['tipos_contrato_id'],
            'cat_genero_id' => $newEmpleado['cat_genero_id'],
            'cat_tipo_sangre_id' => $newEmpleado['cat_tipos_sangre_id'],
            'fotografia' => $urlFotografia,
            'password' => Hash::make($newEmpleado['password']),
            'role_id' => $newEmpleado['rol_id']
         ]); //creamos el usuario
         

         $puesto_id = puesto::select('id')->where('name','LIKE','%'.$newEmpleado['puesto_id'].'%')->get();

         if($request->has('expediente'))
         {   
            $curp = $request['curp'];
            $expediente  = $request['expediente'];
            /*Guardamos*/
            $rutaExpediente = $expediente->storeAs('expedientes/expediente/',$curp,'gcs');
            $urlExpediente = Storage::disk('gcs')->url($rutaExpediente);
 
            expediente::updateOrCreate(
             [
                 'ruta' => $urlExpediente,
                 'cat_tipos_documento_id' => 25,
                 'empleado_id' => $empleado->id
             ]
         );
 
         }
         if($request->has('contrato'))
         {
             $curp = $request['curp'];
             $contrato = $request['contrato'];
             /*Guardamos*/
             $rutaContrato = $contrato->storeAs('expedientes/contratos/',$curp,'gcs');
             $urlContrato = Storage::disk('gcs')->url($rutaContrato); 
 
             expediente::updateOrCreate(
                 [
                     'ruta' => $urlContrato,
                     'cat_tipos_documento_id' => 26,
                     'empleado_id' => $empleado->id
                 ]
                 );
         }

         //creamos el empleado_puesto
         empleados_puesto::create([
            'puesto_id' => $puesto_id[0]->id,
            'departamento_id' =>$newEmpleado['departamento_id'],
            'empleado_id' => $empleado->id,
         ]);

        // Store docuemtos
         $this->storeDocumentos($request, $empleado);

         return redirect()->back();

         
    }


    public function edit($id)
    {
         $empleado = DB::table(DB::raw('users'))
         ->selectRaw('*')
         ->where('id','=', $id)
         ->get();

         $empleado_direccion_id = $empleado[0]->direccion_id;

         $direccion = DB::table(DB::raw('direcciones'))
         ->selectRaw(
            'direccion_localidades.id AS localidad_id,
            direccion_municipios.nombre,
            direccion_municipios.id AS municipio_id,
            direccion_estados.nombre,
            direccion_estados.id AS estado_id,
            direcciones.calle AS calle,
            direcciones.numero AS numero,
            direcciones.colonia AS colonia,
            direcciones.codigo_postal AS codigo_postal,
            direcciones.manzana AS manzana,
            direcciones.lote AS lote'
            )
         ->join('users','direcciones.id','users.direccion_id')
         ->join('direccion_localidades','direcciones.direccion_localidade_id','direccion_localidades.id')
         ->join('direccion_municipios', 'direccion_localidades.direccion_municipio_id','direccion_municipios.id')
         ->join('direccion_estados', 'direccion_municipios.direccion_estado_id','direccion_estados.id')
         ->where('users.direccion_id','=', $empleado_direccion_id)
         ->get();

   

         $escolaridades = Escolaridad::all();
         $estado_civiles = catEstadosCiviles::all();
         $tipos_sangre = catTipoSangre::all();
         $bancos = Banco::all(); 
         $departamentos = Ceco::all();
         $tipos_contrato = tipoContrato::select('id', 'nombre','activo')->where('activo',1 )->get();

         return Inertia::render('RH/Empleados/Create/Edit.Index',
         [
            'direccion' => $direccion,
            'empleado' => $empleado,
            'escolaridades' => $escolaridades,
            'estados_civiles' => $estado_civiles,
            'cat_tipo_sangre' => $tipos_sangre,
            'bancos' => $bancos,
            'departamentos' => $departamentos,
            'tipos_contrato' => $tipos_contrato
         ]);
        
    }

    public function update(Request $request, User $empleado)
    {
        $urlFoto = '';
        $urlExpediente = '';
        $urlContrato = '';

     
        
        /*Guardado de imagnes, expedientes, contrato*/ 
        if($request->has('fotografia') && $request['fotografia'] !== null)
        {
           $foto =  $request['fotografia'];
           $nombre_original = $foto->getClientOriginalName();
           /*Guardamos*/ 
           $rutaFoto = $foto->storeAs('fotos',$nombre_original ,'gcs');
           $urlFoto = Storage::disk('gcs')->url($rutaFoto);
        }

        $newEmpleado =  $request;

        // Guarda nueva direccion si el campo no existe
        if(empty($request->direccion_id))
        {
             //creamos la direccion
            $direccion = direccione::create([
                'direccion_localidade_id' => $newEmpleado['direccion_localidade_id'],
                'calle' => $newEmpleado['calle'],
                'numero' => $newEmpleado['numero'],
                'colonia' => $newEmpleado['colonia'],
                'codigo_postal'=> $newEmpleado['codigo_postal'],
                'lote' =>$newEmpleado['lote'],
                'manzana' => $newEmpleado['manzana'],
            ]);
        } 
        else //sino actualizamos el existente
        {
            direccione::where('id', $newEmpleado->direccion_id)->update([
                "direccion_localidade_id" => $newEmpleado['direccion_localidade_id'],
                "calle" => $newEmpleado['calle'],
                "numero" => $newEmpleado['numero'],
                "colonia" => $newEmpleado['colonia'],
                "codigo_postal" => $newEmpleado['codigo_postal'],
                "lote" => $newEmpleado['lote'],
                "manzana" => $newEmpleado['manzana']
            ]);
        }
        //Actualizamos el usuario
        $newUser = User::where('id' ,'=' , $newEmpleado['id'])
        ->update([
            'numero_empleado' => $newEmpleado['numero_empleado'],
            'name' => $newEmpleado['nombre'],
            'apellido_paterno' => $newEmpleado['apellido_paterno'],
            'apellido_materno' => $newEmpleado['apellido_materno'],
            'email' => $newEmpleado['correo_electronico'],
            'fecha_nacimiento' => $newEmpleado['fecha_nacimiento'],
            'fecha_ingreso' => $newEmpleado['fecha_ingreso'],
            'fecha_ingreso_real' => $newEmpleado['fecha_ingreso_real'],
            'nss' => $newEmpleado['nss'],
            'curp' =>$newEmpleado['curp'],
            'rfc' =>$newEmpleado['rfc'],
            'contacto_emergencia' => $newEmpleado['contacto_emergencia'],
            'telefono' => $newEmpleado['telefono'],
            'hijos' => $newEmpleado['hijos'],
            'clave_bancaria' => $newEmpleado['clave_bancaria'],
            'numero_cuenta_bancaria' => $newEmpleado['numero_cuenta_bancaria'],
            'salario_diario' =>$newEmpleado['salario_diario'],
            'salario_bruto' => $newEmpleado['salario_bruto'],
            'salario_imss' => $newEmpleado['salario_imss'],
            'bono_puntualidad' =>$newEmpleado['bono_puntualidad'],
            'bono_asistencia' => $newEmpleado['bono_asistencia'],
            'despensa' => $newEmpleado['despensa'],
            'fondo_ahorro' => $newEmpleado['fondo_ahorro'],
            'horario' => $newEmpleado['horario'],
            'alergias' =>$newEmpleado['alergias'],
            'enfermedades_cronicas' =>$newEmpleado['enfermedades_cronicas'],
            'direccion_id' => $direccion->id,
            'estado_civil_id' => $newEmpleado['cat_estados_civile_id'],
            'banco_id' =>$newEmpleado['banco_id'],
            'escolaridad_id'=>$newEmpleado['escolaridade_id'],
            'cat_tipos_nomina_id' =>$newEmpleado['cat_tipos_nomina_id'],
            'tipos_contrato_id' => $newEmpleado['tipos_contrato_id'],
            'cat_genero_id' => $newEmpleado['cat_genero_id'],
            'cat_tipo_sangre_id' => $newEmpleado['cat_tipos_sangre_id'],
            'password' => Hash::make('12345678'),
            'fotografia' => $urlFoto ,
            'password' => Hash::make($newEmpleado['password']),
            'role_id' => $newEmpleado['rol_id']
         ]);

       // Store docuemtos

             if($request->has('expediente') && $request['expediente'] !== null)
             {   
                $curp = $request['curp'];
                $expediente  = $request['expediente'];
                /*Guardamos*/
                $rutaExpediente = $expediente->storeAs('expedientes',$curp,'gcs');
                $urlExpediente = Storage::disk('gcs')->url($rutaExpediente);
     
                expediente::updateOrCreate(
                 [
                     'ruta' => $urlExpediente,
                     'cat_tipos_documento_id' => 25,
                     'empleado_id' => $empleado->id
                 ]
             );
     
             }
             if($request->has('contrato') && $request['contrato'] !== null)
             {
                 $curp = $request['curp'];
                 $contrato = $request['contrato'];
                 /*Guardamos*/
                 $rutaContrato = $contrato->storeAs('contratos',$curp,'gcs');
                 $urlContrato = Storage::disk('gcs')->url($rutaContrato); 
     
                 expediente::updateOrCreate(
                     [
                         'ruta' => $urlContrato,
                         'cat_tipos_documento_id' => 26,
                         'empleado_id' => $empleado->id
                     ]
                     );
             }

       //Preguntamos si el usuario se dio de baja
       if(!empty($request->cat_bajas_empleado_id))
       {
           $request->validate(['fecha_baja' => 'required|after:1900-01-01']);
           bajasEmpleado::updateOrCreate(
            ['fecha_baja' => $request->fecha_baja,
            'empleado_id'=> $empleado->id],
           ['cat_bajas_empleado_id' => $request->cat_bajas_empleado_id]);
           $empleado->activo = false;
           $empleado->save();
       }
       //finiquito_pagado
       if(!empty($request->fecha_finiquito)){
        finiquito::updateOrCreate(
            ['fecha_finiquito' => $request->fecha_finiquito,'empleado_id'=> $empleado->id],
            ['monto' => $request->monto_finiquito,'pagado' => $request->finiquito_pagado]);
        }
        return redirect()->back();
    }

}
