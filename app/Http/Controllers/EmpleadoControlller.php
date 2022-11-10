<?php

namespace App\Http\Controllers;

use App\Models\Banco;
use App\Models\catEstadosCiviles;
use App\Models\catTipoSangre;
use App\Models\Ceco;
use App\Models\Escolaridad;
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
        $newEmpleado =  $request->validated();
        try {
            DB::beginTransaction();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
