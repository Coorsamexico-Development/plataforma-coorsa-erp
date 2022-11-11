<?php

namespace App\Http\Controllers;

use App\Models\DepartamentosAuditoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DepartamentosAuditoriaController extends Controller
{
    public function index()
    {
        $archivos = null;
        $departamentosAuditoria = DepartamentosAuditoria::orderBy('id', 'asc');
        return Inertia::render('ControlInterno/DashboardAuditoria', [
            'departamentosAuditoria' => fn () => $departamentosAuditoria->get(),
            'filters' => request()->all(['departamento_auditoria_id']),
            'archivos' => fn () => $archivos,
        ]);
    }
    public function storeCalificacion(DepartamentosAuditoria $departamentosAuditoria, Request $request)
    {
        $request->validate([
            'calificacion' =>  ['required', 'numeric'],
            'mes' => ['required', 'date'],
            "documento" => ['nullable', 'mimes:png,jpg,pdf, jpeg'],
        ]);

        if ($request->has('documento')) {
        }
        $departamentosAuditoria->documentosCalificacionesMes()->updateOrCreate([

            'mes' => $request->mes,

        ], [
            'calificacion' => $request->calificacion,
            'user_id' => Auth::id(),
            'documento_url' => '',
            'fecha_creacion' => now(),
        ]);

        return redirect()->back();
    }
}
