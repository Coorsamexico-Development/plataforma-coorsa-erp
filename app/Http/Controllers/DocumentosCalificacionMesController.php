<?php

namespace App\Http\Controllers;

use App\Models\DocumentosCalificacionMes;
use Illuminate\Http\Request;

class DocumentosCalificacionMesController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentosCalificacionMes $documentosCalificacionMes)
    {
        $documentosCalificacionMes->delete();
        return redirect()->back();
    }
}
