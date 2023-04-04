<?php

namespace App\Http\Controllers;

use App\Models\bajasEmpleado;
use App\Models\Banco;
use App\Models\CatBajasEmpleados;
use App\Models\catEstadosCiviles;
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

class EmpleadoControlller extends Controller
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
            ->leftjoin('empleados_puestos', 'empleados_puestos.empleado_id', 'users.id')
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



        return Inertia::render(
            'RH/Empleados/EmpleadosIndex',
            [
                'empleados' => fn () => $empleados->paginate(10),
                'activo' => $activo,
                'filters' => request()->all(['search', 'fields', 'searchs'])
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


        return Inertia::render(
            'RH/Empleados/Create/CreateIndex',
            [
                'escolaridades' => $escolaridades,
                'estados_civiles' => $estado_civiles,
                'cat_tipo_sangre' => $tipos_sangre,
                'bancos' => $bancos,
                'departamentos' => $departamentos,
                'tipos_contrato' => $tipos_contrato,
                'roles' => $roles
            ]
        );
    }


    public function store(Request $request)
    {
        $request->validate([ //validaciones
            'correo_electronico' => 'required | unique:users,email',
            'numero_empleado' => 'required',
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
            'clave_bancaria' => 'required',
            'numero_cuenta_bancaria' => 'required',
            'salario_diario' => 'required',
            'salario_bruto' => 'required',
            'salario_imss' => 'required',
            'bono_puntualidad' => 'required',
            'bono_asistencia' => 'required',
            'despensa' => 'required',
            'fondo_ahorro' => 'required',
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
            'expediente' => 'required',
            'contrato' => 'required',
            'cat_tipos_sangre_id' => 'required',
            'alergias' => 'required',
            'enfermedades_cronicas' => 'required',
            'cat_genero_id' => 'required',
            'rol_id' => 'required',
        ]);

        $newEmpleado =  $request;
        $ruta_fotografia = "";

        if (empty($request['fotografia'])) {
            if ($request->has('fotografia')) {
                if ($request['fotografia'] != null) {
                    $fotografia = request('fotografia');
                    $nombre_fotografia =  $fotografia->getClientOriginalName(); //rescatamos el nombre original
                    $ruta_fotografia = $fotografia->storeAs('expedientes/fotografia/', $nombre_fotografia, 'gcs'); //guardamos el archivo en el storage
                    $urlFotografia = Storage::disk('gcs')->url($ruta_fotografia);
                } else {
                    $urlFotografia = "";
                }
            }
        } else {
            $urlFotografia = "";
        }

        $ruta_fotografia_empresarial = "";
        if (empty($request['foto_empresarial'])) {
            if ($request->has('foto_empresarial')) {
                if ($request['foto_empresarial'] != null) {
                    $fotografia_Empresarial = request('foto_empresarial');
                    $nombre_fotografia_empresarial =  $fotografia_Empresarial->getClientOriginalName(); //rescatamos el nombre original
                    $ruta_fotografia_empresarial = $fotografia_Empresarial->storeAs('expedientes/fotografia/', $nombre_fotografia_empresarial, 'gcs'); //guardamos el archivo en el storage
                    $urlFotografia_Empresarial = Storage::disk('gcs')->url($ruta_fotografia_empresarial);
                } else {
                    $urlFotografia_Empresarial = "";
                }
            }
        } else {
            $urlFotografia_Empresarial = "";
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
            'foto_empresarial' => $urlFotografia_Empresarial
        ]); //creamos el usuario


        if ($request->has('expediente')) {
            if ($request['expediente'] != null) {
                $curp = $request['curp'];
                $expediente  = $request['expediente'];
                /*Guardamos*/
                $rutaExpediente = $expediente->storeAs('expedientes', $curp . '_expediente.' . $expediente->extension(), 'gcs');
                $urlExpediente = Storage::disk('gcs')->url($rutaExpediente);

                expediente::updateOrCreate(
                    [
                        'ruta' => $urlExpediente,
                        'cat_tipos_documento_id' => 25,
                        'empleado_id' => $empleado->id
                    ]
                );
            } else {
            }
        }
        if ($request->has('contrato')) {
            if ($request['contrato'] != null) {
                $curp = $request['curp'];
                $contrato = $request['contrato'];
                /*Guardamos*/
                $rutaContrato = $contrato->storeAs('expedientes/contratos', $curp . '_contrato.' . $contrato->extension(), 'gcs');
                $urlContrato = Storage::disk('gcs')->url($rutaContrato);

                expediente::updateOrCreate(
                    [
                        'ruta' => $urlContrato,
                        'cat_tipos_documento_id' => 26,
                        'empleado_id' => $empleado->id
                    ]
                );
            }
        }

        //creamos el empleado_puesto

        if (!empty($request['puesto_id'])) {
            //$puesto_id = puesto::select('id')->where('name','LIKE','%'.$newEmpleado['puesto_id'].'%')->get();

            empleados_puesto::create([
                'puesto_id' => $request['puesto_id'],
                'departamento_id' => $newEmpleado['departamento_id'],
                'empleado_id' => $empleado->id,
            ]);
        }


        // Store docuemtos

        return redirect()->back();
    }


    public function edit($id)
    {
        $empleado = DB::table(DB::raw('users'))
            ->selectRaw('*')
            ->where('id', '=', $id)
            ->get();

        $empleado_direccion_id = $empleado[0]->direccion_id;

        $direccion = DB::table(DB::raw('direcciones'))
            ->selectRaw(
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
            ->where('users.direccion_id', '=', $empleado_direccion_id)
            ->get();


        $dept_puesto = empleados_puesto::select('puesto_id', 'departamento_id')
            ->where('empleado_id', '=', $id)
            ->get();


        $documentos = expediente::select('*')
            ->where('empleado_id', '=', $id)
            ->get();

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
        )
            ->where('empleado_id', '=', $id)
            ->get();

        $finiquito = finiquito::select(
            'monto',
            'fecha_finiquito',
            'pagado',
        )
            ->where('empleado_id', '=', $id)
            ->get();



        return Inertia::render(
            'RH/Empleados/Create/Edit.Index',
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
                'documentos' => $documentos,
                'cat_bajas' => $cat_bajas,
                'empleado_baja' => $empleado_baja,
                'finiquito' => $finiquito,
                'departamento_puesto' => $dept_puesto
            ]
        );
    }

    public function update(Request $request, User $empleado)
    {
        $request->validate([ //validaciones
            'correo_electronico' => 'required',
            'numero_empleado' => 'required',
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
            'clave_bancaria' => 'required',
            'numero_cuenta_bancaria' => 'required',
            'salario_diario' => 'required',
            'salario_bruto' => 'required',
            'salario_imss' => 'required',
            'bono_puntualidad' => 'required',
            'bono_asistencia' => 'required',
            'despensa' => 'required',
            'fondo_ahorro' => 'required',
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
            'alergias' => 'required',
            'enfermedades_cronicas' => 'required',
            'cat_genero_id' => 'required',
            'rol_id' => 'required',
        ]);

        $urlFoto = '';
        $urlExpediente = '';
        $urlContrato = '';
        $urlFotografiaEmpresarial = '';


        /*Guardado de imagnes, expedientes, contrato*/
        if ($request->has('fotografia') && $request['fotografia'] !== null) {
            if (is_file($request['fotografia'])) {
                $foto =  $request['fotografia'];
                $nombre_original = $foto->getClientOriginalName();
                /*Guardamos*/
                $rutaFoto = $foto->storeAs('fotos', $nombre_original, 'gcs');
                $urlFoto = Storage::disk('gcs')->url($rutaFoto);
            } else {
                $urlFoto = $request['fotografia'];
            }
        } else {
            if ($request['fotografia'] == null) {
                $urlFoto = null;
            } else {
                $foto =  $request['fotografia'];
                $nombre_original = $foto->getClientOriginalName();
                /*Guardamos*/
                $rutaFoto = $foto->storeAs('fotos', $nombre_original, 'gcs');
                $urlFoto = Storage::disk('gcs')->url($rutaFoto);
            }
        }

        /*Guardado de foto empresarial*/
        if ($request->has('foto_empresarial') && $request['foto_empresarial'] !== null) {
            if (is_file($request['foto_empresarial'])) {
                $foto_empresarial =  $request['foto_empresarial'];
                $nombre_original_empresarial = $foto_empresarial->getClientOriginalName();
                /*Guardamos*/
                $rutaFotoEmpresarial = $foto_empresarial->storeAs('fotos', $nombre_original_empresarial, 'gcs');
                $urlFotografiaEmpresarial = Storage::disk('gcs')->url($rutaFotoEmpresarial);
            } else {
                $urlFotografiaEmpresarial = $request['foto_empresarial'];
            }
        } else {
            if ($request['foto_empresarial'] == null) {
                $urlFotografiaEmpresarial = null;
            } else {
                $foto_empresarial =  $request['foto_empresarial'];
                $nombre_original_empresarial = $foto_empresarial->getClientOriginalName();
                /*Guardamos*/
                $rutaFotoEmpresarial = $foto_empresarial->storeAs('fotos', $nombre_original_empresarial, 'gcs');
                $urlFotografiaEmpresarial = Storage::disk('gcs')->url($rutaFotoEmpresarial);
            }
        }

        $newEmpleado =  $request;

        $password = '';

        if (empty($request->password)) {
            $user = DB::table(DB::raw('users'))
                ->selectRaw('*')
                ->where('id', '=', $request['id'])
                ->get();

            $password_user = $user[0]->password;
            $password = $password_user;
        } else {
            $password = Hash::make($request['password']);
        }


        // Guarda nueva direccion si el campo no existe
        if (empty($request->direccion_id)) {
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
        } else //sino actualizamos el existente
        {
            direccione::where('id', $newEmpleado->direccion_id)->update([
                "direccion_localidade_id" => $newEmpleado['direccion_localidade_id'],
                "calle" => $newEmpleado['calle'],
                "numero" => $newEmpleado['numero'],
                "colonia" => $newEmpleado['colonia'],
                "codigo_postal" => $newEmpleado['codigo_postal'],
                "lote" => $newEmpleado['lote'],
                "manzana" => $newEmpleado['manzana']
            ]);
        }
        //Actualizamos el usuario
        $newUser = User::where('id', '=', $newEmpleado['id'])
            ->update([
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
                'fotografia' => $urlFoto,
                'password' =>  $password,
                'role_id' => $newEmpleado['rol_id'],
                /*Datos empresariales */
                'foto_empresarial' => $urlFotografiaEmpresarial,
                'correo_empresarial' => $newEmpleado['correo_empresarial'],
                'telefono_empresarial' => $newEmpleado['telefono_empresarial']
            ]);

        // Store expediente
        if ($request->has('expediente') && $request['expediente'] !== null) {
            if (is_file($request['expediente'])) {
                if ($request['curp'] !== null) {
                    $curp = $request['curp'];
                    $expediente  = $request['expediente'];
                    /*Guardamos*/
                    $rutaExpediente = $expediente->storeAs('expedientes', $curp . '_expediente.' . $expediente->extension(), 'gcs');
                    $urlExpediente = Storage::disk('gcs')->url($rutaExpediente);

                    expediente::updateOrCreate(
                        [
                            'ruta' => $urlExpediente,
                            'cat_tipos_documento_id' => 25,
                            'empleado_id' => $newEmpleado['id']
                        ]
                    );
                }
            }
        }

        // Store contrato

        if ($request->has('contrato') && $request['contrato'] !== null) {
            if (is_file($request['contrato'])) {
                if ($request['curp'] !== null) {
                    $curp = $request['curp'];
                    $contrato  = $request['contrato'];
                    /*Guardamos*/
                    $rutaContrato = $contrato->storeAs('contrato', $curp . '_contrato.' . $contrato->extension(), 'gcs');
                    $urlContrato = Storage::disk('gcs')->url($rutaContrato);

                    expediente::updateOrCreate(
                        [
                            'ruta' => $urlContrato,
                            'cat_tipos_documento_id' => 26,
                            'empleado_id' => $newEmpleado['id']
                        ]
                    );
                }
            }
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


        //Preguntamos si el la categoria de baja viene vacia
        if (!empty($request->cat_bajas_empleado_id)) {
            $request->validate(['fecha_baja' => 'required|after:1900-01-01']);
            bajasEmpleado::updateOrCreate(
                [
                    'cat_bajas_empleado_id' => $request['cat_bajas_empleado_id'],
                    'empleado_id' => $request['id'],
                    'fecha_baja' => $request['fecha_baja']
                ]
            );

            User::where('id', '=', $newEmpleado['id'])  //desactivamos el usuario
                ->update(['activo' => 0]);
        }
        //finiquito_pagado
        if (!empty($request->fecha_finiquito)) {
            finiquito::updateOrCreate(
                [
                    'empleado_id' => $newEmpleado['id'],
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
