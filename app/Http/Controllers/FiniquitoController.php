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

class FiniquitoController extends Controller
{
    public function calcularFiniquito(Request $request)
    {
        // Obtener el usuario directamente del query en la solicitud
        $query = $request->input('query');
        $fecha = $request->input('fecha');

        $usuario = User::where('name', 'like', "%{$query}%")
                ->orWhere('numero_empleado', $query)
                ->where('activo', 1)
                ->first();

        $user_id = $usuario->id;

        //Calcular Aguinaldo Neto
        $isrMensual = $this->isr($usuario);
        $propAguinaldo = round($this->calcularAguinaldoProporcional($usuario,$fecha),2);
        $salarioMensual = $usuario->salario_imss;
        $isrGravable = $this->calcularIsrAguinaldo($salarioMensual,$propAguinaldo);
        $isrAguinaldo = $isrGravable - $isrMensual;

        $vacaciones = UserVacacions::where('user_id', $user_id)->value('contador');

        //Total mesesRestantes 
        $vacacionesRestantes = 0;
        $mesesRestantes = 0;
        $vacacionesNoTomadas = 0;
        $vacacionesProporcionales = round($this->calcularVacaciones($usuario, $fecha, $vacacionesRestantes, $mesesRestantes, $vacacionesNoTomadas), 2);

        $aguinaldoNeto = $propAguinaldo -$isrAguinaldo;

        // Calcular Prima Vacacional Neta
        $propPrimaVacacional = $this->calcularPrimaVacacional($usuario);
        $isrPrima = $this->calcularIsrPrimaVacacional($propPrimaVacacional);

        $primaVacacionalNeta = $propPrimaVacacional - $isrPrima;

        // Realizar los cálculos para el finiquito
        $finiquito = [
            'aguinaldo_proporcional' => round($this->calcularAguinaldoProporcional($usuario,$fecha),2),
            'dias_vacaciones' => $vacaciones,
            'prima_vacacional' => round($this->calcularPrimaVacacional($usuario),2),
            'isr_prima' => round($isrPrima,2),
            'prima_vacacional_neta' => round($primaVacacionalNeta,2),
            'fondo_ahorro' => round($this->calcularFondoAhorro($usuario,$fecha),2),
            'fecha_baja' => $fecha,
            'isr_aguinaldo' => round($isrAguinaldo,2),
            'aguinaldo_neto' => round($aguinaldoNeto,2),
            'vacaciones' => round($this->calcularVacaciones($usuario,$fecha),2),
            'vacaciones_no_tomadas' => round($vacacionesNoTomadas,2),
            'periodo_vacaciones' => round($vacacionesRestantes,2),
            'meses_restantes' => $mesesRestantes,
        ];

        // Filtrar los valores que no quieres incluir en el total
        $valoresIncluir = array_filter($finiquito, function($key) {
            return !in_array($key, ['isr_aguinaldo', 'aguinaldo_proporcional', 'fecha_baja','isr_prima','prima_vacacional', 'periodo_vacaciones','dias_vacaciones','meses_restantes', 'vacaciones_no_tomadas']);
        }, ARRAY_FILTER_USE_KEY);

        // Calcular el total del finiquito
        $finiquito['total'] = round(array_sum($valoresIncluir), 2);


        return Inertia::render('Finiquito/FiniquitoIndex', [
            'usuario' => $usuario,
            'finiquito' => $finiquito,
        ])->toResponse($request);
    }

