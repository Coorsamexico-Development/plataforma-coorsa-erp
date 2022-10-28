<?php

namespace App\Http\Controllers;

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
     
        if($activo === 'activo'){
            $empleados = $empleados->where('activo', 1)->get();
        }
        else if($activo === 'inactivo'){
            $empleados = $empleados->where('activo', 0)->get();
        }


        if( request('search'))
        {
               $empleados->where(function ($query){
                $query->orWhere('numero_empleado', 'LIKE', '%'.request('search').'%');
                $query->orWhere('name', 'LIKE', '%'.request('search').'%');
                $query->orWhere('apellido_paterno', 'LIKE', '%'.request('search').'%');
                $query->orWhere('apellido_materno', 'LIKE', '%'.request('search').'%');
                $query->orWhere('telefono', 'LIKE', '%'.request('search').'%');
            });

        }


        return Inertia::render('RH/Empleados/EmpleadosIndex',
        [
            'empleados' => $empleados,
            'activo' => $activo,
            'filters' => request()->all(['search'])
        ]);
    }

}
