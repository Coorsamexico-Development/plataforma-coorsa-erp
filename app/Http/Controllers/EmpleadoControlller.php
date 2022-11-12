<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\catEstadosCiviles;
use App\Models\catTipoSangre;
use App\Models\Ceco;
use App\Models\direccione;
use App\Models\empleados_puesto;
use App\Models\Escolaridad;
use App\Models\expediente;
use App\Models\puesto;
use App\Models\tipoContrato;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

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

         return Inertia::render('RH/Empleados/Create/CreateIndex',
         [
            'escolaridades' => $escolaridades,
            'estados_civiles' => $estado_civiles,
            'cat_tipo_sangre' => $tipos_sangre,
            'bancos' => $bancos,
            'departamentos' => $departamentos,
            'tipos_contrato' => $tipos_contrato
         ]);
    }


    public function store (Request $request)
    {
        $newEmpleado =  $request;

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
            'password' => '12345678'
         ]); //creamos el usuario
         

         $puesto_id = puesto::select('id')->where('name','LIKE','%'.$newEmpleado['puesto_id'].'%')->get();

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

         $escolaridades = Escolaridad::all();
         $estado_civiles = catEstadosCiviles::all();
         $tipos_sangre = catTipoSangre::all();
         $bancos = Banco::all(); 
         $departamentos = Ceco::all();
         $tipos_contrato = tipoContrato::select('id', 'nombre','activo')->where('activo',1 )->get();

         return Inertia::render('RH/Empleados/Create/Edit.Index',
         [
            'empleado' => $empleado,
            'escolaridades' => $escolaridades,
            'estados_civiles' => $estado_civiles,
            'cat_tipo_sangre' => $tipos_sangre,
            'bancos' => $bancos,
            'departamentos' => $departamentos,
            'tipos_contrato' => $tipos_contrato
         ]);
        
    }

    
    

    public function storeDocumentos (Request $request, User $empleado)
    {

        if($empleado->hasFile('fotografia'))
        {
            $path  = $request->file('fotografia')->storeAs('expedientes/'. $empleado->curp,
            'fotografia.'. $request->file('fotografia')->extension(), 'public'
            );
            $empleado->fotografia = asset('storage/'.$path);
            $empleado->save();
        }
        if($request->hasFile('expediente')){
            $path  = $request->file('expediente')->storeAs('expedientes/'. $empleado->curp,
            'expediente.'. $request->file('expediente')->extension(), 'public'
            );

            expediente::updateOrCreate(
                ['cat_tipos_documento_id' => 25,// Expediente General
                'empleado_id' => $empleado->id],
                ['ruta'  => asset('storage/'.$path)]);
        }
        if($request->hasFile('contrato')){
            $path  = $request->file('contrato')->storeAs('expedientes/'. $empleado->curp,
            'contrato.'. $request->file('contrato')->extension(), 'public'
            );
            expediente::updateOrCreate(
                ['cat_tipos_documento_id' => 26, // Contrato
                'empleado_id' => $empleado->id],
                ['ruta'  => asset('storage/'.$path)]);
        }
    }
}
