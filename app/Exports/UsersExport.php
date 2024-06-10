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

    protected $activo;

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
                'users.nss',
                'users.email',
                'puestos.name AS puesto',
                'cecos.nombre  AS departamento',
                'users.fecha_nacimiento',
                'users.fecha_ingreso',
                'users.fecha_ingreso_real',
                'users.salario_bruto',
                'users.fotografia',
                'expedientes.ruta',
                'direcciones.codigo_postal',
                'de.nombre as estado',
                'dm.nombre as ciudad',
                'direcciones.numero',
                'direcciones.calle',
                'direcciones.colonia',
                DB::raw('concat(cecos.nombre, " ", cecos.descripcion) as ceco'),
                DB::raw('IF(users.activo = 0,users.updated_at,"")'),
                'bancos.nombre',
                'users.clave_bancaria',
                'users.numero_cuenta_bancaria',
                'users.telefono',
                'cg.nombre as genero'
            )
            ->leftjoin('expedientes', 'expedientes.empleado_id', 'users.id')
            ->leftjoin('empleados_puestos', 'empleados_puestos.empleado_id', 'users.id')
            ->leftJoin('departamento_puestos as dp', 'dp.id', 'empleados_puestos.dpto_puesto_id')
            ->leftjoin('puestos', 'dp.puesto_id', 'puestos.id')
            ->leftjoin('cecos', 'dp.departamento_id', 'cecos.id')
            ->leftJoin('direcciones', 'direcciones.id', 'users.direccion_id')
            ->leftJoin('direccion_localidades as dl', 'dl.id', 'direcciones.direccion_localidade_id')
            ->leftJoin('direccion_municipios as dm', 'dm.id', 'dl.direccion_municipio_id')
            ->leftJoin('direccion_estados as de', 'de.id', 'dm.direccion_estado_id')
            ->leftJoin('bancos', 'bancos.id', 'users.banco_id')
            ->leftJoin('cat_generos as cg', 'cg.id', 'users.cat_genero_id')
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
            "NSS",
            "email",
            "Puesto",
            'Departamento',
            'Fecha de nacimiento',
            'Fecha de ingreso',
            'Fecha de ingreso real',
            'Salario mensual',
            'Fotografía',
            'Documento',
            'CP',
            'Estado',
            'Ciudad',
            'Colonia',
            'Calle',
            'Numero',
            'Ceco',
            $this->activo === 0 ? 'Fecha Baja' : null,
            'Banco',
            'CLABE Bancaria',
            'Numero de Cuenta',
            'Telefono',
            'Genero'
        ];
    }
}
