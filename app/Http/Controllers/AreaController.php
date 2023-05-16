<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Areas_padres_hijos;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /* Agregamos una nueva Area */
    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|unique:areas,nombre'
        ]);

        Area::create([
            'nombre' => $request->area,
        ]);
        return redirect()->back();
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
            $nodoA = get_object_vars(Area::where([['nombre', $nodoF[0]]])
                ->first());
        } elseif ($nodoB === null) {
            $nodoF = explode('/', $request->nodoC['from']);
            $nodoB = get_object_vars(Area::where([['nombre', $nodoF[0]]])
                ->first());
        }

        /* Una vez tenemos los nodos Padre->Hijo revisamos que no exista un registro previo */
        $relAft = Areas_padres_hijos::where([['areas_id_padre', $nodoA['nid']], ['areas_id_hijo', $nodoB['nid']]])->first();

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
            Areas_padres_hijos::create([
                'areas_id_padre' => $nodoA['nid'],
                'areas_id_hijo' => $nodoB['nid'],
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
        dd($nodoA, $nodoB, $nodoC, $nodoD);

        if ($nodoA === null) {
            $nodoF = explode('/', $request->nodoC['from']);
            $nodoA = get_object_vars(Area::where([['nombre', $nodoF[0]]])
                ->first());

            $relBef = Areas_padres_hijos::where([['areas_id_padre', $nodoA['nid']], ['areas_id_hijo', $nodoD['nid']]])->first();

            $relBef->update([
                'activo' => 0
            ]);
        }
        return redirect()->back();
    }
}
