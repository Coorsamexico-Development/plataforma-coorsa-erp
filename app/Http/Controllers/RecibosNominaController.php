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
        $fecha = explode('-', $file->getClientOriginalName())[0] . '-' . explode('-', $file->getClientOriginalName())[1] . '-01';
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
            $noEmpleado = explode('.', explode('_', explode('/', $file)[1])[3])[0];
            $user = User::where('numero_empleado', $noEmpleado)->first();
            dd($user);
            if ($user->id) {
                $docs = new UploadedFile(Storage::disk('docs')->path($file), explode('/', $file)[1], 'application/pdf');
                $pathfile = $docs->storeAs("nominas/{$user->id}", $docs->getClientOriginalName(), 'gcs');
                $pathGCS = Storage::disk('gcs')->url($pathfile);

                NominasEmpleado::create([
                    'empleado_id' => $user->id,
                    'nomina_doc' => $pathGCS,
                    'fecha_doc' => $fecha,
                    'periodo' => $semana,
                ]);
            }
        }
        Storage::disk('docs')->deleteDirectory($folder);
        return redirect()->back();
    }
}
