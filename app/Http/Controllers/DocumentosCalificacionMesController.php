<?php

namespace App\Http\Controllers;

use App\Models\DocumentosCalificacionMes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $fileUrl = str_replace("https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET'), "/", $documentosCalificacionMes->documento_url);
        Storage::disk('gcs')->delete($fileUrl);
        $documentosCalificacionMes->delete();
        return redirect()->back();
    }
}
