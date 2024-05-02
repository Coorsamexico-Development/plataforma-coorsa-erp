<?php

namespace App\Http\Controllers;

use App\Models\DiasVacaciones;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\VariablesNomina;
use DateTime;

class VariablesNominaController extends Controller
{
    public function getVariablesNomina(Request $request)
    {
        $user = User::where('numero_empleado', $request->userId)->first();
        $fechaIngreso = new DateTime($user->fecha_ingreso_real);
        $fechaAtual = new DateTime(date('Y-m-d'));

        $diferencia = $fechaIngreso->diff($fechaAtual);

        $vacaciones = DiasVacaciones::where([['año', $diferencia->y], ['activo', 1]])->first();

        return response()->json([
            'uma' => VariablesNomina::where([['activo', 1], ['tipo_id', 1]])->first()->valor,
            'aguinaldo' => VariablesNomina::where([['activo', 1], ['tipo_id', 2]])->first()->valor,
            'vacaciones' => $vacaciones->dias ?? 12,
            'deducible'  => VariablesNomina::where([['activo', 1], ['tipo_id', 3]])->first()->valor,
            'salarioMinimo'  => VariablesNomina::where([['activo', 1], ['tipo_id', 4]])->first()->valor,
        ]);
    }
}
