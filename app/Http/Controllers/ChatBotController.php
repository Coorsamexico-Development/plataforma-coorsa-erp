<?php

namespace App\Http\Controllers;

use Exception;
use Inertia\Inertia;
use App\Events\ChatBot;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ChatBotController extends Controller
{
    public function index()
    {
        return Inertia::render('ChatBot/ChatBot');
    }

    public function chatBot(Request $request)
    {
        try {
            $verifyToken = env('VERIFY_TOKEN');
            $query = $request->query();

            $mode = $query['hub_mode'];
            $token = $query['hub_verify_token'];
            $challenge = $query['hub_challenge'];

            if ($mode && $token)
                if ($mode === 'subscribe' && $token === $verifyToken)
                    return response($challenge, 200)->header('Content-Type', 'text/plain');
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function chatBotData(Request $request)
    {
        try {
            $message = json_decode($request->getContent(), true);

            $value = $message['entry'][0]['changes'][0]['value'];
            $tokenWhats = env('WHATSAPP_TOKEN');
            $phoneNumberId = env('PHONE_NUMBER');
            $docName = Str::uuid();
            $message = (object) $value['messages'][0];
            $pathfile = "WhatsApp/{$message->from}/{$docName}";

            if (!empty($value['messages'])) {
                event(new ChatBot($value));
                switch ($value['messages'][0]['type']) {
                    case 'text':
                        //Cuerpo del mensaje
                        $body = $value['messages'][0]['text']['body'];

                        //Respondemos el mensaje
                        $response = Http::withToken($tokenWhats)->post(
                            "https://graph.facebook.com/v20.0/{$phoneNumberId}/messages",
                            [
                                'messaging_product' => "whatsapp",
                                'to' => $message->from,
                                "type" => "text",
                                'text' => [
                                    'body' => "Esta es una respuesta automatica, le recordamos que este numero ya no esta disponible en whatsapp para ningun tipo de consulta"
                                ],
                            ]
                        );

                        //Evento para ver lo que esta retornando
                        event(new ChatBot($response->object()));
                        break;
                    case 'document':
                        //Cuerpo del mensaje
                        $body = (object) $value['messages'][0]['document'];

                        //Solicitud HTTP para obetner el archivo
                        $response = Http::withToken($tokenWhats)->get("https://graph.facebook.com/v20.0/[$body->id}");
                        $file = Http::withToken($tokenWhats)->get($response->object()->url);

                        //Subir la Imagen al Bucket
                        $file = Storage::disk('gcs')->put($pathfile, $file->body());
                        $pathGCS = Storage::disk('gcs')->url($pathfile);

                        //Respondemos el mensaje
                        $response = Http::withToken($tokenWhats)->post(
                            "https://graph.facebook.com/v20.0/{$phoneNumberId}/messages",
                            [
                                'messaging_product' => "whatsapp",
                                'to' => $message->from,
                                "type" => "text",
                                'text' => [
                                    'body' => "Gracias por mandarnos tu documento los puedes encontrar aqui {$pathGCS}"
                                ],
                            ]
                        );

                        //Evento para ver lo que esta retornando
                        event(new ChatBot($pathGCS));
                        break;
                    case 'image':
                        //Cuerpo del mensaje
                        $body = (object) $value['messages'][0]['image'];

                        //Solicitud HTTP para obetner el archivo
                        $response = Http::withToken($tokenWhats)->get("https://graph.facebook.com/v20.0/{$body->id}");
                        $file = Http::withToken($tokenWhats)->get($response->object()->url);

                        //Subir la Imagen al Bucket
                        $file = Storage::disk('gcs')->put($pathfile, $file->body());
                        $pathGCS = Storage::disk('gcs')->url($pathfile);

                        //Respondemos el mensaje
                        $response = Http::withToken($tokenWhats)->post(
                            "https://graph.facebook.com/v20.0/{$phoneNumberId}/messages",
                            [
                                'messaging_product' => "whatsapp",
                                'to' => $message->from,
                                "type" => "text",
                                'text' => [
                                    'body' => "Gracias por mandarnos tu imagen los puedes encontrar aqui {$pathGCS}"
                                ],
                            ]
                        );

                        //Evento para ver lo que esta retornando
                        event(new ChatBot($pathGCS));
                        break;
                }
            }
            return response()->json([
                'success' => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}