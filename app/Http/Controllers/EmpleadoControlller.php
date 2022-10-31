<?php

namespace App\Http\Controllers;

use App\Models\Escolaridad;
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
         return Inertia::render('RH/Empleados/Create/CreateIndex',
         [
            'escolaridades' => $escolaridades
         ]);
    }
}
