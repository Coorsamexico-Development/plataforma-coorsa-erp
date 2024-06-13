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
        $message = json_decode($request);
        event(new ChatBot($message));
        return response($request['hub_challenge'], 200);
    }

    public function chatBotData(Request $request)
    {
        event(new ChatBot(json_decode($request)));
        return response($request['hub_challenge'], 200);
    }
}