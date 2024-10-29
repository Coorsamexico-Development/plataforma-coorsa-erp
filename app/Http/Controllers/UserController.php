<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\UserRedes;
use App\Mail\SignEmployee;
use App\Models\Plataforma;

use App\Exports\UsersExport;
use Illuminate\Http\Request;
use App\Mail\WelcomeEmployde;
use App\Models\empleados_puesto;
use App\Traits\SolicitudesTrait;
use App\Helpers\SendResetPassword;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{



    /**
     * Get list of users
     */
    public function list()
    {
        return response()->json(User::select('id')
            ->selectRaw("concat(users.name,IFNULL(concat(' ',users.apellido_paterno), '')) as name")->get());
    }

    public function export($activo)
    {
        if ($activo === 'activo') {
            return Excel::download(new UsersExport(1), 'Reporte_Empleados_Activos.xlsx');
        }

        if ($activo === 'inactivo') {
            return Excel::download(new UsersExport(0), 'Reporte_Empleados_Inactivos.xlsx');
        }
    }

    public function viewCard($numero_empleado)
    {
        $dataUser = User::select(
            'users.id',
            DB::raw('lower(users.name) as name'),
            'puestos.name AS puesto',
            'users.correo_empresarial as email',
            'users.telefono_empresarial as telMex',
            'users.foto_empresarial',
            'users.profile_photo_path',
        )
            ->leftjoin('empleados_puestos', 'empleados_puestos.empleado_id', 'users.id')
            ->leftJoin('departamento_puestos as dp', 'dp.id', 'empleados_puestos.dpto_puesto_id')
            ->leftjoin('puestos', 'dp.puesto_id', 'puestos.id')
            ->where('users.numero_empleado', '=', $numero_empleado)
            ->first();

        $socialNet = [];

        foreach (UserRedes::select('rs.name as red', 'user_redes.value')->join('redes_sociales as rs', 'rs.id', 'user_redes.redes_id')->where('user_redes.user_id', $dataUser->id)->get() as $red)
            $socialNet[strtolower($red->red)] = $red->value;
        $usuario = (object) [
            'name' => $dataUser->name,
            'puesto' => [
                'es' => $dataUser->puesto,
                'eng' => SolicitudesTrait::translateText($dataUser->puesto),
            ],
            'email' => $dataUser->email,
            'numbers' => (object)[
                'mex' => "+52{$dataUser->telMex}"
            ],
            'socialNet' => $socialNet,
            'foto' => $dataUser->foto_empresarial ?? $dataUser->profile_photo_path,
            'vCard' => UserRedes::select('value')->where([['user_id', $dataUser->id], ['redes_id', 5]])->first()->value ?? null,
        ];

        return Inertia::render(
            'vCard/Card',
            [
                'user' => $usuario
            ]
        );
    }

    public function getPuesto($id)
    {
        $puesto  = empleados_puesto::select('puestos.name AS puesto')
            ->where('empleado_id', '=', $id)
            ->join('puestos', 'empleados_puestos.puesto_id', '=', 'puestos.id')
            ->get();

        return $puesto[0];
    }

    public function getUsers(Request $request)
    {
        $users = User::select(
            'id',
            'numero_empleado',
            'name',
            'apellido_paterno',
            'apellido_materno',
            'fecha_nacimiento',
            'curp',
            'nss',
            'rfc',
            'vacaciones',
            'activo',
            'email',
            'password'
        )
            ->where('numero_empleado', '<>', 0)
            ->whereNotNull(['numero_empleado', 'rfc', 'nss', 'curp', 'fecha_nacimiento'])
            ->orderBy('id');

        if ($request->has('userId')) $users->where('numero_empleado', $request->userId);


        return response()->json($users->get());
    }

    public function getMeails(Request $request)
    {
        $users = User::select(
            'email',
            'password'
        )
            ->where('curp', $request->curp);

        if ($request->has('userId')) $users->where('numero_empleado', $request->userId);


        return response()->json($users->first());
    }

    public function sendMail()
    {
        set_time_limit(0);
        try {
            $users = User::where('activo', 1)->get();
            $erros = [];
            $corrects = 0;

            foreach ($users as $user) {
                try {
                    $sendRestPassword = new SendResetPassword();
                    $sendRestPassword->send($user);
                    $corrects++;
                } catch (Exception $e) {
                    array_push([
                        'id' => $user->id,
                        'user' => "{$user->name} {$user->apellido_paterno} {$user->apellido_materno}",
                        'email' => $user->email,
                        'error' => $e
                    ]);
                }
            }

            return response()->json([
                'status' => 200,
                'errors' => $erros,
                'envios correctos' => $corrects,
            ]);
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
