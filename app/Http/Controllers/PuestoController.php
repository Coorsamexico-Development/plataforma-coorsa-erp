<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use App\Models\empleados_puesto;
use App\Models\puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class PuestoController extends Controller
{
    //
    public function index()
    {
        request()->validate([
            'direction' => ['in:asc,desc']
        ]);

        $puestos = puesto::select('id', 'name', 'activo');
        if (request('search')) {
            $puestos->orWhere('name', 'LIKE', '%' . request('search') . '%');
        }

        if (request()->has(['field', 'direction'])) {
            $puestos->orderBy(request('field'), request('direction'));
        } else if (request()->has('ceco')) {
            $ceco = request('ceco');
            $ceco = Ceco::where('id', $ceco['id'])->first();
            $puestos = puesto::select('puestos.id', 'puestos.name', 'puestos.activo')
                ->join('departamento_puestos as dePu', 'dePu.puesto_id', 'puestos.id')
                ->where([['dePu.departamento_id', $ceco->id], ['dePu.activo', 1]])
                ->orderBy('activo', 'desc')
                ->orderBy('puestos.name', 'asc');
        } else
            $puestos->orderBy('activo', 'desc')->orderBy('name', 'asc');

        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);
        return response()->json([
            'puestos' => $puestos->paginate(20),
            'filters' => request()->all(['search', 'field', 'direction']),
            'nominas' => $nominas
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('puestos.create');
        $newPuesto = $request->validate([
            'name' => ['required', 'max:60', 'unique:puestos,name'],
        ]);

        puesto::create($newPuesto);

        return redirect()->back();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, puesto $puesto)
    {

        $this->authorize('puestos.update');
        $request->validate([
            'name' => [
                'required', 'max:60',
                'unique:puestos,name,' . $puesto->id
            ]
        ]);
        $puesto->name = $request->name;
        $puesto->activo = $request->activo;
        $puesto->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(puesto $puesto)
    {
        $this->authorize('puestos.delete');

        $empleado = empleados_puesto::select('users.*', DB::raw('CONCAT(users.name," ",users.apellido_paterno," ", users.apellido_materno) as fullname'))
            ->join('users', 'users.id', 'empleados_puestos.empleado_id')
            ->join('departamento_puestos as dp', 'dp.id', 'empleados_puestos.dpto_puesto_id')
            ->where([['puesto_id', $puesto->id], ['empleados_puestos.activo', 1], ['dp.activo', 1]]);
        if (!$empleado->exists()) {
            puesto::where('id', $puesto->id)->delete();
            return response()->json(['empleado' => 'succes']);
        } else {
            return response()->json(['empleado' => $empleado->get()]);
        }
    }
}
