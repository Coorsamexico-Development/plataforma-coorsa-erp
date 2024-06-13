<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Events\ChatBot;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function chatBot(Request $request)
    {
        $message = $request->body;
        event(new ChatBot($message));
        return response($request['hub_challenge'], 200)->withHeaders([
            'Content-Type' => 'text/plain; charset=utf-8',
            'X-Powered-By' => 'Express',
            'x-powered-by' => 'Express',
        ]);
    }

    public function chatBotData(Request $request)
    {
        event(new ChatBot($request->mensaje));
        return response($request['hub.challenge'], 200)->withHeaders([
            'Content-Type' => 'text/plain; charset=utf-8',
            'X-Powered-By' => 'Express',
            'x-powered-by' => 'Express',
        ]);
    }
}
