<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Areas_padres_hijos;
use App\Models\Ceco;
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
                'Ce.descripcion as CecoName',
            )
            ->where('DP.areas_id', null)
            ->orWhere('DP.areas_id', 1)
            ->orderBy('Ceco')
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
                    'Ce.descripcion as CecoName',
                )
                ->where('DP.areas_id', $area->id)
                ->orderBy('Ceco')
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
                            'Ce.descripcion as CecoName',
                        )
                        ->where('DP.id', $r->departamento_puestos_id_padre)
                        ->orderBy('Ceco')
                        ->first();

                    /* Informacion del Hijo */
                    $b = DB::table('departamento_puestos as DP')
                        ->join('cecos as Ce', 'Ce.id', 'DP.departamento_id')
                        ->join('puestos as P', 'P.id', 'DP.puesto_id')
                        ->select(
                            'DP.id as id',
                            'P.name as Puesto',
                            'Ce.Nombre as Ceco',
                            'Ce.descripcion as CecoName',
                        )
                        ->where('DP.id', $r->departamento_puestos_id_hijo)
                        ->orderBy('Ceco')
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

        foreach ($areas as $n) {
            $rel = Areas_padres_hijos::where([['areas_id_padre', $n->id], ['activo', 1]])->get();

            foreach ($rel as $r) {
                /* Informacion del Padre */
                $a = Area::where('id', $r->areas_id_padre)
                    ->first();

                /* Informacion del Hijo */
                $b = Area::where('id', $r->areas_id_hijo)
                    ->first();
                $ph = Area::select(
                    'DP.*',
                )
                    ->join('departamento_puestos as DP', 'DP.areas_id', 'areas.id')
                    ->where([['areas.nombre', $b->nombre]])
                    ->get();
                if (sizeof($ph) != 0) {
                    foreach ($ph as $p) {
                        $h = Padres_hijos::select(
                            'padres_hijos.id',
                            'c.nombre as padre',
                            'P.name as puestoP',
                            'c2.nombre as hijo',
                            'P2.name as puestoH'
                        )
                            ->join('departamento_puestos as DP', 'DP.id', 'padres_hijos.departamento_puestos_id_padre')
                            ->join('cecos as c', 'c.id', 'DP.departamento_id')
                            ->join('puestos as P', 'P.id', 'DP.puesto_id')
                            ->join('departamento_puestos as DP2', 'DP2.id', 'padres_hijos.departamento_puestos_id_hijo')
                            ->join('cecos as c2', 'c2.id', 'DP2.departamento_id')
                            ->join('puestos as P2', 'P2.id', 'DP2.puesto_id')
                            ->where([['departamento_puestos_id_hijo', $p['id']], ['padres_hijos.activo', 2]])
                            ->first();
                        if ($h != null) {
                            $hijo = $h;
                            break;
                        } else {
                            $hijo = (object) [
                                'id' => null,
                                'padre' => null,
                                'puestoP' => null,
                                'hijo' => null,
                                'puestoH' => null
                            ];
                        }
                    }
                } else {
                    $hijo = (object) [
                        'id' => null,
                        'padre' => null,
                        'puestoP' => null,
                        'hijo' => null,
                        'puestoH' => null
                    ];
                }

                /* Establecemos las relaciones con el nombre de los nodos */
                $areaRel[] = [
                    'nodoA' => $a->nombre,
                    'nodoB' => $b->nombre,
                    'idA' => $a->id,
                    'idB' => $b->id,
                    'padre' => $hijo->padre . ' ' . $hijo->puestoP,
                    'hijo' => $hijo->hijo . ' ' . $hijo->puestoH,
                    'ph' => $hijo->id
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
        $area = $request->area;

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
            if (Padres_hijos::where([['departamento_puestos_id_padre', $nodoA['Nodeid']], ['departamento_puestos_id_hijo', $nodoB['Nodeid']]])->exists()) {
                $relAft->update([
                    'activo' => 1
                ]);
            } else {
                Padres_hijos::create([
                    'departamento_puestos_id_padre' => $nodoA['Nodeid'],
                    'departamento_puestos_id_hijo' => $nodoB['Nodeid'],
                    'activo' => 1,
                ]);
            }
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
                return back();

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
        return back();
    }

    /* Pasar a las diferentes areas */
    public function area(Request $request)
    {
        $DP = departamentoPuesto::where('id', $request->elemento['id'])->first();
        $area = Area::where('id', $request->area)->first();

        if ($DP->areas_id === $area->id) return back();
        $DP->update([
            'areas_id' => strtoupper($request->area),
        ]);

        $relaciones = Padres_hijos::where([['departamento_puestos_id_padre', $DP->id], ['activo', 1]])->orWhere([['departamento_puestos_id_hijo', $DP->id], ['activo', 1]])->get();

        if ($relaciones->count() > 0) {
            foreach ($relaciones as $r) {
                $r->update([
                    'activo' => 0,
                ]);
            }
        }

        return back();
    }

    //Quitamos un Departamento-puesto del area
    public function remove(Request $request)
    {
        $DP = departamentoPuesto::where('id', $request->nodoA['Nodeid'])->first();

        $relaciones = Padres_hijos::where([['departamento_puestos_id_padre', $DP->id], ['activo', '<>', 0]])->get();
        foreach ($relaciones as $rel) {
            $rel->update([
                'activo' => 0
            ]);
        }
        $relaciones = Padres_hijos::where([['departamento_puestos_id_hijo', $DP->id], ['activo', '<>', 0]])->get();
        foreach ($relaciones as $rel) {
            $rel->update([
                'activo' => 0
            ]);
        }

        $DP->update(['areas_id' => 1]);

        return back();
    }

    //Asignamos una relacion entre jefes de areas
    public function jefearea(Request $request)
    {
        $nodoA = (object)$request->nodoA;
        $area = (object)$request->area;
        $hijo = null;
        $PH = null;

        if ($area->padre === null) {
            $ph = Area::select(
                'DP.*',
            )
                ->join('departamento_puestos as DP', 'DP.areas_id', 'areas.id')
                ->where([['areas.nombre', $area->nodoB]])
                ->get();

            foreach ($ph as $p) {
                $h = Padres_hijos::select()->where([['departamento_puestos_id_hijo', $p['id']], ['activo', 1]])->get();
                if (sizeof($h) === 0) $hijo = $p;
            }

            $padre = departamentoPuesto::select('departamento_puestos.*')
                ->join('cecos as dep', 'dep.id', 'departamento_puestos.departamento_id')
                ->join('puestos as pue', 'pue.id', 'departamento_puestos.puesto_id')
                ->where([['dep.nombre', $nodoA->Ceco], ['pue.name', $nodoA->Puesto]])
                ->first();

            if ($hijo != null) {
                $existe = Padres_hijos::where([['departamento_puestos_id_padre', $padre->id], ['departamento_puestos_id_hijo', $hijo->id]])->exists();
                if ($existe) {
                    $PH = Padres_hijos::where([['departamento_puestos_id_padre', $padre->id], ['departamento_puestos_id_hijo', $hijo->id]])->first();
                    $PH->update(['activo' => 2]);
                } else {
                    $PH = Padres_hijos::create([
                        'departamento_puestos_id_padre' => $padre->id,
                        'departamento_puestos_id_hijo' => $hijo->id,
                        'activo' => 2
                    ]);
                }
            }
        }
        return back();
    }

    //Quitamos la relacion entre jefes de area
    public function jefeareaR(Request $request)
    {
        $jefeArea = Padres_hijos::where('id', $request->jARid)->first();
        $jefeArea->update([
            'activo' => 0,
        ]);
        return back();
    }
}
