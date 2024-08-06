<?php

namespace App\Http\Controllers;

use App\Exports\ReporteVacacionesExport;
use App\Models\SolicitudVacaciones;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class SolicitudVacacionsController extends Controller
{
    public function index()
    {
        return Inertia::render('RH/Vacaciones/VacacionesDash', [
            'nominas' => DB::table('nominas_empleados')->where('empleado_id', Auth::user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5)
        ]);
    }

    public function getDataCalendarVacaciones()
    {
        $cumpleaños = User::select(
            DB::raw('lower(concat("Cumpleaños ",users.name, " ", users.apellido_paterno, " ", users.apellido_materno)) as title'),
            'users.fecha_nacimiento'
        )
            ->where('users.activo', 1)
            ->whereNotNull('users.name')
            ->distinct()
            ->get();

        $vacaciones = User::select(
            DB::raw('lower(concat("vacaciones ",users.name, " ", users.apellido_paterno, " ", users.apellido_materno)) as title'),
            'sv.fechas',
        )
            ->join('solicitud_vacacions as sv', 'sv.user_id', 'users.id')
            ->where('sv.activo', 1)
            ->get();

        return response()->json([
            'cumpleaños' => $cumpleaños,
            'vacaciones' => $vacaciones
        ]);
    }

    public function reporteVacaciones(Request $request)
    {
        $mes = $request->month + 1;
        $mes = $mes < 10 ? "0{$mes}" : $mes;
        $año = $request->year;
        $newDate = "{$año}-{$mes}";

        return Excel::download(new ReporteVacacionesExport($newDate), "reporte-{$newDate}.xlsx");
    }
}