<?php

namespace App\Http\Controllers;

use App\Models\puesto;
use Illuminate\Http\Request;

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
        return response()->json([
            'puestos' => $puestos->paginate(10),
            'filters' => request()->all(['search', 'field', 'direction'])
        ]);
    }

    public function store(Request $request)
    {
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
        $puesto->delete();
        return response()->json([
            'message' => 'ok'
        ]);
    }
}
