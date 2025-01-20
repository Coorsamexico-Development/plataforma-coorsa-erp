<?php

namespace App\Http\Controllers;

use App\Events\SuaEvent;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Rubro;
use App\Models\Proceso;
use App\Models\CalfRubroMe;
use App\Models\CiAtributo;
use App\Models\CiAño;
use App\Models\CiDatas;
use App\Models\CiParametro;
use App\Models\CiParametroAtributo;
use App\Models\DocumentosMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DepartamentosAuditoria;
use Illuminate\Broadcasting\Channel;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;

use function PHPUnit\Framework\returnSelf;

class DepartamentosAuditoriaController extends Controller
{
    //Gets
    public function index()
    {
        return Inertia::render('ControlInterno/DashboardAuditoria', [
            'nominas' => DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5),
        ]);
    }

    public function dataSua()
    {
        $añoMes = CiDatas::select(
            'cia.año as año',
            'cim.mes as mes',
        )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [1, 2, 3, 4])
            ->orderBy('cia.año')
            ->orderBy('cim.id');

        $añoMesT2 = CiDatas::select(
            'cia.año as año',
            'cim.mes as mes',
        )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [5, 6])
            ->orderBy('cia.año')
            ->orderBy('cim.id');

        $atributos = CiAtributo::whereIn('id', [1, 2, 3, 4])->get();
        $atributosT2 = CiAtributo::whereIn('id', [5, 6])->get();

        $data =
            CiDatas::select(
                'cia.año as año',
                'cim.mes as mes',
                'ci_datas.value',
                'ci_datas.atributo_id as atributo'
            )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [1, 2, 3, 4])
            ->orderBy('cia.año')
            ->orderBy('cim.id');

        $dataT2 =
            CiDatas::select(
                'cia.año as año',
                'cim.id as mes',
                'ci_datas.value',
                'ci_datas.atributo_id as atributo'
            )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [5, 6])
            ->orderBy('cia.año')
            ->orderBy('cim.id');

        return response()->json([
            'añoMeses' => $añoMes->exists() ? $añoMes->get()->groupBy(['año', 'mes']) : null,
            'añoMesesT2' => $añoMesT2->exists() ? $añoMesT2->get()->groupBy(['año', 'mes']) : null,
            'atributos' => $atributos,
            'atributosT2' => $atributosT2,
            'data' => $data->exists() ? $data->get()->groupBy('atributo') : null,
            'dataT2' => $dataT2->exists() ? $dataT2->get()->groupBy('atributo') : null,
        ]);
    }

    public function dataNomina(Request $request)
    {
        $atributos = ["7", "8", "9", "10", "11", "12", "13", "14"];
        $parametros = ["1", "2"];

        return response()->json($this->getData($request, $atributos, $parametros, 2));
    }

    public function getGraphLineNomina($dataGraphLine)
    {
        $garphLine = (object)[];
        foreach ($dataGraphLine as $años => $values) {
            $garphLine->$años = (object)[];
            foreach ($values as $mes => $value) {
                $sumatoria = 0;
                foreach ($value as $data) {
                    $sumatoria += $data->value;
                    $garphLine->$años->$mes = (object)[
                        'value' => $sumatoria / count($value),
                        'mes' => $data->mes_id,
                        'año' => $data->año
                    ];
                }
            }
        }
        return $garphLine;
    }

    public function dataCXP(Request $request)
    {
        $atributos = [15, 16, 17, 18];
        $parametros = ["1", "2", "3"];
        return response()->json($this->getData($request, $atributos, $parametros, 3));
    }

    public function dataAltas(Request $request)
    {
        $atributos = [9, 19, 7, 20, 21, 22, 23, 24, 25, 26];
        $parametros = ["1", "2", "3"];

        return response()->json($this->getData($request, $atributos, $parametros, 4));
    }

    public function dataBajas(Request $request)
    {
        $atributos = [27, 28, 29, 30, 31, 32, 33, 34, 35, 48, 49];
        $parametros = ["1", "2", "3"];

        return response()->json($this->getData($request, $atributos, $parametros, 5));
    }

    public function dataCompras(Request $request)
    {
        $atributos = [36, 37, 38, 39, 40, 41, 42];
        $parametros =  ["1", "2"];

        return response()->json($this->getData($request, $atributos, $parametros, 6));
    }

    public function dataManiobras(Request $request)
    {
        $atributos = [43, 44, 45, 46, 47];
        $parametros = ["1", "2"];

        return response()->json($this->getData($request, $atributos, $parametros, 7));
    }

    //Posts
    public function dataEvolucionImss(Request $request): void
    {
        $request->validate([
            'fecha' => ['required'],
            'pago' => ['required'],
            'pagar' => ['required'],
            'diff' => ['required'],
            'incre' => ['required'],
        ]);

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        CiDatas::updateOrCreate(
            [
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => 1,
            ],
            [
                'value' => $request->pago
            ]
        );
        CiDatas::updateOrCreate([
            'año_id' => $año->id,
            'mes_id' => $mes,
            'atributo_id' => 2,
        ], [
            'value' => $request->pagar
        ]);
        CiDatas::updateOrCreate([
            'año_id' => $año->id,
            'mes_id' => $mes,
            'atributo_id' => 3,
        ], [
            'value' => $request->diff
        ]);
        CiDatas::updateOrCreate([
            'año_id' => $año->id,
            'mes_id' => $mes,
            'atributo_id' => 4,
        ], [
            'value' => $request->incre
        ]);

        event(new SuaEvent());
    }

    public function dataEvolucionColab(Request $request): void
    {
        $request->validate([
            'fecha' => ['required'],
            'colaboradores' => ['required'],
            'cotizado' => ['required'],
        ]);

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        CiDatas::updateOrCreate(
            [
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => 5,
            ],
            [
                'value' => $request->colaboradores
            ]
        );
        CiDatas::updateOrCreate([
            'año_id' => $año->id,
            'mes_id' => $mes,
            'atributo_id' => 6,
        ], [
            'value' => $request->cotizado
        ]);

        event(new SuaEvent());
    }

    public function addNomina(Request $request): void
    {
        $request->validate([
            'fecha' => ['required'],
            'nombre.*' => ['required'],
            'nss.*' => ['required'],
            'rfc.*' => ['required'],
            'ingreso.*' => ['required'],
            'puesto.*' => ['required'],
            'infonavit.*' => ['required'],
            'fonacot.*' => ['required'],
            'bancos.*' => ['required'],
        ]);

        $values = [$request->nombre, $request->nss, $request->rfc, $request->ingreso, $request->puesto, $request->infonavit, $request->fonacot, $request->bancos];
        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 2,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 2,
            ], [
                'value' => $value->riesgo,
            ]);
        }

        event(new SuaEvent());
    }

    public function addCXP(Request $request): void
    {
        $request->validate([
            'fecha' => ['required'],
            'SAPP.*' => ['required'],
            'FSVFP.*' => ['required'],
            'FPVFP.*' => ['required'],
            'GAP.*' => ['required'],
        ]);


        $values = [$request->SAPP, $request->FSVFP, $request->FPVFP, $request->GAP];
        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 3,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 3,
            ], [
                'value' => $value->riesgo,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 3,
                'seccion_id' => 3,
            ], [
                'value' => $value->calificacion,
            ]);
        }
        event(new SuaEvent);
    }

    public function addAltas(Request $request)
    {
        $request->validate([
            'fecha' => ['required'],
            'nombre.*' => ['required'],
            'rfc.*' => ['required'],
            'curp.*' => ['required'],
            'alta.*' => ['required'],
            'envTyF.*' => ['required'],
            'RnPRH.*' => ['required'],
            'DocCorr.*' => ['required'],
            'ClasRies.*' => ['required'],
            'FormAuth.*' => ['required'],
            'CFDI.*' => ['required'],
        ]);

        $values = [$request->nombre, $request->rfc, $request->curp, $request->alta, $request->envTyF, $request->RnPRH, $request->DocCorr, $request->ClasRies, $request->FormAuth, $request->CFDI];

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 4,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 4,
            ], [
                'value' => $value->riesgo,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 3,
                'seccion_id' => 4,
            ], [
                'value' => $value->calificacion,
            ]);
        }
        event(new SuaEvent);
    }

    public function addBajas(Request $request)
    {
        $request->validate([
            'fecha' => ['required'],
            'nombre.*' => ['required'],
            'rfc.*' => ['required'],
            'curp.*' => ['required'],
            'alta.*' => ['required'],
            'envTyF.*' => ['required'],
            'RnPRH.*' => ['required'],
            'DocCorr.*' => ['required'],
            'ClasRies.*' => ['required'],
            'FormAuth.*' => ['required'],
            'FormBajasEv.*' => ['required'],
            'correoBajasEv.*' => ['required'],
        ]);

        $values = [$request->nombre, $request->rfc, $request->curp, $request->alta, $request->envTyF, $request->RnPRH, $request->DocCorr, $request->ClasRies, $request->FormAuth, $request->FormBajasEv, $request->correoBajasEv];

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 5,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 5,
            ], [
                'value' => $value->riesgo,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 3,
                'seccion_id' => 5,
            ], [
                'value' => $value->calificacion,
            ]);
        }
        event(new SuaEvent);
    }

    public function addCompras(Request $request)
    {
        $request->validate([
            'fecha' => ['required'],
            'reqComp.*' => ['required'],
            'timeResp.*' => ['required'],
            'cotizacion.*' => ['required'],
            'authCompra.*' => ['required'],
            'contrarecibo.*' => ['required'],
            'gastDiv.*' => ['required'],
            'cumplimiento.*' => ['required'],
        ]);

        $values = [$request->reqComp, $request->timeResp, $request->cotizacion, $request->authCompra, $request->contrarecibo, $request->gastDiv, $request->cumplimiento];

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 6,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 6,
            ], [
                'value' => $value->riesgo,
            ]);
        }
        event(new SuaEvent);
    }

    public function addManiobra(Request $request)
    {
        $request->validate([
            'fecha' => ['required'],
            'formReq.*' => ['required'],
            'VoBo.*' => ['required'],
            'cuenta.*' => ['required'],
            'ventas.*' => ['required'],
            'pago.*' => ['required'],
        ]);

        $values = [$request->formReq, $request->VoBo, $request->cuenta, $request->ventas, $request->pago];

        $mes = explode("-", $request->fecha)[0];
        $año = CiAño::where('año', explode("-", $request->fecha)[1])->first();

        foreach ($values as $value) {
            $value = (object) $value;
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 1,
                'seccion_id' => 7,
            ], [
                'value' => $value->porcentaje,
            ]);
            CiParametroAtributo::updateOrCreate([
                'año_id' => $año->id,
                'mes_id' => $mes,
                'atributo_id' => $value->id,
                'parametro_id' => 2,
                'seccion_id' => 7,
            ], [
                'value' => $value->riesgo,
            ]);
        }
        event(new SuaEvent);
    }

    //Privates 
    private function getData($request, $atributos, $params, $seccion)
    {
        $atributos = CiAtributo::whereIn('id', $atributos)->get();
        $parametros = CiParametro::whereIn('id', $params)->get();
        $mes = null;
        $año = null;
        $dataRadar = 5;
        $data = null;
        $dataPorcentaje = 0;

        if ($request->date !== null) {
            $mes = explode('-', $request->date)[0];
            $año = explode('-', $request->date)[1];
        }

        $paramsFecha = CiParametroAtributo::select(
            'cim.id as mes',
            'cia.id as año',
            'cia.año as añoTotal'
        )
            ->join('ci_meses as cim', 'cim.id', 'ci_parametro_atributos.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_parametro_atributos.año_id')
            ->orderBy('cia.año', 'desc')
            ->orderBy('cim.id', 'desc')
            ->where('seccion_id', $seccion)
            ->where(function ($query) use ($request, $mes, $año) {
                if ($request->date !== null)
                    $query->where([['cim.id', $mes], ['cia.año', $año]]);
            });

        if ($paramsFecha->exists()) {
            $paramsFecha = $paramsFecha->first();
            $data = CiParametroAtributo::select(
                'atributo_id as atributo',
                'parametro_id as parametro',
                'value',
            )
                ->where([
                    ['seccion_id', $seccion],
                    ['mes_id', $paramsFecha->mes],
                    ['año_id', $paramsFecha->año],
                ]);
            $dataRadar = CiParametroAtributo::select(
                DB::raw('sum(value) as riesgo'),
            )
                ->where([
                    ['seccion_id', $seccion],
                    ['mes_id', $paramsFecha->mes],
                    ['año_id', $paramsFecha->año],
                    ['parametro_id', 2]
                ])
                ->groupBy('parametro_id')
                ->first();
            $dataRadar = $dataRadar->riesgo != 0 ? $dataRadar->riesgo / count($atributos) : 5;

            $dataPorcentaje = CiParametroAtributo::select(
                DB::raw('sum(value) as porcentaje'),
            )
                ->where([
                    ['seccion_id', $seccion],
                    ['mes_id', $paramsFecha->mes],
                    ['año_id', $paramsFecha->año],
                    ['parametro_id', 1]
                ])
                ->groupBy('parametro_id')
                ->first();
            $dataPorcentaje = $dataPorcentaje->porcentaje != 0 ? $dataPorcentaje->porcentaje / count($atributos) : 0;
        }

        $dataGraphLine = CiParametroAtributo::select(
            'value',
            'cim.id as mes_id',
            'cim.mes as mes',
            'cia.año as año'

        )
            ->join('ci_meses as cim', 'cim.id', 'ci_parametro_atributos.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_parametro_atributos.año_id')
            ->orderBy('cia.año', 'desc')
            ->orderBy('cim.id', 'desc')
            ->where([
                ['seccion_id', $seccion],
                ['parametro_id', 1]
            ])
            ->get()
            ->groupBy(['año', 'mes']);

        $garphLine = $this->getGraphLineNomina($dataGraphLine);

        return [
            'atributos' => $atributos,
            'parametros' => $parametros,
            'data' => $data != null ? $data->get()->groupBy('atributo') : [],
            'garphLine' => $garphLine,
            'dataRadar' => $dataRadar,
            'dataPorcentaje' => $dataPorcentaje,
            'paramsFecha' => $paramsFecha->exists() ? "{$paramsFecha->mes}-{$paramsFecha->añoTotal}" : $request->date
        ];
    }
}
