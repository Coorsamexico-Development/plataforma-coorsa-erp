<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Areas_padres_hijos;
use App\Models\departamentoPuesto;
use App\Models\Padres_hijos;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /* Agregamos una nueva Area */
    public function store(Request $request)
    {

        if (Area::where('nombre', $request->area)->exists()) {
            $request->validate([
                'area' => 'required'
            ]);

            $area = Area::where('nombre', $request->area)->first();
            $area->update([
                'activo' => 1
            ]);
        } else {
            $request->validate([
                'area' => 'required|unique:areas,nombre'
            ]);

            Area::create([
                'nombre' => strtoupper($request->area),
                'activo' => 1
            ]);
        }
    }

    public function relacion(Request $request)
    {
        /* Definimos nuestros nodos con los que vamos a trabajar */
        $nodoA = $request->nodoA;
        $nodoB = $request->nodoB;
        $nodoC = $request->nododC; /* Nodo de recuperacion */
        $nodoD = $request->nodoD; /* Nodo de relcion anterior */

        if ($nodoC === null && $nodoA != null) {
            /* Revisamos si alguno de nuestros nodos Padre->Hijo estan vacios de ser así el nodoC tendra la infomacion que requerimos para ubtener el nodo faltante */
            if ($nodoA === null) {
                $nodoF = explode('/', $request->nodoC['from']);
                $nodoA = get_object_vars(Area::where([['nombre', $nodoF[0]]])
                    ->first());
            } elseif ($nodoB === null) {
                $nodoF = explode('/', $request->nodoC['from']);
                $nodoB = get_object_vars(Area::where([['nombre', $nodoF[0]]])
                    ->first());
            }

            /* Una vez tenemos los nodos Padre->Hijo revisamos que no exista un registro previo */
            $relAft = Areas_padres_hijos::where([['areas_id_padre', $nodoA['nid']], ['areas_id_hijo', $nodoB['nid']]])->first();
            $existe = Areas_padres_hijos::where([['areas_id_padre', $nodoB['nid']], ['areas_id_hijo', $nodoA['nid']], ['activo', '<>', '0']])->exists();
            if (!$existe) {
                if ($nodoD != null) {
                    if ($nodoD['nid'] != $nodoB['nid']) {
                        $relBef = Areas_padres_hijos::where([['areas_id_padre', $nodoA['nid']], ['areas_id_hijo', $nodoD['nid']]])->first();

                        if ($relAft === null) {
                            /* Como no existe un registro previo entonces lo creamos y desactivamos la relacion actual*/
                            $relBef->update([
                                'activo' => 0
                            ]);
                            Areas_padres_hijos::create([
                                'areas_id_padre' => $nodoA['nid'],
                                'areas_id_hijo' => $nodoB['nid'],
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
                            Areas_padres_hijos::create([
                                'areas_id_padre' => $nodoA['nid'],
                                'areas_id_hijo' => $nodoB['nid'],
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
                    if (Areas_padres_hijos::where([['areas_id_padre', $nodoA['nid']], ['areas_id_hijo', $nodoB['nid']]])->exists()) {
                        $relAft->update(['activo' => 1]);
                    } else {
                        Areas_padres_hijos::create([
                            'areas_id_padre' => $nodoA['nid'],
                            'areas_id_hijo' => $nodoB['nid'],
                            'activo' => 1,
                        ]);
                    }
                }
            }
        }
        return back();
    }

    /* Daamos de baja las relaciones */
    public function destroy(Request $request)
    {
        /* Definimos nuestros nodos con los que vamos a trabajar */
        $nodoA = $request->nodoA;
        $nodoB = $request->nodoB;
        $nodoC = $request->nododC; /* Nodo de recuperacion */
        $nodoD = $request->nodoD; /* Nodo de relcion anterior */

        if ($nodoA === null) {
            $nodoA = Area::where('nombre', $request->nodoC['from'])->first();

            $relBef = Areas_padres_hijos::where([['areas_id_padre', $nodoA->id], ['areas_id_hijo', $nodoD['nid']]])->first();

            $relBef->update([
                'activo' => 0
            ]);
        } else {
            if ($nodoB != null) {
                $nodoA = Area::where('id', $nodoA['nid'])->first();

                $relBef = Areas_padres_hijos::where([['areas_id_padre', $nodoA->id], ['areas_id_hijo', $nodoB['nid']]])->first();

                $relBef->update([
                    'activo' => 0
                ]);
            }
        }

        return back();
    }

    public function remove(Request $request)
    {

        //Damos de baja la relacion que había entre el Departamento-Puesto y el area
        $area = Area::where('id', $request->area)->first();
        $PH = departamentoPuesto::where('areas_id', $area->id)->get();
        foreach ($PH as $dep) {
            $dep->update([
                'areas_id' => 1
            ]);
            $rels = Padres_hijos::where('departamento_puestos_id_padre', $dep->id)->get();
            foreach ($rels as $rel) {
                $rel->update([
                    'activo' => 0
                ]);
                $rels = Padres_hijos::where('departamento_puestos_id_hijo', $dep->id)->get();
                foreach ($rels as $rel) {
                    $rel->update([
                        'activo' => 0
                    ]);
                }
            }
        }

        //Desactivamos las relaciones que tuviera con otras areas
        $aph = Areas_padres_hijos::where('areas_id_padre', $area->id)->get();
        foreach ($aph as $ar) {
            $ar->update([
                'activo' => 0
            ]);
        }
        $aph = Areas_padres_hijos::where('areas_id_hijo', $area->id)->get();
        foreach ($aph as $ar) {
            $ar->update([
                'activo' => 0
            ]);
        }

        //Desactivamos el area
        $area->update([
            'activo' => 0
        ]);

        return back();
    }

    public function edit(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:areas,id',
            'nombre' => 'required|unique:areas,nombre'
        ]);
        $area = Area::where('id', $request->id)->first();
        $area->update([
            'nombre' => $request->nombre
        ]);
    }
}
