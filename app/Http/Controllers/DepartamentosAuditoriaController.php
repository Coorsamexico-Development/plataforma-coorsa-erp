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
use App\Models\DocumentosMe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DepartamentosAuditoria;
use Illuminate\Support\Facades\Storage;

class DepartamentosAuditoriaController extends Controller
{
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
            ->orderBy('cim.id')
            ->get();
        $añoMesT2 = CiDatas::select(
            'cia.año as año',
            'cim.mes as mes',
        )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [5, 6])
            ->orderBy('cia.año')
            ->orderBy('cim.id')
            ->get();

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
            ->orderBy('cim.id')
            ->get();

        $dataT2 =
            CiDatas::select(
                'cia.año as año',
                'cim.mes as mes',
                'ci_datas.value',
                'ci_datas.atributo_id as atributo'
            )
            ->join('ci_meses as cim', 'cim.id', 'ci_datas.mes_id')
            ->join('ci_años as cia', 'cia.id', 'ci_datas.año_id')
            ->whereIn('ci_datas.atributo_id', [5, 6])
            ->orderBy('cia.año')
            ->orderBy('cim.id')
            ->get();

        return response()->json([
            'añoMeses' => $añoMes->exists() ? $añoMes->groupBy(['año', 'mes']) : null,
            'añoMesesT2' => $añoMesT2->exists() ? $añoMesT2->groupBy(['año', 'mes']) : null,
            'atributos' => $atributos,
            'atributosT2' => $atributosT2,
            'data' => $data->exists() ? $data->groupBy('atributo') : null,
            'dataT2' => $dataT2->exists() ? $dataT2->groupBy('atributo') : null,
        ]);
    }

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
}
