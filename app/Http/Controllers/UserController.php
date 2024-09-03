<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Plataforma;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

use App\Exports\UsersExport;
use App\Models\empleados_puesto;
use App\Models\UserRedes;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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
            ->leftjoin('cecos', 'dp.departamento_id', 'cecos.id')
            ->leftjoin('ubicaciones', 'cecos.ubicacione_id', 'ubicaciones.id')
            ->where('users.numero_empleado', '=', $numero_empleado)
            ->where('users.activo', '=', 1)
            ->first();

        $socialNet = [];

        foreach (UserRedes::select('rs.name as red', 'user_redes.value')->join('redes_sociales as rs', 'rs.id', 'user_redes.redes_id')->where('user_redes.user_id', $dataUser->id)->get() as $red)
            $socialNet[strtolower($red->red)] = $red->value;
        $usuario = (object) [
            'name' => $dataUser->name,
            'puesto' => [
                'es' => $dataUser->puesto,
                'eng' => ''
            ],
            'email' => $dataUser->email,
            'numbers' => (object)[
                'mex' => "+52{$dataUser->telMex}"
            ],
            'socialNet' => $socialNet,
            'foto' => $dataUser->foto_empresarial ?? $dataUser->profile_photo_path,
            'vCard' => '/assets/vCards/Renato.vcf',
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
}
