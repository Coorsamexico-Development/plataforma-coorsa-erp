<?php

namespace App\Traits;

use DeepL\Translator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Http;

trait EmpeladosTrait
{
    public static function changePassword($password, $curp)
    {
        $url = "https://www.opeaciones.coorsamexico.com/api/v1/empleados/change/password";

        Http::post($url, [
            'password' => $password,
            'curp' => $curp
        ]);
    }
}