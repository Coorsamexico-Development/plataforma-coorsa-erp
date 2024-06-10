<?php

namespace App\Http\Controllers;

use App\Models\DiasVacaciones;
use App\Models\User;
use App\Models\UserVacacions;
use Illuminate\Http\Request;
use App\Models\VariablesNomina;
use DateTime;
use Illuminate\Http\JsonResponse;

class VariablesNominaController extends Controller
{

    public function getVariablesNomina(Request $request)
    {
        $user = User::where('numero_empleado', $request->userId)->first();
        $fechaIngreso = new DateTime($user->fecha_ingreso_real);
        $fechaAtual = new DateTime(date('Y-m-d'));

        $mesIngreso=$fechaIngreso->format('m');

        $diferencia = $fechaIngreso->diff($fechaAtual);

        $vacaciones = DiasVacaciones::where([['año', $diferencia->y], ['activo', 1]])->first();

        return response()->json([
            'uma' => VariablesNomina::where([['activo', 1], ['tipo_id', 1]])->first()->valor,
            'aguinaldo' => VariablesNomina::where([['activo', 1], ['tipo_id', 2]])->first()->valor,
            'vacaciones' => $vacaciones->dias ?? 12,
            'deducible'  => VariablesNomina::where([['activo', 1], ['tipo_id', 3]])->first()->valor,
            'salarioMinimo'  => VariablesNomina::where([['activo', 1], ['tipo_id', 4]])->first()->valor,
            'mesIngreso' => $mesIngreso,
            'vacaciones' => $vacaciones,
            'fecha_ingreso' => $fechaIngreso,
            'fecha_actual' => $fechaAtual
        ]);
    }

    public function actualizarSalarios(): void
    {
        $users = User::where('activo', 1)->get();

        $uma = VariablesNomina::where([['activo', 1], ['tipo_id', 1]])->first()->valor;
        $aguinaldo = VariablesNomina::where([['activo', 1], ['tipo_id', 2]])->first()->valor;
        $deducible = VariablesNomina::where([['activo', 1], ['tipo_id', 3]])->first()->valor;
        $salarioMinimo = VariablesNomina::where([['activo', 1], ['tipo_id', 4]])->first()->valor;

        foreach ($users as $user) {
            
            $diasMes = 365 / 12;
            $valesDespensa = $diasMes * $uma * $deducible;

            $fechaIngreso = new DateTime($user->fecha_ingreso_real);
            $fechaAtual = new DateTime(date('Y-m-d'));
            if ($fechaAtual > $fechaIngreso)
                $diferencia = $fechaIngreso->diff($fechaAtual);
            else $diferencia = (object)['y' => 0];

            $diasTrabajados = $diferencia->days;
            $vacaciones = DiasVacaciones::where([['año', $diferencia->y], ['activo', 1]])->first()->dias ?? 12;
            $factor = (365 + $aguinaldo + $vacaciones * 0.25) / 365;
            $salarioMinimoMes = $salarioMinimo * $factor * $diasMes;

            //Calculos del salario
            $sueldoBruto = $user->salario_bruto ?? 0;
            $sueldoImss = ($sueldoBruto - $valesDespensa) / 1.22;
            $bonoPunt = $sueldoImss * 0.1;
            $fondoAhorro = $sueldoImss * 0.02;
            $fondoAhorroAnual=$fondoAhorro*12;

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

            $this->actualizarVacaciones($user, DiasVacaciones::where([['año', $diferencia->y], ['activo', 1]])->first());
        }
    }

    private function actualizarVacaciones($user, $vacaciones)
    {
        if ($vacaciones != null)
            if (!UserVacacions::where([['user_id', $user->id], ['dias_vacaciones_id', $vacaciones->id]])->exists())
                UserVacacions::create([
                    'user_id' => $user->id,
                    'dias_vacaciones_id' => $vacaciones->id,
                    'contador' => $vacaciones->dias,
                ]);
    }
}
  