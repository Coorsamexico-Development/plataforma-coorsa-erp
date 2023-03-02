<?php

namespace App\Http\Controllers;

use App\Models\CalfRubroMe;
use App\Models\DepartamentosAuditoria;
use App\Models\DocumentosMe;
use App\Models\Proceso;
use App\Models\Rubro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class DepartamentosAuditoriaController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $departamentosAuditoria = DepartamentosAuditoria::orderBy('id', 'asc');

        $procesos = Proceso::select('procesos.*');

        $calificaciones_mes = CalfRubroMe::select('calf_rubro_mes.*',
        'procesos.id AS proceso_id')
        ->join('rubros', 'calf_rubro_mes.rubro_id' , 'rubros.id')
        ->join('procesos', 'rubros.proceso_id', 'procesos.id');

        if (request()->has('departamento_auditoria_id'))
         {
            $departamento =  DepartamentosAuditoria::find(request('departamento_auditoria_id'));
            $procesos->where('procesos.departamento_auditoria_id','=',request('departamento_auditoria_id'));
            $calificaciones_mes->where('procesos.departamento_auditoria_id','=',request('departamento_auditoria_id'));
            //$calificaciones = $departamento->documentosCalificacionesMes()->orderBy('mes', 'asc');
        }

        return Inertia::render('ControlInterno/DashboardAuditoria', [
            'departamentosAuditoria' => fn () => $departamentosAuditoria->get(),
            'procesos' => fn() =>$procesos->get(),
            'filters' => request()->all(['departamento_auditoria_id']),
            'usuarios' => $usuarios,
            'calificaciones_mes' => fn () => $calificaciones_mes ->get()
        ]);
    }

    public function storeProceso(Request $request)
    {
        $request->validate([
            'nombre' =>  ['required'],
            'descripción' => ['required'],
            "departamento_auditoria_id" => ['required']
        ]);

        if($request['logo']  !== null)
        {
            $logo = request('logo');
            $nombre_logo =  $logo->getClientOriginalName(); //rescatamos el nombre original
            $ruta_logo= $logo->storeAs('procesos/logos', $nombre_logo, 'gcs'); //guardamos el archivo en el storage
            $urlFotografia = Storage::disk('gcs')->url($ruta_logo);

            Proceso::create([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'departamento_auditoria_id'=> $request['departamento_auditoria_id'], 
                'logo' => $urlFotografia
            ]);
        }
        else
        {
           Proceso::create([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'departamento_auditoria_id'=> $request['departamento_auditoria_id'], 
            ]);
        }

        return redirect()->back();
    }

    public function storeDocumento (Request $request)
    {

          $documento = request('documento');
          $nombre_documento =  $documento->getClientOriginalName(); //rescatamos el nombre original
          $ruta_documento= $documento->storeAs('procesos/documentos', $nombre_documento, 'gcs'); //guardamos el archivo en el storage
          $urlDocumento = Storage::disk('gcs')->url($ruta_documento);


        DocumentosMe::create([
            'user_id' => $request['user_id'],
            'proceso_id' => $request['proceso_id'],
            'mes' => $request['mes'],
            'documento' => $urlDocumento
        ]);

        return redirect()->back();
    }


    public function getDocumentos ($proceso_id) 
    {
        return DocumentosMe::select('documentos_mes.*', 
        'users.id AS user_id',
        'users.name AS user_name',
        'users.apellido_paterno AS user_apellido_paterno',
        'users.apellido_materno AS user_apellido_materno')
        ->join('users','documentos_mes.user_id','users.id')
        ->where('documentos_mes.proceso_id','=',$proceso_id)
        ->get();
    }

    public function destroyDocumento(DocumentosMe $documento)
    {
        $fileUrl = str_replace("https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET'), "/", $documento->documento);
        Storage::disk('gcs')->delete($fileUrl);
        $documento->delete();
        return redirect()->back();
    }

    public function storeRubro (Request $request)
    {

       Rubro::create([
         'proceso_id' => $request['proceso_id'],
       ]);
       return redirect()->back();
    }

    public function getRubros($proceso_id)
    {
        return  Rubro::select('rubros.*')
        ->with('calificaciones')
        ->where('rubros.proceso_id','=',$proceso_id)
        ->get();
    }

    public function updateRubro(Request $request, $rubro_id)
    {
       //return $rubro_id;
       Rubro::where('id','=',$rubro_id)
       ->update([
          'nombre'=> $request['nombre'],
       ]);

       return redirect()->back();
    }

    public function storeCalf (Request $request)
    {
       CalfRubroMe::updateOrCreate(
         ['rubro_id' => $request['rubro_id'], 'mes' => $request['mes'], 'año' => $request['año']],
         ['valor' => $request['valor']]
       );
       return redirect()->back();
    }
 
/*
    public function storeCalificacion(DepartamentosAuditoria $departamentosAuditoria, Request $request)
    {
        $documentoUrl = "";
        if ($request->has('documento')) {
            $file = $request->file('documento');

            $rutaImage = $file->store('calificaciones', 'gcs');
            $documentoUrl = Storage::disk('gcs')->url($rutaImage);
        }
        $departamentosAuditoria->documentosCalificacionesMes()->updateOrCreate([

            'mes' => $request->mes,

        ], [
            'calificacion' => $request->calificacion,
            'user_id' => Auth::id(),
            'documento_url' => $documentoUrl,
            'fecha_creacion' => now(),
        ]);
        return redirect()->back();
    }
 */
}
