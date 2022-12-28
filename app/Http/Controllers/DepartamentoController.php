<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use App\Models\departamentoPuesto;
use App\Models\empleados_puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Database\QueryException;

class DepartamentoController extends Controller
{
    //
    public function listPuestoDep(Ceco $departamento)
    {
        $puestos = DB::table(DB::raw('departamento_puestos'))
            ->select(DB::raw(
                'puestos.id,puestos.name'
            ))
            ->join('puestos', 'departamento_puestos.puesto_id', 'puestos.id')
            ->where('departamento_id', '=', $departamento['id'])
            ->get();

        return $puestos;
    }

    public function index()
    {
        request()->validate([
            'direction' => ['in:asc,desc']
        ]);

        $departamentos = Ceco::select(
            'cecos.id AS id',
            'cecos.nombre AS nombre',
            DB::raw('COUNT(empleados_puestos.empleado_id) AS personal')
        )
            ->leftjoin('departamento_puestos', 'cecos.id', 'departamento_puestos.id')
            ->leftjoin('puestos', 'departamento_puestos.puesto_id', 'puestos.id')
            ->leftjoin('empleados_puestos', 'puestos.id', 'empleados_puestos.puesto_id')
            ->leftjoin('users', 'empleados_puestos.empleado_id', 'users.id')
            ->groupby('cecos.id');


        if (request('search')) {
            $departamentos->orWhere('nombre', 'LIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $departamentos->orderBy(request('field'), request('direction'));
        }
        return Inertia::render('RH/Departamentos/Departamento.Index', [
            'departamentos' => $departamentos->paginate(50),
            'filters' => request()->all(['search', 'field', 'direction'])
        ]);
    }

    public function puestosIndex(Ceco $departamento)
    {
        $departamentoPuestos = $departamento->puestos()->select('puestos.id')->get();

        return $departamentoPuestos->pluck('id');
    }

    public function puestosUpdate(Ceco $departamento)
    {
        request()->validate([
            'checked' => ['required', 'boolean'],
            'puesto_id' => ['required', 'exists:puestos,id']
        ]);
        try {
            DB::beginTransaction();
            if (request('checked')) {
                $departamento->puestos()->attach(request('puesto_id'));
            } else {
                $departamento->puestos()->detach(request('puesto_id'));
            }
            DB::commit();
            return "ok";
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
}
