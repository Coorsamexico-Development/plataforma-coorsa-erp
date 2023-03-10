<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\SignEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class EmpleadoController extends Controller
{
    //

    public function sendFirma(Request $request)
    {
        $request->validate([
            'image' => ['required', 'string']
        ]);
        $customPaper = array(0, 0, 360, 360);
        $pdf = PDF::loadView('pdf.firma', ['image' => $request->image])->setPaper($customPaper, 'landscape');

        Mail::send(new SignEmployee(
            "FIRMA EMPLADO ROBERT",
            'emplado@correo.com',
            "Se adjunta la firma",
            $pdf->output()
        ));
        return response()->json([
            'message' => "OK send image"
        ]);
    }

    public function showFirma()
    {

        $customPaper = array(0, 0, 360, 360);
        $pdf = PDF::loadView('pdf.firma', ['image' => ""])
            ->setPaper($customPaper, 'landscape');

        return $pdf->stream();
    }
}
