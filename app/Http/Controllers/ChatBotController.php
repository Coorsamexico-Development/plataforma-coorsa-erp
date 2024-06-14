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

            if (!empty($value['messages']))
                switch ($value['messages'][0]['type']) {
                    case 'text':
                        $body = $value['messages'][0]['text']['body'];
                        $message = (object) $value['messages'][0];
                        event(new ChatBot($message));

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
                        event(new ChatBot($response->object()));
                        break;
                    case 'document':
                        $body = (object) $value['messages'][0]['document'];
                        $response = Http::withToken($tokenWhats)->get("https://graph.facebook.com/v20.0/[$body->id}");
                        $file = Http::withToken($tokenWhats)->get($response->object()->url);
                        $file = new UploadedFile($file->body(), $body->mimeType);
                        $pathfile = $file->storeAs("WhatsApp/", Str::uuid() . '.' . $file->extension(), 'gcs');
                        $pathGCS = Storage::disk('gcs')->url($pathfile);

                        event(new ChatBot($body));
                        event(new ChatBot($pathGCS));
                        break;
                    case 'image':
                        $body = (object) $value['messages'][0]['image'];
                        $response = Http::withToken($tokenWhats)->get("https://graph.facebook.com/v20.0/{$body->id}");
                        $file = Http::withToken($tokenWhats)->get($response->object()->url);
                        $file = new UploadedFile($file->body(), $body->mimeType);
                        $pathfile = $file->storeAs("WhatsApp/", Str::uuid() . '.' . $file->extension(), 'gcs');
                        $pathGCS = Storage::disk('gcs')->url($pathfile);

                        event(new ChatBot($body));
                        event(new ChatBot($pathGCS));
                        break;
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
