<?php

namespace App\Http\Controllers;

use App\Services\RelayServerService;
use Illuminate\Http\Request;

class PlaygroundController extends Controller
{
    public function index()
    {
        $config = [
            'app_key' => config('services.relay.playground_key'),
            'host' => 'ws.relaycloud.dev',
            'port' => 443,
        ];

        return view('playground.index', compact('config'));
    }

    public function publish(Request $request)
    {
        $request->validate([
            'channel' => 'required|string|max:100',
            'event' => 'required|string|max:100',
            'payload' => 'required|string|max:2000',
        ]);

        $data = json_decode($request->payload, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->withErrors(['payload' => 'Payload must be valid JSON']);
        }

        $relay = app(RelayServerService::class);
        $relay->publishEvent(
            config('services.relay.playground_app_id'),
            config('services.relay.playground_secret'),
            $request->channel,
            $request->event,
            $data
        );

        return response()->json(['ok' => true]);
    }
}
