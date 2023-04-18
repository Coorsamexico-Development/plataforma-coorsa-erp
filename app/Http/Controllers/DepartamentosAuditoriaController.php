<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Rubro;
use App\Models\Proceso;
use App\Models\CalfRubroMe;
use App\Models\DocumentosMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\DepartamentosAuditoria;
use Illuminate\Support\Facades\Storage;

class DepartamentosAuditoriaController extends Controller
{
    public function index()
    {
        $usuarios = User::all();
        $departamentosAuditoria = DepartamentosAuditoria::orderBy('id', 'asc');

        $procesos = Proceso::select('procesos.*');

        $calificaciones_mes = CalfRubroMe::select(
            'calf_rubro_mes.*',
            'procesos.id AS proceso_id',
            'procesos.nombre as proceso_name',
            'rubros.nombre as rubro_name'
        )
            ->join('rubros', 'calf_rubro_mes.rubro_id', 'rubros.id')
            ->join('procesos', 'rubros.proceso_id', 'procesos.id')
            ->orderBy('año', 'ASC')
            ->orderBy('mes', 'ASC');

        $rubros = Rubro::select(
            'rubros.id AS rubro_id',
            'rubros.nombre AS rubro_name'
        )
            ->join('procesos', 'rubros.proceso_id', 'procesos.id');

        $documentos_mes = DocumentosMe::select(
            'documentos_mes.documento',
            'documentos_mes.mes',
            'documentos_mes.portada AS portada',
            'procesos.nombre AS proceso_name',
            'users.name',
            'users.apellido_paterno',
            'users.apellido_materno'
        )
            ->join('procesos', 'documentos_mes.proceso_id', 'procesos.id')
            ->join('users', 'documentos_mes.user_id', 'users.id');


        if (request()->has('departamento_auditoria_id')) {
            $departamento =  DepartamentosAuditoria::find(request('departamento_auditoria_id'));
            $procesos->where('procesos.departamento_auditoria_id', '=', request('departamento_auditoria_id'));
            $calificaciones_mes->where('procesos.departamento_auditoria_id', '=', request('departamento_auditoria_id'));
            $documentos_mes->where('procesos.departamento_auditoria_id', '=', request('departamento_auditoria_id'));
            $rubros->where('procesos.departamento_auditoria_id', '=', request('departamento_auditoria_id'));

            //$calificaciones = $departamento->documentosCalificacionesMes()->orderBy('mes', 'asc');
        }

        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        return Inertia::render('ControlInterno/DashboardAuditoria', [
            'departamentosAuditoria' => fn () => $departamentosAuditoria->get(),
            'procesos' => fn () => $procesos->get(),
            'filters' => request()->all(['departamento_auditoria_id']),
            'usuarios' => $usuarios,
            'calificaciones_mes' => fn () => $calificaciones_mes->get(),
            'documentos_mes' => fn () => $documentos_mes->get(),
            'rubros_mes' => fn () => $rubros->get(),
            'nominas' => $nominas
        ]);
    }

    public function storeProceso(Request $request)
    {
        $request->validate([
            'nombre' =>  ['required'],
            'descripción' => ['required'],
            "departamento_auditoria_id" => ['required']
        ]);

        if ($request['logo']  !== null) {
            $logo = request('logo');
            $nombre_logo =  $logo->getClientOriginalName(); //rescatamos el nombre original
            $ruta_logo = $logo->storeAs('procesos/logos', $nombre_logo, 'gcs'); //guardamos el archivo en el storage
            $urlFotografia = Storage::disk('gcs')->url($ruta_logo);

            Proceso::create([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'departamento_auditoria_id' => $request['departamento_auditoria_id'],
                'logo' => $urlFotografia
            ]);
        } else {
            Proceso::create([
                'nombre' => $request['nombre'],
                'descripcion' => $request['descripcion'],
                'departamento_auditoria_id' => $request['departamento_auditoria_id'],
            ]);
        }

        return redirect()->back();
    }

    public function storeDocumento(Request $request)
    {
        $documento = request('documento');
        $nombre_documento =  $documento->getClientOriginalName(); //rescatamos el nombre original
        $ruta_documento = $documento->storeAs('procesos/documentos', $nombre_documento, 'gcs'); //guardamos el archivo en el storage
        $urlDocumento = Storage::disk('gcs')->url($ruta_documento);

        $portada = request('portada');
        $nombre_portada = $portada->getClientOriginalName();
        $ruta_portada = $portada->storeAs('procesos/documentos/portadas', $nombre_portada, 'gcs');
        $urlPortada = Storage::disk('gcs')->url($ruta_portada);

        DocumentosMe::create([
            'user_id' => $request['user_id'],
            'proceso_id' => $request['proceso_id'],
            'mes' => $request['mes'],
            'documento' => $urlDocumento,
            'portada' => $urlPortada
        ]);

        return redirect()->back();
    }

