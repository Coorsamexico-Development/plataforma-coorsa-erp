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
                            ->orWhere('id', 'LIKE', "%{$searchQuery}%");
                    })
                    ->where('activo', 1)
                    ->get();

        return  response()->json($users);
    }

    public function index(){
        return Inertia::render('ControlNominas/NominasIndex');
    }

    public function deducciones($id){
        return Inertia::render('ControlNominas/Deducciones', ['id' => $id]);
    }

    public function dibujarTableroRetardos($fechaAnterior, $fechaActual){
        
    }
}
