<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

trait SolicitudesTrait
{
    public static function translateText($text)
    {
        $resp = Http::withToken(env('DEEPL_TOKEN', 'apy_key'))->post('https://api-free.deepl.com/v2/translate', [
            'form_params' => [
                'text' => $text,
                'target_lang' => 'EN',
                'source_lang' => 'ES'
            ]
        ]);

        dd($resp);
        return $resp->body();
    }
}