    public function updateDocumento(Request $request, $idDocumento)
    {

        $documento = request('documento');
        $nombre_documento =  $documento->getClientOriginalName(); //rescatamos el nombre original
        $ruta_documento = $documento->storeAs('procesos/documentos', $nombre_documento, 'gcs'); //guardamos el archivo en el storage
        $urlDocumento = Storage::disk('gcs')->url($ruta_documento);

        $portada = request('portada');
        $nombre_portada = $portada->getClientOriginalName();
        $ruta_portada = $portada->storeAs('procesos/documentos/portadas', $nombre_portada, 'gcs');
        $urlPortada = Storage::disk('gcs')->url($ruta_portada);

        DocumentosMe::where('documentos_mes.id', '=', $idDocumento)
            ->update([
                'user_id' => $request['user_id'],
                'proceso_id' => $request['proceso_id'],
                'mes' => $request['mes'],
                'documento' => $urlDocumento,
                'portada' => $urlPortada
            ]);

        return redirect()->back();
    }


    public function getDocumentos($proceso_id)
    {
        return DocumentosMe::select(
            'documentos_mes.*',
            'users.id AS user_id',
            'users.name AS user_name',
            'users.apellido_paterno AS user_apellido_paterno',
            'users.apellido_materno AS user_apellido_materno'
        )
            ->join('users', 'documentos_mes.user_id', 'users.id')
            ->where('documentos_mes.proceso_id', '=', $proceso_id)
            ->get();
    }

    public function destroyDocumento(DocumentosMe $documento)
    {
        $fileUrl = str_replace("https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET'), "/", $documento->documento);
        Storage::disk('gcs')->delete($fileUrl);
        $documento->delete();
        return redirect()->back();
    }

    public function storeRubro(Request $request)
    {
        Rubro::create([
            'proceso_id' => $request['proceso_id'],
        ]);
        return redirect()->back();
    }

    public function getRubros($proceso_id, $año)
    {
        return  Rubro::select('rubros.*')
            ->with([
                'calificaciones' => function ($query1) use ($año) {
                    $query1->select(
                        'calf_rubro_mes.*'
                    )
                        ->where('calf_rubro_mes.año', '=', $año);
                }
            ])
            ->where('rubros.proceso_id', '=', $proceso_id)
            ->get();
    }

    public function updateRubro(Request $request, $rubro_id)
    {
        //return $rubro_id;
        $request->validate([
            'nombre' =>  ['required']
        ]);
        Rubro::where('id', '=', $rubro_id)
            ->update([
                'nombre' => $request['nombre'],
            ]);

        return redirect()->back();
    }

    public function storeCalf(Request $request)
    {
        $request->validate([
            'rubro_id' =>  ['required'],
            'valor' =>  ['required'],
        ]);

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

    public function recuperarRubros($categoria, $mes, $año)
    {
        /*
     $mesBuscado = 0;
     switch ($mes) 
     {
        case 'Ene':
              $mesBuscado = 1;
            break;
        case 'Feb':
              $mesBuscado = 2;
            break;
        case 'Mar':
              $mesBuscado = 3;
            break;
        case 'Abr':
              $mesBuscado = 4;
            break;
        case 'May':
              $mesBuscado = 5;
            break;
        case 'Jun':
             $mesBuscado = 6;
          break;
        case 'Jul':
            $mesBuscado = 7;
          break;
        case 'Ago':
            $mesBuscado = 8;
          break;
        case 'Sep':
            $mesBuscado = 9;
          break;
        case 'Oct':
            $mesBuscado = 10;
          break;
        case 'Nov':
            $mesBuscado = 11;
          break;
        case 'Dic':
            $mesBuscado = 12;
          break;
        
     }
     */

        $rubros = Rubro::select(
            'rubros.nombre AS rubro_name'
        )
            ->join('procesos', 'rubros.proceso_id', 'procesos.id')
            ->where('procesos.nombre', 'LIKE', '%' . $categoria . '%')
            ->get();

        $valores =  CalfRubroMe::select(
            'rubros.nombre AS rubro_nombre',
            'calf_rubro_mes.valor AS valor',
        )
            ->join('rubros', 'calf_rubro_mes.rubro_id', 'rubros.id')
            ->join('procesos', 'rubros.proceso_id', 'procesos.id')
            ->where('procesos.nombre', 'LIKE', '%' . $categoria . '%')
            ->where('calf_rubro_mes.mes', '=', $mes)
            ->where('calf_rubro_mes.año', '=', $año)
            ->get();

        return [$rubros, $valores];
    }
}
