<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use App\Models\puesto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PuestoEmpleadoController extends Controller
{
    public function search($dpto, $puesto)
    {
        $dpto = Ceco::where('id', $dpto)->first();
        $puesto = puesto::where('id', $puesto)->first();
        $empleados = User::select('users.*', DB::raw('CONCAT(users.name," ",users.apellido_paterno," ", users.apellido_materno) as fullname'))
            ->join('empleados_puestos as ep', 'ep.empleado_id', 'users.id')
            ->where([['ep.puesto_id', $puesto->id], ['ep.departamento_id', $dpto->id], ['users.activo', 1]])
            ->orderBy('fullname', 'asc');
        return response()->json($empleados->paginate(20));
    }
}
