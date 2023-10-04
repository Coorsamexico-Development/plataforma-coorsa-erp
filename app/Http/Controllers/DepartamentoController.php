<?php

namespace App\Http\Controllers;

use App\Http\Requests\CecoRequest;
use App\Models\Area;
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
        $puestos = $departamento->puestos()
            ->where('departamento_puestos.activo', 1)
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
            ->leftJoin('departamento_puestos as dp', 'dp.departamento_id', 'cecos.id')
            ->leftjoin('empleados_puestos', function ($join) {
                $join->on('dp.id', '=', 'empleados_puestos.dpto_puesto_id')
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
        $departamentoPuestos = $departamento->puestos()->select('puestos.id', 'departamento_puestos.plantilla_auth')->where('departamento_puestos.activo', 1)->get();
        foreach ($departamentoPuestos as $dp) {
            $emp = empleados_puesto::select(
                'puesto_id',
                DB::raw('COUNT(empleado_id) as plantillaAct')
            )
                ->join('users', 'users.id', 'empleado_id')
                ->leftJoin('departamento_puestos as dp', 'dp.id', 'empleados_puestos.dpto_puesto_id')
                ->groupBy('dp.puesto_id')
                ->where([['dp.puesto_id', $dp->id], ['dp.departamento_id', $departamento->id], ['users.activo', 1], ['dp.activo', 1]])
                ->first();
            if ($dp->plantilla_auth != null)
                $plantilla[$dp->id] = $dp->plantilla_auth;
            else
                $plantilla[$dp->id] = 0;

            if ($emp)
                $empleados[$dp->id] = $emp->plantillaAct;
            else
                $empleados[$dp->id] = 0;
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
        $area = Area::select(
            'areas.nombre',
            'puestos.name as puesto',
            DB::raw('CONCAT(cecos.nombre, " - ", cecos.descripcion) as ceco'),
        )
            ->join('departamento_puestos as dp', 'dp.areas_id', 'areas.id')
            ->join('cecos', 'cecos.id', 'dp.departamento_id')
            ->join('puestos', 'puestos.id', 'dp.puesto_id')
            ->leftJoin('padres_hijos as ph', 'ph.departamento_puestos_id_padre', 'dp.id')
            ->leftJoin('padres_hijos as hp', 'hp.departamento_puestos_id_padre', 'dp.id')
            ->where([['dp.departamento_id', $departamento->id], ['dp.puesto_id', request('puesto_id')], ['dp.activo', 1], ['ph.activo', 1], ['hp.activo', 1]]);
        try {
            if (request('checked')) {
                $dp = departamentoPuesto::where([['departamento_id', $departamento->id], ['puesto_id', request('puesto_id')], ['activo', 0]]);
                if ($dp->exists()) {
                    $dp = $dp->first();
                    $dp->update(['activo' => 1]);
                } else {
                    DB::beginTransaction();
                    $departamento->puestos()->attach(request('puesto_id'));
                    DB::commit();
                }
            } else if ($area->exists()) return response()->json($area->first());
            else {
                $dp = departamentoPuesto::where([['departamento_id', $departamento->id], ['puesto_id', request('puesto_id')], ['activo', 1]])->first();
                $dp->update(['activo' => 0]);
                /* $departamento->puestos()->detach(request('puesto_id')); */
            }
            return "ok";
        } catch (QueryException $e) {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }

    public function cambiosPuestos()
    {
        $dps = departamentoPuesto::select()->get();
        foreach ($dps as $dp) {
            $emPus = empleados_puesto::where([['departamento_id', $dp->departamento_id], ['puesto_id', $dp->puesto_id]])->get();
            foreach ($emPus as $emPu) $emPu->update(['dpto_puesto_id' => $dp->id]);
        }
    }
}