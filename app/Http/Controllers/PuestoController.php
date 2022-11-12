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

        $puestos = puesto::select('id','name','activo');
        if( request('search')){
            $puestos->orWhere('name', 'LIKE', '%'.request('search').'%');
        }

        if(request()->has(['field', 'direction'])) {
            $puestos->orderBy(request('field'), request('direction'));
        }
        return response()->json([
            'puestos' => $puestos->paginate(10),
            'filters' => request()->all(['search', 'field','direction'])
        ]);
    }
}
