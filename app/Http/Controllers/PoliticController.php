<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Politic;
use App\Models\Tipopolitica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PoliticController extends Controller
{
    public function index()
    {
        $tipoPoliticas = Tipopolitica::orderBy('id', 'asc')->where('seccion_id', '=', 1);
        $politicas = Politic::select(
            'politics.*',
            'users.name AS nombre',
            'users.apellido_paterno AS apellido_paterno',
            'users.apellido_materno AS apellido_materno',
            'tipopoliticas.color'
        )
            ->join('users', 'politics.empleado_id', 'users.id')
            ->join('tipopoliticas', 'politics.type_politic', 'tipopoliticas.id')
            ->where('id_statu', '=', 1)
            ->where('tipopoliticas.seccion_id', '=', 1);


        if (request()->has('search')) {
            $search = '%' . strtr(request('search'), array("'" => "\\'", "%" => "\\%")) . '%';
            $politicas->where('politics.namepolitica', 'like', $search);
        }
        if (request()->has('type_politic')) {
            $politicas->where('politics.type_politic', '=', request('type_politic'));
        }

        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        return Inertia::render('ControlInterno/PoliticsIndex', [
            'tipoPoliticas' => fn () => $tipoPoliticas->get(),
            'politicas' =>  fn () => $politicas->get(),
            'filters' => fn () => request()->all(['search', 'type_politic']),
            'nominas' => $nominas
        ]);
    }


    public function docsinternos($seccion) // es solo docs internos
    {
        $tipoPoliticas = Tipopolitica::orderBy('id', 'asc')->where('seccion_id', '=', $seccion);
        $politicas = Politic::select(
            'politics.*',
            'users.name AS nombre',
            'users.apellido_paterno AS apellido_paterno',
            'users.apellido_materno AS apellido_materno',
            'tipopoliticas.color'
        )
            ->join('users', 'politics.empleado_id', 'users.id')
            ->join('tipopoliticas', 'politics.type_politic', 'tipopoliticas.id')
            ->where('id_statu', '=', 1)
            ->where('tipopoliticas.seccion_id', '=', $seccion);

        if (request()->has('search')) {
            $search = '%' . strtr(request('search'), array("'" => "\\'", "%" => "\\%")) . '%';
            $politicas->where('politics.namepolitica', 'like', $search);
        }
        if (request()->has('type_politic')) {
            $politicas->where('politics.type_politic', '=', request('type_politic'));
        }
        $nominas = DB::table('nominas_empleados')->where('empleado_id', auth()->user()->id)->orderByDesc('fecha_doc')->orderByDesc('periodo')->paginate(5);

        return Inertia::render('ControlInterno/DocumentosInternos', [
            'tipoPoliticas' => fn () => $tipoPoliticas->get(),
            'politicas' =>  fn () => $politicas->get(),
            'filters' => fn () => request()->all(['search', 'type_politic']),
            'nominas' => $nominas,
            'seccion_id' => $seccion
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'namepolitica' => ['required', 'max:60'],
            'descripcion' => ['required', 'max:100'],
            'type_politic' => ['required', 'exists:tipopoliticas,id'],
            'autor' => ['required', 'exists:users,id'],
            'imagePolitic' => ['nullable', 'mimes:png,jpeg,jpg'],
            'pdf' => ['required'],
        ]);
        $urlImage = "/assets/img/logo-red.jpg";
        if ($request->hasFile("imagePolitic")) {
            $file = $request->file('imagePolitic');

            $rutaImage = $file->store('politics/img', 'gcs');
            $urlImage = Storage::disk('gcs')->url($rutaImage);
        }
        $file = $request->file('pdf');
        $rutaPdf = $file->store('politics/pdf', 'gcs');
        $urlPdf = Storage::disk('gcs')->url($rutaPdf);

        Politic::create([
            'namepolitica' => $request->namepolitica,
            'descripcion' => $request->descripcion,
            'type_politic' => $request->type_politic,
            'id_statu' => 1,
            'autor' => $request->autor,
            'imagePolitic' => $urlImage,
            'pdf' => $urlPdf,
            'empleado_id' => Auth::id(),
        ]);

        return redirect()->back();
    }


    public function update(Request $request, Politic $politic)
    {
        $request->validate([
            'namepolitica' => ['required', 'max:60'],
            'descripcion' => ['required', 'max:100'],
            'type_politic' => ['required', 'exists:tipopoliticas,id'],
            'autor' => ['required', 'exists:users,id'],
            //'imagePolitic' => ['nullable'],
            //'pdf' => ['nullable'],
            'politic' => ['required'] //id a editar
        ]);

        $urlImage = null;
        if($request->has('imagePolitic'))
        {
          if($request['imagePolitic'] !== null)
          {
            $fileUrl = str_replace("https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET'), "/", $politic->imagePolitic);
            Storage::disk('gcs')->delete($fileUrl);
            $fileImg = $request->file('imagePolitic');
            $rutaImage = $fileImg->store('politics/img', 'gcs');
            $urlImage = Storage::disk('gcs')->url($rutaImage);
          }
        }

        $urlPdf = null;
        if($request->has('pdf'))
        {
          if($request['pdf'] !== null)
          {
            $fileUrl = str_replace("https://storage.googleapis.com/" . env('GOOGLE_CLOUD_STORAGE_BUCKET'), "/", $politic->pdf);
            Storage::disk('gcs')->delete($fileUrl);

            $file = $request->file('pdf');
            $rutaPdf = $file->store('politics/pdf', 'gcs');
            $urlPdf = Storage::disk('gcs')->url($rutaPdf);
          }
        }

        if($urlPdf !== null || $urlImage !== null)
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'imagePolitic' => $urlImage,
                'pdf' => $urlPdf,
                'empleado_id' => $request->autor,
            ]);
        }
        else
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'empleado_id' => $request->autor,
            ]);
        }

        if($urlPdf !== null)
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'pdf' => $urlPdf,
                'empleado_id' => $request->autor,
            ]);
        }
        else
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'empleado_id' => $request->autor,
            ]);
        }

        if($urlImage !== null)
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'imagePolitic' => $urlImage,
                'empleado_id' => $request->autor,
            ]);
        }
        else
        {
            $politica=  Politic::where('politics.id','=',$request['politic'])
            ->update([
                'namepolitica' => $request->namepolitica,
                'descripcion' => $request->descripcion,
                'type_politic' => $request->type_politic,
                'autor' => $request->autor,
                'empleado_id' => $request->autor,
            ]);
        }
        

        return redirect()->back();
    }

    /**
     * No se elimina solo se cambia el status
     * @param Politic $politc
     */
    public function destroy(Politic $politic)
    {
        $politic->id_statu = 2;
        $politic->save();
        return redirect()->back();
    }
}
