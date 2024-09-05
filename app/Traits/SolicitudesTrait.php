<?php

namespace App\Traits;

use DeepL\Translator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

trait SolicitudesTrait
{
    public static function translateText($text)
    {
        $translator = new Translator(env('DEEPL_TOKEN'));
        $resp = $translator->translateText($text, 'es', 'en-GB');
        return $resp->text;
    }
}