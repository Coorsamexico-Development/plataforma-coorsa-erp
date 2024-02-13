<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromQuery, WithHeadings
{
    use Exportable;

    public function __construct(int $activo)
    {
        $this->activo = $activo;
    }

    public function query()
    {
        return DB::table('users')
            ->select(
                'users.id',
                'users.numero_empleado',
                'users.name',
                'users.apellido_paterno',
                'users.apellido_materno',
                'users.rfc',
                'users.curp',
                'users.email',
                'puestos.name AS puesto',
                'cecos.nombre  AS departamento',
                'users.fecha_nacimiento',
                'users.fecha_ingreso',
                'users.fecha_ingreso_real',
                'users.salario_bruto',
                'users.fotografia',
                'expedientes.ruta'
            )
            ->leftjoin('expedientes', 'expedientes.empleado_id', 'users.id')
            ->leftjoin('empleados_puestos', 'empleados_puestos.empleado_id', 'users.id')
            ->leftJoin('departamento_puestos as dp', 'dp.id', 'empleados_puestos.dpto_puesto_id')
            ->leftjoin('puestos', 'dp.puesto_id', 'puestos.id')
            ->leftjoin('cecos', 'dp.departamento_id', 'cecos.id')
            ->groupBy('users.id')
            ->where('users.activo', '=', $this->activo)
            ->orderBy('users.id');
    }

    public function headings(): array
    {
        return [
            "ID",
            "No.Empleado",
            "Nombre",
            "Apellido paterno",
            "Apellido materno",
            "RFC",
            "CURP",
            "email",
            "Puesto",
            'Departamento',
            'Fecha de nacimiento',
            'Fecha de ingreso',
            'Fecha de ingreso real',
            'Salario bruto',
            'Fotografía',
            'Documento'
        ];
    }
}
