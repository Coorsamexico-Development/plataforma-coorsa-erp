<?php

namespace App\Http\Controllers;

use ZipArchive;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\NominasEmpleado;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class RecibosNominaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'doc' => 'required|mimes:zip'
        ]);
        $file = $request->file('doc');
        $fecha = explode('-', $file->getClientOriginalName())[0] . '-' . explode('-', $file->getClientOriginalName())[1] . '-02';
        $semana = explode('.', explode('-', $file->getClientOriginalName())[2])[0];
        $temp = Storage::disk('docs')->put('/', $request->file('doc'));

        $zip = new ZipArchive;
        if ($zip->open(Storage::disk('docs')->path($temp)) === TRUE) {
            $folder = explode('.', $request->file('doc')->getClientOriginalName())[0];
            $path = Storage::disk('docs')->path('/') . $folder;
            $zip->extractTo($path);
            $zip->close();
        }
        Storage::disk('docs')->delete($temp);

        $files = Storage::disk('docs')->files($folder);

        foreach ($files as $file) {
            $noEmpleado = explode('_', explode('/', $file)[1])[0];
            $user = User::where('numero_empleado', $noEmpleado)->first();
            $extension = explode('.', $file)[1];
            if ($extension === 'pdf') {
                if ($user) {
                    $docs = new UploadedFile(Storage::disk('docs')->path($file), explode('/', $file)[1], 'application/pdf');
                    $name = explode('.', $docs->getClientOriginalName())[0] . "_{$semana}" . date('y');
                    $pathfile = $docs->storeAs("nominas/{$user->id}", "{$name}.{$docs->getClientOriginalExtension()}", 'gcs');
                    $pathGCS = Storage::disk('gcs')->url($pathfile);

                    NominasEmpleado::updateOrCreate([
                        'empleado_id' => $user->id,
                        'fecha_doc' => $fecha,
                        'periodo' => $semana,
                    ], [
                        'nomina_doc' => $pathGCS,
                    ]);
                }
            }
        }
        Storage::disk('docs')->deleteDirectory($folder);
        return redirect()->back();
    }
}
