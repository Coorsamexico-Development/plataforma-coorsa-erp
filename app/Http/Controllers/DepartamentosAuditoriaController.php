<?php

namespace App\Http\Controllers;

use App\Models\DepartamentosAuditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartamentosAuditoriaController extends Controller
{
    public function index()
    {
        $departamentosAuditoria = DepartamentosAuditoria::orderBy('id', 'asc');
        return Inertia::render('ControlInterno/DashboardAuditoria', [
            'departamentosAuditoria' => fn () => $departamentosAuditoria->get()
        ]);
    }
}
