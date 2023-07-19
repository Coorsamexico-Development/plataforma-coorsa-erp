<?php

namespace App\Http\Controllers;

use App\Models\puesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        }
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
    public function destroy(Puesto $puesto)
    {
        dd('hola');
        $this->authorize('puestos.delete');
        $puesto->delete();
        return response()->json([
            'message' => 'ok'
        ]);
    }
}
