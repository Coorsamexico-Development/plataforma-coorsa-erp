<?php

namespace App\Http\Controllers;

use ZipArchive;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class RecibosNominaController extends Controller
{
    public function index()
    {
        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);
        return Inertia::render(
            'RH/Recibos/RecibosNomina',
            ['nominas' => $nominas]
        );
    }
    public function store(Request $request)
    {
        $request->validate([
            'doc' => 'required|mimes:zip'
        ]);
        $file = $request->file('doc');
        $fecha = explode('-', $file->getClientOriginalName())[0] . '-' . explode('-', $file->getClientOriginalName())[1] . '-01';
        $semana = explode('.', explode('-', $file->getClientOriginalName())[2])[0];
        $temp = Storage::disk('gcs')->put('/nominas', $request->file('doc'));

        $zip = new ZipArchive;
        if ($zip->open(Storage::disk('gcs')->path($temp)) === TRUE) {
            $folder = explode('.', $request->file('doc')->getClientOriginalName())[0];
            $path = Storage::disk('gcs')->path('nominas') . $folder;
            $zip->extractTo($path);
            $zip->close();
        }
        Storage::disk('gcs')->delete($temp);

        $files = Storage::disk('gcs')->files('/nominas/' . $folder);

        foreach ($files as $file) {
            $noEmpleado = explode('.', explode('_', explode('/', $file)[1])[3])[0];
            $pathfile = Storage::disk('gcs')->put('/nominas', new File(Storage::disk('gcs')->path($file)));
            $pathGCS = Storage::disk('gcs')->url($pathfile);

            DB::table('nominas_empleados')->create([
                'empleado_id' => 809,
                'nomina_doc' => $pathGCS,
                'fecha_doc' => $fecha,
                'periodo' => $semana,
            ]);
        }
        Storage::disk('gcs')->deleteDirectory('/nominas/' . $folder);
        return redirect()->back();
    }
}
