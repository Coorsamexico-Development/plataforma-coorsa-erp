<?php

namespace App\Http\Controllers;

use App\Models\NominasEmpleado;
use App\Models\User;
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
            $pathfile = Storage::disk('gcs')->put('/nominas', new File(Storage::disk('docs')->path($file)));
            $pathGCS = Storage::disk('gcs')->url($pathfile);

            NominasEmpleado::create([
                'empleado_id' => $user->id,
                'nomina_doc' => $pathGCS,
                'fecha_doc' => $fecha,
                'periodo' => $semana,
            ]);
        }
        Storage::disk('docs')->deleteDirectory($folder);
        return redirect()->back();
    }
}
