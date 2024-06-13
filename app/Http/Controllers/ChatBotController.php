<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Events\ChatBot;
use Exception;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function index()
    {
        return Inertia::render('ChatBot/ChatBot');
    }

    public function chatBot(Request $request)
    {
        $message = json_decode($request->getContent(), true);
        event(new ChatBot($message['entry'][0]));
        return response($request['hub_challenge'], 200);
    }

    public function chatBotData(Request $request)
    {
        try {
            $message = json_decode($request->getContent(), true);

            $value = $message['entry'][0]['changes'][0]['value'];

            event(new ChatBot($message));


            /* if (!empty($value['messages']))
                switch ($value['messages'][0]['type']) {
                    case 'text':
                        $body = $value['messages'][0]['text']['body'];
                        event(new ChatBot($body));
                        break;
                    case 'document':
                        $body = $value['messages'][0]['document'];
                        event(new ChatBot($body));
                        break;
                    case 'image':
                        $body = $value['messages'][0]['image'];
                        event(new ChatBot($body));
                        break;
                } */
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
