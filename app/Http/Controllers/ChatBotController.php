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

    public function chatBot(Request $request, $req, $res)
    {
        $message = json_encode($req);
        event(new ChatBot($message));
        return response($request['hub_challenge'], 200);
    }

    public function chatBotData(Request $request, $req, $res)
    {
        event(new ChatBot(json_encode($req)));
        return response($request['hub_challenge'], 200);
    }
}