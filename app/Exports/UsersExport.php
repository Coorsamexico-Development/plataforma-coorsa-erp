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
            'id',
            'numero_empleado',
            'name',
            'apellido_paterno',
            'apellido_materno',
            'email',
            'fecha_nacimiento',
            'fecha_ingreso',
            'fecha_ingreso_real',
            'salario_bruto'
            )
        ->where('activo','=',$this->activo)
        ->orderBy('users.id');
    }

    public function headings(): array
    {
        return ["ID", "No.Empleado", "Nombre", "Apellido paterno", "Apellido materno", "email", 'Fecha de nacimiento', 'Fecha de ingreso', 'Fecha de ingreso real','Salario bruto'];
    }
}
