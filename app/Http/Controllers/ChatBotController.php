<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Events\ChatBot;
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
        event(new ChatBot($message->enrty));
        return response($request['hub_challenge'], 200);
    }

    public function chatBotData(Request $request)
    {
        $message = json_decode($request->getContent(), true);
        event(new ChatBot($message->enrty));
        return response($request['hub_challenge'], 200);
    }
}
