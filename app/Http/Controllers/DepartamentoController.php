<?php

namespace App\Http\Controllers;

use App\Models\Ceco;
use App\Models\departamentoPuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Database\QueryException;

class DepartamentoController extends Controller
{
    //
    public function listPuestoDep (Ceco $departamento)
    {
     $puestos = DB::table(DB::raw('departamento_puestos'))
     ->select(DB::raw(
         'puestos.name'
     ))
     ->join('puestos','departamento_puestos.puesto_id','puestos.id')
     ->where('departamento_id','=', $departamento['id'])
     ->get();

     return $puestos;
    }

    public function index () 
    {
        request()->validate([
            'direction' => ['in:asc,desc']
        ]);

        $departamentos = Ceco::select('id','nombre');
        if( request('search'))
        {
            $departamentos->orWhere('nombre', 'LIKE', '%'.request('search').'%');
        }

        if(request()->has(['field', 'direction'])) {
            $departamentos->orderBy(request('field'), request('direction'));
        }
        return Inertia::render('RH/Departamentos/Departamento.Index',[
            'departamentos' => $departamentos->paginate(10),
            'filters' => request()->all(['search', 'field','direction'])
        ]);
    }

    public function puestosIndex(Ceco $departamento)
    {
        $departamentos_puesto = DB::table(DB::raw('departamento_puestos'))
        ->select(DB::raw(
            'departamento_id,
             puesto_id'
        ))
        ->where('departamento_id','=', $departamento['id'])
        ->get();

        return $departamentos_puesto;
    }

    public function puestosUpdate(Ceco $departamento)
    {
        request()->validate([
            'checked' => ['required','boolean'],
            'puesto_id' => ['required', 'exists:puestos,id']
        ]);
        try{
            DB::beginTransaction();
            if(request('checked')){
                $departamento->puestos()->attach(request('puesto_id'));

            }else{
                $departamento->puestos()->detach(request('puesto_id'));
            }
            DB::commit();
            return "ok";
        }catch(QueryException $e)
        {
            DB::rollback();
            return response()->json($e->getMessage(), 500);
        }
    }
}
