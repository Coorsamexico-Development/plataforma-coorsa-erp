<?php

namespace App\Http\Controllers;

use App\Models\SolicitudVacaciones;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->get();

        $vacaciones = User::select(
            DB::raw('lower(concat("vacaciones ",users.name, " ", users.apellido_paterno, " ", users.apellido_materno)) as title'),
            'sv.fechas'
        )
            ->join('solicitud_vacacions as sv', 'sv.user_id', 'users.id')
            ->where('sv.activo', 1)
            ->get();

        return response()->json([
            'cumpleaños' => $cumpleaños,
            'vacaciones' => $vacaciones
        ]);
    }
}
