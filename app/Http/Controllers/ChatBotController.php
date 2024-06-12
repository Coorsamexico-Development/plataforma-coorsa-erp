<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Events\ChatBot;
use Illuminate\Http\Request;

class ChatBotController extends Controller
{
    public function chatBot()
    {
        event(new ChatBot('hola'));
        return Inertia::render('ChatBot/ChatBot');
    }

    public function chatBotData(Request $request)
    {
        event(new ChatBot($request->mensaje));
    }
}