    public function calcularAguinaldoProporcional($usuario,$fecha)
    {   
        $user_id=$usuario->id;
        $aguinaldo = VariablesNomina::where([['activo', 1], ['tipo_id', 2]])->first()->valor;
        
        
        $vacaciones = UserVacacions::where('user_id', $user_id)->value('contador');

        $aguinaldoXmes = $aguinaldo / 12;
        $aguinaldoXdia = $aguinaldoXmes / 30;

        if($fecha){

            $fechaIngreso = Carbon::createFromFormat('Y-m-d', $usuario->fecha_ingreso_real);
            $fechaBaja = Carbon::createFromFormat('Y-m-d', $fecha);
            $estanciaEmpresa=$fechaIngreso->diffInYears($fechaBaja);
            $salarioDiario = $usuario->salario_diario;
            
            if($estanciaEmpresa >= 1){
                $fechaCorte = Carbon::create($fechaBaja->year, 1, 1);

                $mesesTrabajados = $fechaCorte->diffInMonths($fechaBaja);

                $fechaInicioPlusMonths = $fechaCorte->copy()->addMonths($mesesTrabajados);
                $diasTrabajados = $fechaInicioPlusMonths->diffInDays($fechaBaja);
                
                $propAguinaldo = (($mesesTrabajados * $aguinaldoXmes) + ($diasTrabajados * $aguinaldoXdia)) * $salarioDiario;
                    
                return $propAguinaldo; 
            }else {

                if($fechaIngreso->year == $fechaBaja->year){
                    $mesesTrabajados = $fechaIngreso->diffInMonths($fechaBaja);
                    $fechaInicioPlusMonths = $fechaIngreso->copy()->addMonths($mesesTrabajados);
                    $diasTrabajados = $fechaInicioPlusMonths->diffInDays($fechaBaja);
              
                    $propAguinaldo = (($mesesTrabajados * $aguinaldoXmes) + ($diasTrabajados * $aguinaldoXdia)) * $salarioDiario;

                    return $propAguinaldo; 
                }else{
                    $fechaCorte = Carbon::create($fechaBaja->year, 1, 1);
                    $mesesTrabajados = $fechaCorte->diffInMonths($fechaBaja);
                    $fechaInicioPlusMonths = $fechaCorte->copy()->addMonths($mesesTrabajados);
                    $diasTrabajados = $fechaInicioPlusMonths->diffInDays($fechaBaja);

                    $propAguinaldo = (($mesesTrabajados * $aguinaldoXmes) + ($diasTrabajados * $aguinaldoXdia)) * $salarioDiario;

                    return $propAguinaldo; 
                }   
            }
                
        }     
    }

    public function calcularIsrAguinaldo($salarioMensual,$propAguinaldo){

        if(!$propAguinaldo){
            return round(0,2);
        }

        $baseGravable = $salarioMensual + $propAguinaldo;


        $isr= ISR::where('limite_inferior', '<=', $baseGravable)
                  ->where('limite_superior', '>=', $baseGravable)
                  ->where('activos',1)
                  ->first();

        $porcentajeExcedenteDecimal = $isr->porcentaje_excedente / 100;
        
        $isrAguinaldo = ($baseGravable - $isr->limite_inferior) * $porcentajeExcedenteDecimal + $isr->cuota_fija;

        return $isrAguinaldo;   
    }

    public function calcularVacaciones($usuario, $fecha, &$vacacionesRestantes = 0, &$mesesRestantes = 0, &$vacacionesNoTomadas = 0){
        $user_id = $usuario->id;

        if($fecha){
            $fechaIngreso = Carbon::createFromFormat('Y-m-d',$usuario->fecha_ingreso_real);
            $fechaBaja = Carbon::createFromFormat('Y-m-d', $fecha);
            $fechaActual = Carbon::now();
            $estanciaEmpresa=$fechaIngreso->diffInYears($fechaActual);

            $salarioDiario = $usuario->salario_diario;

            $vacaciones = UserVacacions::where('user_id', $user_id)->value('contador');

            $fechaPreisncripcion = Carbon::create($fechaBaja->year, $fechaIngreso->month, $fechaIngreso->day + 1);
            $fechaPreinscripcionAnterior=Carbon::create($fechaPreisncripcion->year-1,$fechaPreisncripcion->month,$fechaPreisncripcion->day);

            if($estanciaEmpresa>=1){
                if($fechaBaja > $fechaPreisncripcion){
                    $mesesRestantes=$fechaPreisncripcion->diffInMonths($fechaBaja);

                    $totalMesesRestantes=($vacaciones/12)*$mesesRestantes;

                    $diasVacaciones = $vacaciones + $totalMesesRestantes;

                    $vacacionesNoTomadas = $vacaciones * $salarioDiario;

                    $vacacionesRestantes = $totalMesesRestantes * $salarioDiario;

                    $propVacaciones = $vacacionesNoTomadas + $vacacionesRestantes;
                    
                    return $propVacaciones;
                }else{
                    $mesesRestantes=$fechaPreinscripcionAnterior->diffInMonths($fechaBaja);

                    $totalMesesRestantes=($vacaciones/12)*$mesesRestantes;

                    $diasVacaciones = $vacaciones + $totalMesesRestantes;

                    $vacacionesNoTomadas = $vacaciones * $salarioDiario;

                    $vacacionesRestantes = $totalMesesRestantes * $salarioDiario;

                    $propVacaciones = $vacacionesNoTomadas + $vacacionesRestantes;

                    return $propVacaciones;
                }

            }else{
                $mesesRestantes = $fechaIngreso->diffInMonths($fechaBaja);

                $vacacionesRestantes = $mesesRestantes * $salarioDiario;

                $propVacaciones = $vacacionesRestantes;
                
                return $propVacaciones;
            }
        }
    }

