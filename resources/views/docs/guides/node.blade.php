@extends('layouts.docs')

@section('title', 'Using Relay with Node.js')

@section('content')
<div class="prose">
    <h1>Using Relay with Node.js</h1>
    <p>Trigger WebSocket events from your Node.js or Express app using Relay's Pusher-compatible server SDK.</p>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSockets with a free tier. Connect in 60 seconds.
        <a href="{{ route('register') }}">Try free &rarr;</a>
    </div>

    <h2>Prerequisites</h2>
    <ul>
        <li>Node.js 16+</li>
        <li>A running Relay server</li>
        <li>Your App Key, Secret, and App ID</li>
    </ul>

    <div class="step">
        <span class="step-num">1</span>
        <h2>Install the Pusher Server SDK</h2>
    </div>

    <pre><code class="language-bash">npm install pusher</code></pre>

    <div class="step">
        <span class="step-num">2</span>
        <h2>Create relay.js</h2>
    </div>

    <pre><code class="language-javascript">const Pusher = require('pusher');

const relay = new Pusher({
  appId:   process.env.RELAY_APP_ID,
  key:     process.env.RELAY_APP_KEY,
  secret:  process.env.RELAY_APP_SECRET,
  host:    process.env.RELAY_HOST ?? 'ws.relaycloud.dev',
  port:    process.env.RELAY_PORT ?? '443',
  useTLS:  true,
});

module.exports = relay;</code></pre>

    <div class="step">
        <span class="step-num">3</span>
        <h2>Environment Variables</h2>
    </div>

    <p>Add these to your <code>.env</code> file:</p>

    <pre><code class="language-bash">RELAY_APP_ID=your-app-id
RELAY_APP_KEY=your-app-key
RELAY_APP_SECRET=your-app-secret
RELAY_HOST=ws.relaycloud.dev
RELAY_PORT=443</code></pre>

    <div class="step">
        <span class="step-num">4</span>
        <h2>Trigger from an Express Route</h2>
    </div>

    <pre><code class="language-javascript">const express = require('express');
const relay = require('./relay');
const app = express();

app.post('/messages', express.json(), async (req, res) =&gt; {
  const { message } = req.body;

  await relay.trigger('public-feed', 'message.sent', {
    message,
    timestamp: new Date().toISOString(),
  });

  res.json({ status: 'sent' });
});</code></pre>

    <div class="step">
        <span class="step-num">5</span>
        <h2>Private Channel Trigger</h2>
    </div>

    <pre><code class="language-javascript">await relay.trigger('private-user.42', 'notification', {
  text: 'You have a new message',
});</code></pre>

    <div class="step">
        <span class="step-num">6</span>
        <h2>Batch Triggers</h2>
    </div>

    <pre><code class="language-javascript">await relay.triggerBatch([
  { channel: 'public-feed',    name: 'message.sent', data: { message: 'Hello' } },
  { channel: 'private-user.1', name: 'notification', data: { text: 'Hi' } },
]);</code></pre>

    <div class="note">
        <p>Relay supports the full Pusher server SDK interface including batch triggers, channel info queries, and presence data.</p>
    </div>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.
        <a href="{{ route('register') }}">Start free &rarr;</a>
    </div>
</div>
@endsection
