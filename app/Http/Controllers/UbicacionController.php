<?php

namespace App\Http\Controllers;

use App\Http\Requests\UbicacionRequest;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    public function store(UbicacionRequest $request)
    {
        $validated = $request->validated();
        Ubicacion::create($validated);
        return redirect()->back();
    }

    public function update(UbicacionRequest $request, Ubicacion $ubicacion)
    {
        $validated = $request->validated();
        $ubicacion->update($validated);
        return redirect()->back();
    }
}
