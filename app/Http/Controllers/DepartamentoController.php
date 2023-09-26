<?php

namespace App\Http\Controllers;

use App\Http\Requests\CecoRequest;
use App\Models\Ceco;
use App\Models\Cliente;
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
        $puestos = $departamento->puestos()
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
            'cecos.*',
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
            ->groupby('cecos.id')
            ->where('cecos.activo_erp', 1);

        $ubicaciones = Ubicacion::select('ubicaciones.id', 'ubicaciones.name');
        $clientes = Cliente::select('clientes.id', 'clientes.nombre');
        if (request('search')) {
            $departamentos->where('nombre', 'LIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $departamentos->orderBy(request('field'), request('direction'));
        } else
            $departamentos->orderBy('cecos.nombre', 'asc');
        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        return Inertia::render('RH/Departamentos/Departamento.Index', [
            'departamentos' => fn ()  => $departamentos->paginate(50),
            'filters' => fn ()  => request()->all(['search', 'field', 'direction']),
            'ubicaciones' => fn () => $ubicaciones->get(),
            'clientes' => fn () => $clientes->get(),
            'nominas' => $nominas,
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
        $plantilla = [];
        $empleados = [];
        $departamentoPuestos = $departamento->puestos()->select('puestos.id', 'departamento_puestos.plantilla_auth')->get();
        foreach ($departamentoPuestos as $dp) {
            $emp = empleados_puesto::select(
                'puesto_id',
                DB::raw('COUNT(empleado_id) as plantillaAct')
            )
                ->join('users', 'users.id', 'empleado_id')
                ->groupBy('puesto_id')
                ->where([['puesto_id', $dp->id], ['departamento_id', $departamento->id], ['users.activo', 1]])
                ->first();
            $plantilla[$dp->id] = $dp->plantilla_auth;
            $empleados[$dp->id] = $emp->plantillaAct;
        }
        return response()->json([
            'dptoPues' => $departamentoPuestos->pluck('id'),
            'plantilla' => $plantilla,
            'empleados' => $empleados,
        ]);
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