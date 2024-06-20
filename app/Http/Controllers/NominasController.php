<?php
namespace App\Http\Controllers;
use App\Models\DiasVacaciones;
use App\Models\User;
use App\Models\UserVacacions;
use App\Models\VariablesNomina;
use App\Models\ISR;
use Inertia\Inertia;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class NominasController extends Controller
{
    public function search(Request $request){
        $searchQuery = $request->input('search');

        $users = User::where(function($query) use ($searchQuery) {
                        $query->where('name', 'LIKE', "%{$searchQuery}%")
                            ->orWhere('numero_empleado', 'LIKE', "%{$searchQuery}%");
                    })
                    ->where('activo', 1)
                    ->get();

        return  response()->json($users);
    }

    public function index(){
        return Inertia::render('ControlNominas/NominasIndex');
    }

    public function deducciones($id){
        //Mostrar informacion del usuario
        $usuario = User::where('id', $id)
                ->where('activo', 1)
                ->first();

        return Inertia::render('ControlNominas/MenuDeducciones', ['usuario' => $usuario]);
    }

    public function dibujarTableroRetardos(Request $request){
        
        $fechaAnterior = Carbon::parse($request->fechaAnterior);
        $fechaActual = Carbon::parse($request->fechaActual);

        $dias = [];
        for ($date = $fechaAnterior; $date->lte($fechaActual); $date->addDay()) {
            $dias[] = $date->format('Y-m-d');
        }

        return  response()->json([
            'dias' => $dias
        ]);
    }


    public function procesarRetardos(Request $request){
        $id = $request->id;

        $usuario = User::where('id', $id)
                ->where('activo', 1)
                ->first();

        $salarioBruto = $usuario->salario_bruto;
        $unaHora = ($salarioBruto/(365/12)) / 8;

        $R1 = $request->conteoRetardos['R1'];
        $R2 = $request->conteoRetardos['R2'];
        $R3 = $request->conteoRetardos['R3'];
        $F = $request->conteoRetardos['F'];

        $falta = $unaHora * 8;
        $descuentoFaltas= $F * $falta;

        $subR1 = round($R1*($unaHora),2);
        $subR2 = round($R2 *($unaHora*2),2);
        $subR3 = round($R3*($unaHora*3),2);
        $subF = round($descuentoFaltas,2);

        $descuentoTotal = $subR1 + $subR2 + $subR3 + $subF;

        return response()->json([
            'retardos' => [$R1,$R2,$R3,$F],
            'subtotales' => [$subR1,$subR2,$subR3,$subF],
            'id' => $id,
            'descuento_total' => round($descuentoTotal,2),
            'una_hora' => round($unaHora,2)
        ]);
    }

    public function procesarComedor(Request $request){

        $selecciones = $request->selecciones;
        
        $contador = count(array_filter($selecciones));
        $totalConsumo = $contador * 35;


        return  response()->json([
            'selecciones' => $selecciones,
            'contador' => $contador,
            'total_consumo' => $totalConsumo
        ]);
    }

    public function procesarFaltas(Request $request){

        $id_usuario = $request->idUsuario;
        $selecciones = intval($request->selecciones);

        $usuario = User::where('id', $id_usuario)
                ->where('activo', 1)
                ->first();
        $salarioBruto= $usuario->salario_bruto;
        
        $unaHora = ($salarioBruto/(365/12)) / 8;

        //Falta equivalente a 8 horas de trabajo
        $falta = $unaHora * 8;
        $descuentoFaltas= $selecciones * $falta;


        return response()->json([
            'id_usuario' => $id_usuario,
            'usuario' => $usuario,
            'selecciones' => $selecciones,
            'descuento_faltas' => round($descuentoFaltas,2)
        ]);


    }
}




