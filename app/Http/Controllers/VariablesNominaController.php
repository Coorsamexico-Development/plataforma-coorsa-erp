<?php

namespace App\Http\Controllers;

use App\Models\DiasVacaciones;
use App\Models\User;
use App\Models\UserVacacions;
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

    public function actualizarSalarios(): void
    {
        $users = User::where('activo', 1)->get();

        $uma = VariablesNomina::where([['activo', 1], ['tipo_id', 1]])->first()->valor;
        $aguinaldo = VariablesNomina::where([['activo', 1], ['tipo_id', 2]])->first()->valor;
        $deducible = VariablesNomina::where([['activo', 1], ['tipo_id', 3]])->first()->valor;
        $salarioMinimo = VariablesNomina::where([['activo', 1], ['tipo_id', 4]])->first()->valor;
        $diasMes = 365 / 12;
        $valesDespensa = $diasMes * $uma * $deducible;

        foreach ($users as $user) {

            $fechaIngreso = new DateTime($user->fecha_ingreso_real);
            $fechaAtual = new DateTime(date('Y-m-d'));
            if ($fechaAtual > $fechaIngreso)
                $diferencia = $fechaIngreso->diff($fechaAtual);
            else $diferencia = (object)['y' => 0];


            $vacaciones = DiasVacaciones::where([['año', $diferencia->y], ['activo', 1]])->first()->dias ?? 12;
            $factor = (365 + $aguinaldo + $vacaciones * 0.25) / 365;
            $salarioMinimoMes = $salarioMinimo * $factor * $diasMes;

            //Calculos del salario
            $sueldoBruto = $user->salario_bruto ?? 0;
            $sueldoImss = ($sueldoBruto - $valesDespensa) / 1.22;
            $bonoPunt = $sueldoImss * 0.1;
            $fondoAhorro = $sueldoImss * 0.02;

            if ($salarioMinimoMes >= $sueldoImss) {
                $sueldoImss = $salarioMinimoMes;
                if ($sueldoBruto < $salarioMinimoMes)
                    $sueldoBruto = $salarioMinimoMes;
                $diff = $sueldoBruto - $sueldoImss < 0 ? 0 : $sueldoBruto - $sueldoImss;
                $bonoPunt = $diff / 3;
                $fondoAhorro = $diff / 3;
            }
            $salarioDiario = $sueldoImss / ($factor * $diasMes);
            $salarioDiarioInt = $salarioDiario * $factor;

            $user->update([
                'salario_diario' => round($salarioDiario, 2),
                'salario_bruto' => round($sueldoBruto, 2),
                'salario_imss' => round($sueldoImss, 2),
                'bono_puntualidad' => round($bonoPunt, 2),
                'bono_asistencia' => round($bonoPunt, 2),
                'despensa' => round($valesDespensa, 2),
                'fondo_ahorro' => round($fondoAhorro, 2),
            ]);
        }
    }
}