<?php

namespace App\Http\Controllers;

use App\Models\bajasEmpleado;
use App\Models\Banco;
use App\Models\CatBajasEmpleados;
use App\Models\catEstadosCiviles;
use App\Models\CatTipoDocumento;
use App\Models\CatTipoDocumeto;
use App\Models\catTipoSangre;
use App\Models\Ceco;
use App\Models\direccione;
use App\Models\empleados_puesto;
use App\Models\Escolaridad;
use App\Models\expediente;
use App\Models\finiquito;
use App\Models\puesto;
use App\Models\Role;
use App\Models\tipoContrato;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class EmpleadoController extends Controller
{
    //
    public function index($activo)
    {
        request()->validate([
            'fields' => ['nullable', 'array']
        ]);
        $empleados = User::select(
            'users.id',
            'users.fotografia',
            'users.numero_empleado',
            'users.name',
            'users.apellido_paterno',
            'users.apellido_materno',
            'users.telefono',
            'users.activo',
            'cecos.nombre AS departamento',
            'puestos.name AS puesto'
        )
            ->with(['expedientes' => function ($query) {
                $query->select(
                    'expedientes.id',
                    'expedientes.ruta',
                    'expedientes.cat_tipos_documento_id',
                    'cat_tipo_documentos.nombre as tipo_documento',
                    'expedientes.empleado_id'
                )
                    ->join('cat_tipo_documentos', 'expedientes.cat_tipos_documento_id', '=', 'cat_tipo_documentos.id');
            }])->leftjoin('empleados_puestos', 'empleados_puestos.empleado_id', 'users.id')
            ->leftjoin('cecos', 'empleados_puestos.departamento_id', 'cecos.id')
            ->leftjoin('puestos', 'empleados_puestos.puesto_id', 'puestos.id');



        if ($activo === 'activo') {
            $this->authorize('user-activos.show');
            $empleados->where('users.activo', 1);
        } else if ($activo === 'inactivo') {
            $this->authorize('user-inactivos.show');
            $empleados->where('users.activo', 0);
        }


        if (request()->has('search')) {
            $search = '%' . strtr(request('search'), array("'" => "\\'", "%" => "\\%")) . '%';
            $empleados->where(function ($query) use ($search) {
                $query->where(
                    'users.name',
                    'LIKE',
                    $search
                )->orWhere('users.apellido_paterno', 'LIKE',  $search)
                    ->orWhere('users.apellido_materno', 'LIKE',  $search)
                    ->orWhere('users.numero_empleado', 'LIKE',  $search)
                    ->orWhere('cecos.nombre', 'LIKE',  $search)
                    ->orWhere('puestos.name', 'LIKE',  $search);
            });
        }

        //Busquedas por campo
        if (request()->has('searchs')) {
            $empleados->where(function ($query) {
                foreach (request('searchs') as $field => $search) {
                    $searchLike = '%' . strtr($search, array("'" => "\\'", "%" => "\\%")) . '%';
                    $query->where($field, 'LIKE', $searchLike);
                }
            });
        }

        if (request()->has('fields')) {
            foreach (request('fields') as $field => $direccion) {
                $empleados->orderBy($field, $direccion);
            }
        }



        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo');
        return Inertia::render(
            'RH/Empleados/EmpleadosIndex',
            [
                'empleados' => fn () => $empleados->paginate(10),
                'activo' => $activo,
                'filters' => request()->all(['search', 'fields', 'searchs']),
                'nominas' => fn () => $nominas->paginate(5)
            ]
        );
    }

    public function createNewEmpleado(Request $request)
    {
        $escolaridades = Escolaridad::all();
        $estado_civiles = catEstadosCiviles::all();
        $tipos_sangre = catTipoSangre::all();
        $bancos = Banco::all();
        $departamentos = Ceco::where('activo_erp', '=', 1)->get();
        $tipos_contrato = tipoContrato::select('id', 'nombre', 'activo')->where('activo', 1)->get();
        $roles = Role::all();
        $tiposDocumentos = CatTipoDocumento::select(
            'id',
            'nombre as tipo_documento',
            'id as cat_tipo_documento_id'
        )->where('activo', '=', 1)->get();
        // return  dd(request());

        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo');

        return Inertia::render(
            'RH/Empleados/Create/CreateEmpleado',
            [
                'escolaridades' => $escolaridades,
                'estados_civiles' => $estado_civiles,
                'cat_tipo_sangre' => $tipos_sangre,
                'bancos' => $bancos,
                'departamentos' => $departamentos,
                'tipos_contrato' => $tipos_contrato,
                'roles' => $roles,
                'expedientes' => $tiposDocumentos,
                'empleado_id' => session()->get('empleado_id'),
                'nominas' => fn () => $nominas->paginate(5)
            ]
        );
    }


    public function store(Request $request)
    {

        $newEmpleado =  $request->validate([ //validaciones
            'correo_electronico' => 'required | unique:users,email',
            'numero_empleado' => 'required|unique:users,numero_empleado',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'fecha_nacimiento' => 'required',
            'fecha_ingreso' => 'required',
            'fecha_ingreso_real' => 'required',
            'nss' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'password' => 'required',
            'contacto_emergencia' => 'required',
            'telefono' => 'required',
            'hijos' => 'required',

            'correo_empresarial' => ['nullable', 'email'],
            'telefono_empresarial' => ['nullable', 'numeric'],
            'clave_bancaria' => 'required',
            'banco_id' => ['nullable', 'exists:bancos,id'],
            'escolaridade_id' => ['nullable', 'exists:escolaridads,id'],
            'numero_cuenta_bancaria' => 'required',
            'salario_diario' => 'required',
            'salario_bruto' => 'required',
            'salario_imss' => 'required',
            'bono_puntualidad' => 'required',
            'bono_asistencia' => 'required',
            'despensa' => 'required',
            'fondo_ahorro' => 'required',
            'horario' => 'required',
            'direccion_estado_id' => 'required',
            'direccion_municipio_id' => 'required',
            'direccion_localidade_id' => 'required',
            'calle' => 'required',
            'numero' => 'required',
            'colonia' => 'required',
            'codigo_postal' => 'required',
            'lote' => 'required',
            'cat_estados_civile_id' => 'required',
            'manzana' => 'required',
            'cat_tipos_nomina_id' => 'required',
            'tipos_contrato_id' => 'required',
            'cat_estados_civile_id' => 'required',
            // 'expediente' => 'required',
            // 'contrato' => 'required',
            'cat_tipos_sangre_id' => 'required',
            'alergias' => ['nullable', 'string'],
            'departamento_id' => ['nullable', 'exists:cecos,id'],
            'enfermedades_cronicas' => ['nullable', 'string'],
            'cat_genero_id' => 'required',
            'rol_id' => 'required',
        ]);


        /*Guardado de imagnes, expedientes, contrato*/
        if ($request->hasFile('fotografia')) {
            $foto =  $request['fotografia'];
            $nombre_original = $foto->getClientOriginalName();
            /*Guardamos*/
            $rutaFoto = $foto->storeAs('fotos', $nombre_original, 'gcs');
            $urlFotografia = Storage::disk('gcs')->url($rutaFoto);
        } else {
            $urlFotografia = null;
        }

        /*Guardado de foto empresarial*/
        if ($request->hasFile('foto_empresarial')) {
            $foto_empresarial =  $request['foto_empresarial'];
            $nombre_original_empresarial = $foto_empresarial->getClientOriginalName();
            /*Guardamos*/
            $rutaFotoEmpresarial = $foto_empresarial->storeAs('fotos', $nombre_original_empresarial, 'gcs');
            $urlFotografiaEmpresarial = Storage::disk('gcs')->url($rutaFotoEmpresarial);
        } else {
            $urlFotografiaEmpresarial = null;
        }

        //creamos la direccion
        $direccion = direccione::create([
            'direccion_localidade_id' => $newEmpleado['direccion_localidade_id'],
            'calle' => $newEmpleado['calle'],
            'numero' => $newEmpleado['numero'],
            'colonia' => $newEmpleado['colonia'],
            'codigo_postal' => $newEmpleado['codigo_postal'],
            'lote' => $newEmpleado['lote'],
            'manzana' => $newEmpleado['manzana'],
        ]);

        $empleado = User::create([
            'numero_empleado' => $newEmpleado['numero_empleado'],
            'name' => $newEmpleado['nombre'],
            'apellido_paterno' => $newEmpleado['apellido_paterno'],
            'apellido_materno' => $newEmpleado['apellido_materno'],
            'email' => $newEmpleado['correo_electronico'],
            'fecha_nacimiento' => $newEmpleado['fecha_nacimiento'],
            'fecha_ingreso' => $newEmpleado['fecha_ingreso'],
            'fecha_ingreso_real' => $newEmpleado['fecha_ingreso_real'],
            'nss' => $newEmpleado['nss'],
            'curp' => $newEmpleado['curp'],
            'rfc' => $newEmpleado['rfc'],
            'contacto_emergencia' => $newEmpleado['contacto_emergencia'],
            'telefono' => $newEmpleado['telefono'],
            'hijos' => $newEmpleado['hijos'],
            'clave_bancaria' => $newEmpleado['clave_bancaria'],
            'numero_cuenta_bancaria' => $newEmpleado['numero_cuenta_bancaria'],
            'salario_diario' => $newEmpleado['salario_diario'],
            'salario_bruto' => $newEmpleado['salario_bruto'],
            'salario_imss' => $newEmpleado['salario_imss'],
            'bono_puntualidad' => $newEmpleado['bono_puntualidad'],
            'bono_asistencia' => $newEmpleado['bono_asistencia'],
            'despensa' => $newEmpleado['despensa'],
            'fondo_ahorro' => $newEmpleado['fondo_ahorro'],
            'horario' => $newEmpleado['horario'],
            'alergias' => $newEmpleado['alergias'],
            'enfermedades_cronicas' => $newEmpleado['enfermedades_cronicas'],
            'direccion_id' => $direccion->id,
            'estado_civil_id' => $newEmpleado['cat_estados_civile_id'],
            'banco_id' => $newEmpleado['banco_id'],
            'escolaridad_id' => $newEmpleado['escolaridade_id'],
            'cat_tipos_nomina_id' => $newEmpleado['cat_tipos_nomina_id'],
            'tipos_contrato_id' => $newEmpleado['tipos_contrato_id'],
            'cat_genero_id' => $newEmpleado['cat_genero_id'],
            'cat_tipo_sangre_id' => $newEmpleado['cat_tipos_sangre_id'],
            'fotografia' => $urlFotografia,
            'password' => Hash::make($newEmpleado['password']),
            'role_id' => $newEmpleado['rol_id'],
            /*Datos enmpresariales*/
            'correo_empresarial' => $newEmpleado['correo_empresarial'],
            'telefono_empresarial' => $newEmpleado['telefono_empresarial'],
            'foto_empresarial' => $urlFotografiaEmpresarial
        ]); //creamos el usuario

        //creamos el empleado_puesto

        if (!empty($request['puesto_id'])) {
            //$puesto_id = puesto::select('id')->where('name','LIKE','%'.$newEmpleado['puesto_id'].'%')->get();

            empleados_puesto::create([
                'puesto_id' => $request['puesto_id'],
                'departamento_id' => $newEmpleado['departamento_id'],
                'empleado_id' => $empleado->id,
            ]);
        }

        return back()->with(['empleado_id' => $empleado->id]);
    }


    /**
     * Almacena los expedientes de un empleado
     */
    public function storeExpediente(Request $request, User $empleado)
    {
        $request->validate([
            'file' => ['required', 'file'],
            'cat_tipo_documento_id' => ['required', 'exists:cat_tipo_documentos,id']
        ]);

        if ($request->hasFile('file')) {
            $curp = $empleado->curp;
            $tipoDoc = CatTipoDocumento::select('cat_tipo_documentos.*')->firstWhere('id', $request['cat_tipo_documento_id']);
            /*Guardamos*/
            $file = $request->file('file');
            $fileName =  $curp . "_" . $tipoDoc->nombre . "." . $file->extension();
            $rutaFile = $file->storeAs("expedientes/$curp/{$tipoDoc->nombre}", $fileName, 'gcs');
            $urlExpediente = Storage::disk('gcs')->url($rutaFile);
            expediente::updateOrCreate(
                [
                    'empleado_id' => $empleado->id,
                    'cat_tipos_documento_id' => $tipoDoc->id,
                ],
                [
                    'ruta' => $urlExpediente,
                ]
            );
        }

        return response()->json([
            'ruta' => $urlExpediente,
            'message' => 'ok'
        ]);
    }

    public function edit($id)
    {

        $empleado = User::selectRaw('*')
            ->findOrFail($id);

        $empleado_direccion_id = $empleado->direccion_id;

        $direccion = direccione::selectRaw(
            'direccion_localidades.id AS localidad_id,
            direccion_municipios.nombre,
            direccion_municipios.id AS municipio_id,
            direccion_estados.nombre,
            direccion_estados.id AS estado_id,
            direcciones.calle AS calle,
            direcciones.numero AS numero,
            direcciones.colonia AS colonia,
            direcciones.codigo_postal AS codigo_postal,
            direcciones.manzana AS manzana,
            direcciones.lote AS lote'
        )
            ->join('users', 'direcciones.id', 'users.direccion_id')
            ->join('direccion_localidades', 'direcciones.direccion_localidade_id', 'direccion_localidades.id')
            ->join('direccion_municipios', 'direccion_localidades.direccion_municipio_id', 'direccion_municipios.id')
            ->join('direccion_estados', 'direccion_municipios.direccion_estado_id', 'direccion_estados.id')
            ->firstWhere('users.direccion_id', '=', $empleado_direccion_id);


        $dept_puesto = empleados_puesto::select('puesto_id', 'departamento_id')
            ->where('empleado_id', '=', $id)
            ->firstWhere('empleados_puestos.activo', '=', 1);

        $escolaridades = Escolaridad::all();
        $estado_civiles = catEstadosCiviles::all();
        $tipos_sangre = catTipoSangre::all();
        $bancos = Banco::all();
        $departamentos = Ceco::where('activo_erp', '=', 1)->get();
        $roles = Role::all();
        $tipos_contrato = tipoContrato::select('id', 'nombre', 'activo')->where('activo', 1)->get();
        $cat_bajas = CatBajasEmpleados::all();

        $empleado_baja = bajasEmpleado::select(
            'cat_bajas_empleado_id',
            'fecha_baja'
        )->where('activo', '=', 1)
            ->firstwhere('empleado_id', '=', $id);

        $finiquito = finiquito::select(
            'monto',
            'fecha_finiquito',
            'pagado',
        )->where('activo', '=', 1)
            ->firstwhere('empleado_id', '=', $id);

        $expedientes = CatTipoDocumento::select(
            'expedientes.*',
            'cat_tipo_documentos.nombre as tipo_documento',
            'cat_tipo_documentos.activo as active',
            'cat_tipo_documentos.id as cat_tipo_documento_id'
        )
            ->leftJoin('expedientes', function ($join) use ($id) {
                $join->on('expedientes.cat_tipos_documento_id', '=', 'cat_tipo_documentos.id')
                    ->on('expedientes.empleado_id', '=', DB::raw($id));
            })
            ->where(function ($query) {
                $query->where('cat_tipo_documentos.activo', '=', 1)
                    ->orWhereNotNull('expedientes.empleado_id');
            })->get();



        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo');
        return Inertia::render(
            'RH/Empleados/Create/EditEmpleado',
            [
                'direccion' => $direccion,
                'empleado' => $empleado,
                'escolaridades' => $escolaridades,
                'estados_civiles' => $estado_civiles,
                'cat_tipo_sangre' => $tipos_sangre,
                'bancos' => $bancos,
                'departamentos' => $departamentos,
                'tipos_contrato' => $tipos_contrato,
                'roles' => $roles,
                'cat_bajas' => $cat_bajas,
                'empleado_baja' => $empleado_baja,
                'finiquito' => $finiquito,
                'departamento_puesto' => $dept_puesto,
                'expedientes'  => $expedientes,
                'nominas' => fn () => $nominas->paginate(5)
            ]
        );
    }

    public function update(Request $request, User $empleado)
    {

        $request->validate([ //validaciones
            'correo_electronico' => 'required',
            'numero_empleado' => 'required|unique:users,numero_empleado,' . $empleado->id . ',id',
            'nombre' => 'required',
            'apellido_paterno' => 'required',
            'apellido_materno' => 'required',
            'fecha_nacimiento' => 'required',
            'fecha_ingreso' => 'required',
            'fecha_ingreso_real' => 'required',
            'nss' => 'required',
            'curp' => 'required',
            'rfc' => 'required',
            'contacto_emergencia' => 'required',
            'telefono' => 'required',
            'hijos' => 'required',
            'correo_empresarial' => ['nullable', 'email'],
            'telefono_empresarial' => ['nullable', 'numeric'],
            'clave_bancaria' => 'required',
            'banco_id' => ['nullable', 'exists:bancos,id'],
            'escolaridade_id' => ['nullable', 'exists:escolaridads,id'],
            'numero_cuenta_bancaria' => 'required',
            'salario_diario' => 'required',
            'salario_bruto' => 'required',
            'salario_imss' => 'required',
            'bono_puntualidad' => 'required',
            'bono_asistencia' => 'required',
            'despensa' => 'required',
            'fondo_ahorro' => 'required',
            'horario' => 'required',
            'alergias' => 'required',
            'enfermedades_cronicas' => 'required',
            'direccion_estado_id' => 'required',
            'direccion_municipio_id' => 'required',
            'direccion_localidade_id' => 'required',
            'calle' => 'required',
            'numero' => 'required',
            'colonia' => 'required',
            'codigo_postal' => 'required',
            'lote' => 'required',
            'cat_estados_civile_id' => 'required',
            'manzana' => 'required',
            'cat_tipos_nomina_id' => 'required',
            'tipos_contrato_id' => 'required',
            'horario' => 'required',
            'cat_estados_civile_id' => 'required',
            'cat_tipos_sangre_id' => 'required',
            'alergias' => ['nullable', 'string'],
            'enfermedades_cronicas' => ['nullable', 'string'],
            'cat_genero_id' => 'required',
            'rol_id' => 'required',
        ]);
          

        $urlFoto = '';
        $urlFotografiaEmpresarial = '';
        /* Haber si jala */

        /*Guardado de imagnes, expedientes, contrato*/
        if ($request->hasFile('fotografia')) {

            $foto =  $request->file('fotografia');
            $nombre_original = $foto->getClientOriginalName();
            /*Guardamos*/
            $rutaFoto = $foto->storeAs('fotos', $nombre_original, 'gcs');
            $urlFoto = Storage::disk('gcs')->url($rutaFoto);
        } else {
            $urlFoto  = $empleado->fotografia;
        }

        /*Guardado de foto empresarial*/
        if ($request->hasFile('foto_empresarial')) {
            $foto_empresarial =  $request->file('foto_empresarial');
            $nombre_original_empresarial = $foto_empresarial->getClientOriginalName();
            /*Guardamos*/
            $rutaFotoEmpresarial = $foto_empresarial->storeAs('fotos', $nombre_original_empresarial, 'gcs');
            $urlFotografiaEmpresarial = Storage::disk('gcs')->url($rutaFotoEmpresarial);
        } else { //para que no exista un cambio
            $urlFotografiaEmpresarial = $empleado->foto_empresarial;
        }
        // Guarda nueva direccion si el campo no existe
        $direccion='';
        if (empty($request->direccion_id)) {
            //creamos la direccion
            $newDireccion = direccione::create([
                'direccion_localidade_id' => $request['direccion_localidade_id'],
                'calle' => $request['calle'],
                'numero' => $request['numero'],
                'colonia' => $request['colonia'],
                'codigo_postal' => $request['codigo_postal'],
                'lote' => $request['lote'],
                'manzana' => $request['manzana'],
            ]);
            $direccion = $newDireccion->id;
        } else //sino actualizamos el existente
        {
           $direccion =  direccione::where('id', $request['direccion_id'])->update([
                "direccion_localidade_id" => $request['direccion_localidade_id'],
                "calle" => $request['calle'],
                "numero" => $request['numero'],
                "colonia" => $request['colonia'],
                "codigo_postal" => $request['codigo_postal'],
                "lote" => $request['lote'],
                "manzana" => $request['manzana']
            ]);
        }

        //Actualizamos el usuario
        $empleado->update([
            'numero_empleado' => $request['numero_empleado'],
            'name' => $request['nombre'],
            'apellido_paterno' => $request['apellido_paterno'],
            'apellido_materno' => $request['apellido_materno'],
            'email' => $request['correo_electronico'],
            'fecha_nacimiento' => $request['fecha_nacimiento'],
            'fecha_ingreso' => $request['fecha_ingreso'],
            'fecha_ingreso_real' => $request['fecha_ingreso_real'],
            'nss' => $request['nss'],
            'curp' => $request['curp'],
            'rfc' => $request['rfc'],
            'contacto_emergencia' => $request['contacto_emergencia'],
            'telefono' => $request['telefono'],
            'hijos' => $request['hijos'],
            'clave_bancaria' => $request['clave_bancaria'],
            'numero_cuenta_bancaria' => $request['numero_cuenta_bancaria'],
            'salario_diario' => $request['salario_diario'],
            'salario_bruto' => $request['salario_bruto'],
            'salario_imss' => $request['salario_imss'],
            'bono_puntualidad' => $request['bono_puntualidad'],
            'bono_asistencia' => $request['bono_asistencia'],
            'despensa' => $request['despensa'],
            'fondo_ahorro' => $request['fondo_ahorro'],
            'horario' => $request['horario'],
            'alergias' => $request['alergias'],
            'enfermedades_cronicas' => $request['enfermedades_cronicas'],
            'direccion_id' => $direccion,
            'estado_civil_id' => $request['cat_estados_civile_id'],
            'banco_id' => $request['banco_id'],
            'escolaridad_id' => $request['escolaridade_id'],
            'cat_tipos_nomina_id' => $request['cat_tipos_nomina_id'],
            'tipos_contrato_id' => $request['tipos_contrato_id'],
            'cat_genero_id' => $request['cat_genero_id'],
            'cat_tipo_sangre_id' => $request['cat_tipos_sangre_id'],
            'fotografia' => $urlFoto,
            'role_id' => $request['rol_id'],
            /*Datos empresariales */
            'foto_empresarial' => $urlFotografiaEmpresarial,
            'correo_empresarial' => $request['correo_empresarial'],
            'telefono_empresarial' => $request['telefono_empresarial'],
            'cat_bajas_empleado_id' => 'nullable|exits:cat_bajas_empleados,id',
            'fecha_baja' => 'required_with:cat_bajas_empleado_id|after:1900-01-01'
        ]);
        //Cambio de contraseña
        if (!empty($request->password)) {
            $password = Hash::make($request['password']);
            $empleado->update(['password' => $password]);
        }

        if (!empty($request->puesto_id) && !empty($request->departamento_id)) {
            $exist_empleados_puesto = empleados_puesto::select('*')
                ->where('empleado_id', '=', $request['id'])
                ->get();

            if (count($exist_empleados_puesto) > 0) {
                if ($exist_empleados_puesto[0]->empleado_id == $request['id']) {
                    empleados_puesto::where('empleado_id', '=', $request['id'])
                        ->update([
                            'puesto_id' => $request['puesto_id'],
                            'departamento_id' => $request['departamento_id']
                        ]);
                } else {
                    empleados_puesto::create([
                        'empleado_id' => $request['id'],
                        'puesto_id' => $request['puesto_id'],
                        'departamento_id' => $request['departamento_id']
                    ]);
                }
            } else {
                empleados_puesto::create([
                    'empleado_id' => $request['id'],
                    'puesto_id' => $request['puesto_id'],
                    'departamento_id' => $request['departamento_id']
                ]);
            }
        }


        //En caso de que contenga la baja
        if (!empty($request->cat_bajas_empleado_id)) {
            bajasEmpleado::updateOrCreate(
                [
                    'cat_bajas_empleado_id' => $request['cat_bajas_empleado_id'],
                    'empleado_id' => $request['id'],
                    'fecha_baja' => $request['fecha_baja']
                ]
            );

            User::where('id', '=', $empleado->id)  //desactivamos el usuario
                ->update(['activo' => 0]);
        }
        //finiquito_pagado
        if (!empty($request->fecha_finiquito)) {
            finiquito::updateOrCreate(
                [
                    'empleado_id' => $empleado->id,
                    'monto' => $request['monto_finiquito'],
                    'fecha_finiquito' => $request['fecha_finiquito'],
                    'pagado' => $request['finiquito_pagado']
                ]
            );
        }
        return redirect()->back();
    }


    public function empleadosData()
    {
        return $empleados = User::select(
            'users.*',
        )->get();
    }
}
