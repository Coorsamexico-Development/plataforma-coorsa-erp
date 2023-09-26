<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use App\Models\departamentoPuesto;
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

    public function searchType($dpto, $puesto, $empleado)
    {
        $dpto = Ceco::where('id', $dpto)->first();
        $puesto = puesto::where('id', $puesto)->first();
        $empleados = User::select('users.*', DB::raw('CONCAT(users.name," ",users.apellido_paterno," ", users.apellido_materno) as fullname'))
            ->join('empleados_puestos as ep', 'ep.empleado_id', 'users.id')
            ->where([['ep.puesto_id', $puesto->id], ['ep.departamento_id', $dpto->id], ['users.activo', 1], ['users.name', 'like', '%' . $empleado . '%']])
            ->orderBy('fullname', 'asc');
        return response()->json($empleados->paginate(20));
    }

    public function update(Request $request)
    {
        $dpto = Ceco::where('id', $request->departamento)->first();
        $puesto = puesto::where('id', $request->puesto)->first();
        $dptoPuest = departamentoPuesto::where([['departamento_id', $dpto->id], ['puesto_id', $puesto->id]])->first();
        $dptoPuest->update([
            'plantilla_auth' => $request->plantilla,
        ]);
        return response()->json($dptoPuest);
    }
}
