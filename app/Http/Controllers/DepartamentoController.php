<?php

namespace App\Http\Controllers;

use App\Http\Requests\CecoRequest;
use App\Models\Ceco;
use App\Models\Cliente;
use App\Models\departamentoPuesto;
use App\Models\empleados_puesto;
use App\Models\Ubicacion;
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
        $this->authorize('departamentos.show');
        request()->validate([
            'direction' => ['in:asc,desc']
        ]);

        $departamentos = Ceco::select(
            'cecos.id AS id',
            'cecos.nombre AS nombre',
            'cecos.ubicacione_id',
            'cecos.cliente_id',
            'cecos.activo_erp',
            DB::raw('COUNT(users.id) AS personal')
        )
            ->leftjoin('empleados_puestos', function ($join) {
                $join->on('cecos.id', '=', 'empleados_puestos.departamento_id')
                    ->on('empleados_puestos.activo', '=', DB::raw(1));
            })
            ->leftjoin('users', function ($join) {
                $join->on('empleados_puestos.empleado_id', '=', 'users.id')
                    ->on('users.activo', '=', DB::raw(1));
            })
            ->groupby('cecos.id');

        $ubicaciones = Ubicacion::select('ubicaciones.id', 'ubicaciones.name');
        $clientes = Cliente::select('clientes.id', 'clientes.nombre');
        if (request('search')) {
            $departamentos->orWhere('nombre', 'LIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $departamentos->orderBy(request('field'), request('direction'));
        }
        return Inertia::render('RH/Departamentos/Departamento.Index', [
            'departamentos' => fn ()  => $departamentos->paginate(50),
            'filters' => fn ()  => request()->all(['search', 'field', 'direction']),
            'ubicaciones' => fn () => $ubicaciones->get(),
            'clientes' => fn () => $clientes->get(),
        ]);
    }

    public function update(CecoRequest $request, Ceco $departamento)
    {
        $validated = $request->validated();
        $departamento->update($validated);
        return redirect()->back();
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
