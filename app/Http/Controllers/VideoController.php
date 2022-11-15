<?php

namespace App\Http\Controllers;

use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    
    public function store(Request $request)
    {
       
        if($request->has('video'))
        {
           $video = $request['video'];
           $nombre =  $video->getClientOriginalName();
           $ruta_video = $video->storeAs('videos', $nombre, 'gcs'); //guardamos el archivo en el storage
           $urlVideo = Storage::disk('gcs')->url($ruta_video);

           video::create([
             'image' => $urlVideo,
             'empleado_id' => $request['autor']
           ]);
        }

        
        return  redirect()->back();
    }
    
}
