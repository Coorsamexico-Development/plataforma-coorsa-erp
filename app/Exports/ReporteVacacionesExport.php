<?php

namespace App\Exports;

use App\Models\DiasVacaciones;
use App\Models\User;
use App\Models\UserVacacions;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReporteVacacionesExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */


    public function __construct(public $date)
    {
    }

    public function collection()
    {
        $collection = [];
        $users = User::select(
            'users.id',
            'users.numero_empleado as nEmpleado',
            DB::raw('concat(users.name, " ", users.apellido_paterno, " ", users.apellido_materno) as fullName'),
            'sv.fechas',
            'sv.dias_solicitados',
        )
            ->join('solicitud_vacacions as sv', 'sv.user_id', 'users.id')
            ->where('sv.fechas', 'like', "%{$this->date}%")
            ->where([['users.activo', 1], ['sv.activo', 1]])->get();

        foreach ($users as $user) {
            $uv = UserVacacions::select(
                'user_vacacions.contador',
                'user_vacacions.dias_vacaciones_id as dv'
            )->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            $diasGozar = DiasVacaciones::select('*')->where('id', $uv->dv)->orderBy('created_at', 'desc')->first();

            array_push($collection, (object)[
                'id' => $user->nEmpleado,
                'fullName' => $user->fullName,
                'FechaInicio' => json_decode($user->fechas)[0],
                'FechaFin' => json_decode($user->fechas)[count(json_decode($user->fechas)) - 1],
                'DiasGozar' => $diasGozar->dias,
                'DiasPendientes' => $uv->contador,
                'DiasSolicitados' => $user->dias_solicitados,
            ]);
        }
        return collect($collection);
    }
    public function headings(): array
    {
        return [
            'Numero Empleado',
            'Nombre completo',
            'Fecha Inicio',
            'Fecha Fin',
            'Dias a Gozar',
            'Dias Pendientes',
            'Dias Solicitados'
        ];
    }
}