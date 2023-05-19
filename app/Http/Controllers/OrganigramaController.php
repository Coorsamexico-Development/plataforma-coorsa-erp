<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Areas_padres_hijos;
use App\Models\departamentoPuesto;
use App\Models\Padres_hijos;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrganigramaController extends Controller
{
    public function index()
    {

        $rels = false;
        $areaRel = false;
        $nodes[] = DB::table('departamento_puestos as DP')
            ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
            ->join('puestos as P', 'P.id', 'DP.puesto_id')
            ->select(
                'DP.id as id',
                'P.name as Puesto',
                'Ce.Nombre as Ceco',
            )
            ->where('DP.areas_id', null)
            ->orWhere('DP.areas_id', 1)
            ->get();

        $areas = Area::all();
        foreach ($areas as $area) {
            $nodes[] = DB::table('departamento_puestos as DP')
                ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                ->join('puestos as P', 'P.id', 'DP.puesto_id')
                ->select(
                    'DP.id as id',
                    'P.name as Puesto',
                    'Ce.Nombre as Ceco',
                )
                ->where('DP.areas_id', $area->id)
                ->get();
        }

        foreach ($nodes as $nodo) {
            /* Obtenemos las relaciones Padres->Hijos */
            $rela = null;
            foreach ($nodo as $n) {
                $rel = Padres_hijos::where([['departamento_puestos_id_padre', $n->id], ['activo', 1]])->get();
                foreach ($rel as $r) {
                    /* Informacion del Padre */
                    $a = DB::table('departamento_puestos as DP')
                        ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                        ->join('puestos as P', 'P.id', 'DP.puesto_id')
                        ->select(
                            'DP.id as id',
                            'P.name as Puesto',
                            'Ce.Nombre as Ceco',
                        )
                        ->where('DP.id', $r->departamento_puestos_id_padre)
                        ->first();

                    /* Informacion del Hijo */
                    $b = DB::table('departamento_puestos as DP')
                        ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                        ->join('puestos as P', 'P.id', 'DP.puesto_id')
                        ->select(
                            'DP.id as id',
                            'P.name as Puesto',
                            'Ce.Nombre as Ceco',
                        )
                        ->where('DP.id', $r->departamento_puestos_id_hijo)
                        ->first();

                    /* Establecemos las relaciones con el nombre de los nodos */
                    $rela[] = [
                        'nodoA' => $a->Puesto . '/' . $a->Ceco,
                        'nodoB' => $b->Puesto . '/' . $b->Ceco,
                    ];
                }
            }
            $rels[] = $rela;
        }

        $areas = Area::where('id', '<>', 1)->get();
        $padre = null;

        foreach ($areas as $n) {
            $rel = Areas_padres_hijos::where([['areas_id_padre', $n->id], ['activo', 1]])->get();

            $DP = DB::table('departamento_puestos as DP')
                ->where([['DP.areas_id', $n->id]])
                ->get();
            foreach ($rel as $r) {
                /* Informacion del Padre */
                $a = Area::where('id', $r->areas_id_padre)
                    ->first();

                /* Informacion del Hijo */
                $b = Area::where('id', $r->areas_id_hijo)
                    ->first();
                foreach ($DP as $p) {
                    $rel = Padres_hijos::where([['departamento_puestos_id_hijo', $p->id], ['activo', 2]])
                        ->get();
                    if ($rel->count() != 0)
                        $padre = $p;
                }

                /* Establecemos las relaciones con el nombre de los nodos */
                $areaRel[] = [
                    'nodoA' => $a->nombre,
                    'nodoB' => $b->nombre,
                    'idA' => $a->id,
                    'idB' => $b->id,
                ];
            }
        }

        /* Recuperamos los recibos de nomina del empleado */
        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        /* Pasamos la vista junto con los parametros requeridos para el funcionamiento */
        return Inertia::render('Organigrama/Organigrama', [
            'nominas' => $nominas,
            'nodes' => $nodes,
            'rels' => $rels,
            'areas' => $areas,
            'areaRel' => $areaRel,
        ]);
    }

    public function relacion(Request $request)
    {
        /* Definimos nuestros nodos con los que vamos a trabajar */
        $nodoA = $request->nodoA;
        $nodoB = $request->nodoB;
        $nodoC = $request->nododC; /* Nodo de recuperacion */
        $nodoD = $request->nodoD; /* Nodo de relcion anterior */


        /* Revisamos si alguno de nuestros nodos Padre->Hijo estan vacios de ser así el nodoC tendra la infomacion que requerimos para ubtener el nodo faltante */
        if ($nodoA === null) {
            $nodoF = explode('/', $request->nodoC['from']);
            $nodoA = get_object_vars(DB::table('departamento_puestos as DP')
                ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                ->join('puestos as P', 'P.id', 'DP.puesto_id')
                ->select(
                    'DP.id as Nodeid',
                    'P.name as Puesto',
                    'Ce.Nombre as Ceco',
                )
                ->where([['P.name', $nodoF[0]], ['Ce.nombre', $nodoF[1]]])
                ->first());
        } elseif ($nodoB === null) {
            $nodoF = explode('/', $request->nodoC['from']);
            $nodoB = get_object_vars(DB::table('departamento_puestos as DP')
                ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                ->join('puestos as P', 'P.id', 'DP.puesto_id')
                ->select(
                    'DP.id as Nodeid',
                    'P.name as Puesto',
                    'Ce.Nombre as Ceco',
                )
                ->where([['P.name', $nodoF[0]], ['Ce.nombre', $nodoF[1]]])
                ->first());
        }

        /* Una vez tenemos los nodos Padre->Hijo revisamos que no exista un registro previo */
        $relAft = Padres_hijos::where([['departamento_puestos_id_padre', $nodoA['Nodeid']], ['departamento_puestos_id_hijo', $nodoB['Nodeid']]])->first();

        if ($nodoD != null) {
            if ($nodoD['Nodeid'] != $nodoB['Nodeid']) {
                $relBef = Padres_hijos::where([['departamento_puestos_id_padre', $nodoA['Nodeid']], ['departamento_puestos_id_hijo', $nodoD['Nodeid']]])->first();

                if ($relAft === null) {
                    /* Como no existe un registro previo entonces lo creamos y desactivamos la relacion actual*/
                    $relBef->update([
                        'activo' => 0
                    ]);
                    Padres_hijos::create([
                        'departamento_puestos_id_padre' => $nodoA['Nodeid'],
                        'departamento_puestos_id_hijo' => $nodoB['Nodeid'],
                        'activo' => 1,
                    ]);
                } else {
                    /* En caso de existir debemos saber si esta activo o no */
                    if ($relAft->Activo === 0) {
                        $relAft->update([
                            'activo' => 1
                        ]);
                        $relBef->update([
                            'activo' => 0
                        ]);
                    } else {
                        /* Si esta activa estan tratando de tener la misma relaacion dos veces así que solo desactivamos la relacion aactual */
                        $relBef->update([
                            'activo' => 0
                        ]);
                    }
                }
            } else {
                if ($relAft === null) {
                    /* Como no existe un registro previo entonces lo creamos y desactivamos la relacion actual*/
                    Padres_hijos::create([
                        'departamento_puestos_id_padre' => $nodoA['Nodeid'],
                        'departamento_puestos_id_hijo' => $nodoB['Nodeid'],
                        'activo' => 1,
                    ]);
                } else {
                    /* En caso de existir debemos saber si esta activo o no */
                    if ($relAft->Activo === 0) {
                        $relAft->update([
                            'activo' => 1
                        ]);
                    }
                }
            }
        } else {
            Padres_hijos::create([
                'departamento_puestos_id_padre' => $nodoA['Nodeid'],
                'departamento_puestos_id_hijo' => $nodoB['Nodeid'],
                'activo' => 1,
            ]);
        }
        return redirect()->back();
    }

    /* Daamos de baja las relaciones */
    public function destroy(Request $request)
    {
        /* Definimos nuestros nodos con los que vamos a trabajar */
        $nodoA = $request->nodoA;
        $nodoB = $request->nodoB;
        $nodoC = $request->nododC; /* Nodo de recuperacion */
        $nodoD = $request->nodoD; /* Nodo de relcion anterior */
        $area = $request->area;

        if ($area === null) {
            if ($nodoA === null) {
                $nodoF = explode('/', $request->nodoC['from']);
                $nodoA = get_object_vars(DB::table('departamento_puestos as DP')
                    ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                    ->join('puestos as P', 'P.id', 'DP.puesto_id')
                    ->select(
                        'DP.id as Nodeid',
                        'P.name as Puesto',
                        'Ce.Nombre as Ceco',
                    )
                    ->where([['P.name', $nodoF[0]], ['Ce.nombre', $nodoF[1]]])
                    ->first());

                $relBef = Padres_hijos::where([['departamento_puestos_id_padre', $nodoA['Nodeid']], ['departamento_puestos_id_hijo', $nodoD['Nodeid']]])->first();

                $relBef->update([
                    'activo' => 0
                ]);
            }
        } else {
            $padre = null;
            $DP = DB::table('departamento_puestos as DP')
                ->where([['DP.areas_id', $area['idB']]])
                ->get();
            foreach ($DP as $p) {
                $rel = Padres_hijos::where([['departamento_puestos_id_hijo', $p->id], ['activo', 1]])
                    ->get();
                if ($rel->count() === 0)
                    $padre = $p;
            }
            if ($padre === null)
                return redirect()->back();

            $relBef = Padres_hijos::where([['departamento_puestos_id_padre', $nodoA['Nodeid']], ['departamento_puestos_id_hijo', $padre->id]])->first();

            if ($relBef) {
                $relBef->update([
                    'activo' => 0
                ]);
            } else {
                Padres_hijos::create([
                    'departamento_puestos_id_padre' => $nodoA['Nodeid'],
                    'departamento_puestos_id_hijo' => $padre->id,
                    'activo' => 2,
                ]);
            }
        }
        return redirect()->back();
    }

    /* Pasar a las diferentes areas */
    public function area(Request $request)
    {
        $DP = departamentoPuesto::where('id', $request->elemento['id'])->first();
        $area = Area::where('id', $request->area)->first();

        if ($DP->areas_id === $area->id) return redirect()->back();
        $DP->update([
            'areas_id' => $request->area,
        ]);

        $relaciones = Padres_hijos::where([['departamento_puestos_id_padre', $DP->id], ['activo', 1]])->orWhere([['departamento_puestos_id_hijo', $DP->id], ['activo', 1]])->get();

        if ($relaciones->count() > 0) {
            foreach ($relaciones as $r) {
                $r->update([
                    'activo' => 0,
                ]);
            }
        }

        return redirect()->back();
    }
    public function remove(Request $request)
    {
        $DP = departamentoPuesto::where('id', $request->nodoA['Nodeid'])->first();
        $DP->update(['areas_id' => 1]);

        return redirect()->back();
    }
}
