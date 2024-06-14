<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Events\ChatBot;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatBotController extends Controller
{
    public function index()
    {
        return Inertia::render('ChatBot/ChatBot');
    }

    public function chatBot(Request $request)
    {
        try {
            $verifyToken = '90e76a72d211d20f8d1f96316b45865af00ba7985473b4e30b526d9066b8bdef';
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

            if (!empty($value['messages']))
                switch ($value['messages'][0]['type']) {
                    case 'text':
                        $body = $value['messages'][0]['text']['body'];
                        event(new ChatBot($body));
                        break;
                    case 'document':
                        $body = (object) $value['messages'][0]['document'];
                        $response = Http::withHeaders([
                            'Authorization' => `Bearer EAADZBDtGyozwBOwiMH8feHwEqTuuHGdoZBdN4KYBaPNDNFZBCtfwZBvODA8HK8TZCTZArm6kuI6vLmH1riQBFHNh3nDmygLIjGfCeWzvEtJksiPnYFPmG2Xiqo64w48ch1QZB7lDmBDcAv6sgyDHFkZCFh92gNGA5rl11wxWj3ZCYOIBbtrAsx8S85DJipF6nZAHEm`,
                            "Content-Type" => "application/json",
                        ])->get("https://graph.facebook.com/v20.0/{$body->id}");
                        event(new ChatBot($response));
                        break;
                    case 'image':
                        $body = (object) $value['messages'][0]['image'];
                        $response = Http::withHeaders([
                            'Authorization' => `Bearer EAADZBDtGyozwBOwiMH8feHwEqTuuHGdoZBdN4KYBaPNDNFZBCtfwZBvODA8HK8TZCTZArm6kuI6vLmH1riQBFHNh3nDmygLIjGfCeWzvEtJksiPnYFPmG2Xiqo64w48ch1QZB7lDmBDcAv6sgyDHFkZCFh92gNGA5rl11wxWj3ZCYOIBbtrAsx8S85DJipF6nZAHEm`,
                            "Content-Type" => "application/json",
                        ])->get("https://graph.facebook.com/v20.0/{$body->id}");
                        event(new ChatBot($response));
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
