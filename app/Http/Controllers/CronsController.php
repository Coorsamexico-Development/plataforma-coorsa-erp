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
    /**
     * Agrega días de vacaciones a los usuarios activos basados en su fecha de ingreso.
     *
     * Este método selecciona a todos los usuarios activos con una fecha de ingreso válida,
     * calcula los años de diferencia entre la fecha de ingreso y la fecha actual, y asigna
     * días de vacaciones según la cantidad de años trabajados, con un máximo de 35 años.
     * Luego, actualiza o crea el registro de vacaciones del usuario en la base de datos.
     *
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito o error.
     */
    public function addVacaciones(): \Illuminate\Http\JsonResponse
    {
        set_time_limit(0);
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
                        'activo' => 1
                    ]
                );
            }
            DB::commit();
            return response()->json(['message' => 'Vacaciones agregadas'], 200);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Actualiza las vacaciones de los usuarios que cumplen aniversario de ingreso en la fecha actual.
     *
     * Este método selecciona a los usuarios que cumplen aniversario de ingreso en la fecha actual y actualiza
     * sus días de vacaciones según los años trabajados. La operación se realiza dentro de una transacción
     * para asegurar la consistencia de los datos.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function actualizarVacaciones(): \Illuminate\Http\JsonResponse
    {
        try {
            DB::beginTransaction();
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
                        'activo' => 1
                    ]
                );
            }
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
