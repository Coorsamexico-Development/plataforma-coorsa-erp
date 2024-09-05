<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserVacacions;
use App\Models\DiasVacaciones;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CronsController extends Controller
{
    public function addVacaciones(): void
    {
        try {
            DB::beginTransaction();
            $users = User::select(
                'users.id',
                'fecha_ingreso_real as fechaIngreso',
            )
                ->where('activo', 1)
                ->whereNotNull('fecha_ingreso_real')
                ->get();

            foreach ($users as $user) {
                $fechaIngreso = new DateTime($user->fechaIngreso);
                $fechaAtual = new DateTime(date('Y-m-d'));
                $diferencia = $fechaIngreso->diff($fechaAtual);
                $diferencia->y > 35 ? $diferencia->y = 35 : NULL;

                $diasVacaciones =  DiasVacaciones::where([['año', $diferencia->y > 0 ? $diferencia->y : 1], ['activo', 1]])->first();
                UserVacacions::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'dias_vacaciones_id' => $diasVacaciones->id,
                        'contador' => $diasVacaciones->dias,
                        'activo' => 0
                    ]
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function actualizarVacaciones(): void
    {
        $users = User::select(
            'users.id',
            'fecha_ingreso_real as fechaIngreso',
        )
            ->whereMonth('fecha_ingreso_real', date('m'))
            ->whereDay('fecha_ingreso_real', date('d'))
            ->where('activo', 1)
            ->get();

        foreach ($users as $user) {
            $fechaIngreso = new DateTime($user->fechaIngreso);
            $fechaAtual = new DateTime(date('Y-m-d'));
            $diferencia = $fechaIngreso->diff($fechaAtual);


            $diasVacaciones =  DiasVacaciones::where([['año', $diferencia->y > 0 ? $diferencia->y : 1], ['activo', 1]])->first();
            UserVacacions::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'dias_vacaciones_id' => $diasVacaciones->dias,
                    'contador' => $diasVacaciones->dias,
                    'activo' => 0
                ]
            );
        }
    }
}