    public function calcularPrimaVacacional($usuario)
    {

        $user_id=$usuario->id;
        $vacaciones = UserVacacions::where('user_id', $user_id)->value('contador');
        $sueldoImss=$usuario->salario_imss;
        $salarioDiario=$usuario->salario_diario;

        $propPrimaVacacional = ($salarioDiario * $vacaciones) * 0.25;   

        return $propPrimaVacacional;
    
    }

    public function calcularIsrPrimaVacacional($propPrimaVacacional){

        $uma = VariablesNomina::where([['activo', 1], ['tipo_id', 1]])->first()->valor;

        if($uma*15>$propPrimaVacacional){
            return $isrPrima = 0.00;
        }else{
            $excedente = $propPrimaVacacional - $uma;

            $isr = ISR::where('limite_inferior', '<=', $excedente)
                  ->where('limite_superior', '>=', $excedente)
                  ->where('activos',1)
                  ->first();

            $porcentajeExcedenteDecimal = $isr->porcentaje_excedente / 100;

            $isrPrima = ($excedente - $isr->limite_inferior) * $porcentajeExcedenteDecimal + $isr->cuota_fija;

            return $isrPrima;

        }
    }

    
    public function calcularFondoAhorro($usuario,$fecha)
    {

        if($fecha){
            $fondoAhorro = $usuario->fondo_ahorro;

            $fechaIngreso = Carbon::createFromFormat('Y-m-d', $usuario->fecha_ingreso_real);
            $fechaBaja = Carbon::createFromFormat('Y-m-d', $fecha);
            $fechaActual = Carbon::now();
            $estanciaEmpresa=$fechaIngreso->diffInYears($fechaActual);
            $diaSalida = $fechaBaja->day;

            if($estanciaEmpresa >= 1){
                $fechaCorte = Carbon::create($fechaBaja->year, 1, 1);
                $mesesTrabajados = $fechaCorte->diffInMonths($fechaBaja);

                if ($diaSalida <= 15 && $diaSalida >= 1) {
                
                    $propFondoAhorro = (($mesesTrabajados) * $fondoAhorro) - $fondoAhorro / 2;
    
                    return $propFondoAhorro;
    
                } else if ($diaSalida <= 31 && $diaSalida > 15) {
                    
                    $propFondoAhorro = ($mesesTrabajados + 1) * $fondoAhorro;
    
                    return $propFondoAhorro;
                }

            }else{
                if($fechaIngreso->year == $fechaBaja->year){
                    $mesesTrabajados = $fechaIngreso->diffInMonths($fechaBaja);

                    if ($diaSalida <= 15 && $diaSalida >= 1) {
                
                        $propFondoAhorro = (($mesesTrabajados) * $fondoAhorro) - $fondoAhorro / 2;
        
                        return $propFondoAhorro;
        
                    } else if ($diaSalida <= 31 && $diaSalida > 15) {
                        
                        $propFondoAhorro = ($mesesTrabajados + 1) * $fondoAhorro;
        
                        return $propFondoAhorro;
                    }

                }else{
                    $fechaCorte = Carbon::create($fechaBaja->year, 1, 1);
                    
                    $mesesTrabajados = $fechaCorte->diffInMonths($fechaBaja);

                    if ($diaSalida <= 15 && $diaSalida >= 1) {
                    
                        $propFondoAhorro = (($mesesTrabajados) * $fondoAhorro) - $fondoAhorro / 2;
        
                        return $propFondoAhorro;
        
                    } else if ($diaSalida <= 31 && $diaSalida > 15) {
                        
                        $propFondoAhorro = ($mesesTrabajados + 1) * $fondoAhorro;
        
                        return $propFondoAhorro;
                    }
                }
            }
            

            
           
            
                       
        }
        
    }


    public function isr($usuario){

        $sueldoImss = $usuario->salario_imss;

        $isr = ISR::where('limite_inferior', '<=', $sueldoImss)
                  ->where('limite_superior', '>=', $sueldoImss)
                  ->where('activos',1)
                  ->first();

        $porcentajeExcedenteDecimal = $isr->porcentaje_excedente / 100;

        $isrCalculado = ($sueldoImss - $isr->limite_inferior) * $porcentajeExcedenteDecimal + $isr->cuota_fija;

        return $isrCalculado;
    }

}



