@extends('layouts.docs')
@section('title', 'Getting Started with Relay Cloud')

@section('content')
<h1>Getting Started with Relay Cloud</h1>
<p class="subtitle">From signup to production WebSocket in 60 seconds.</p>

<div class="step"><span class="step-num">1</span><h2>Create an Account</h2></div>
<p>Sign up at <a href="{{ route('register') }}">relaycloud.dev/register</a>. You'll get a free Hobby plan with 100 connections and 1 project.</p>
<p>Verify your email address to activate your account.</p>

<div class="step"><span class="step-num">2</span><h2>Create a Project</h2></div>
<p>From the <a href="{{ route('dashboard') }}">dashboard</a>, click <strong>"+ New Project"</strong> and give it a name.</p>
<p>You'll immediately receive:</p>
<ul>
    <li><strong>App ID</strong> — unique project identifier</li>
    <li><strong>App Key</strong> — public key for client connections</li>
    <li><strong>App Secret</strong> — private key for server-side publishing</li>
</ul>

<div class="step"><span class="step-num">3</span><h2>Connect Your App</h2></div>
<p>Your WebSocket endpoint is:</p>
<pre><code class="language-plaintext">wss://ws.relaycloud.dev/app/{your-app-key}</code></pre>

<div class="tabs">
    <button class="tab active" data-tab-btn="cloud-connect" onclick="showDocTab('cloud-connect','laravel',this)">Laravel</button>
    <button class="tab" data-tab-btn="cloud-connect" onclick="showDocTab('cloud-connect','node',this)">Node.js</button>
    <button class="tab" data-tab-btn="cloud-connect" onclick="showDocTab('cloud-connect','js',this)">JavaScript</button>
</div>
<div class="tab-panel active" data-tab-group="cloud-connect" data-tab="laravel">
<pre><code class="language-php">// config/broadcasting.php
'relay' => [
    'driver' => 'pusher',
    'key' => env('RELAY_APP_KEY'),
    'secret' => env('RELAY_APP_SECRET'),
    'app_id' => env('RELAY_APP_ID'),
    'options' => [
        'host' => 'ws.relaycloud.dev',
        'port' => 443,
        'scheme' => 'https',
        'encrypted' => true,
    ],
]</code></pre>
<pre><code class="language-bash"># .env
BROADCAST_CONNECTION=relay
RELAY_APP_KEY=your-app-key
RELAY_APP_SECRET=your-app-secret
RELAY_APP_ID=your-app-id</code></pre>
</div>
<div class="tab-panel" data-tab-group="cloud-connect" data-tab="node">
<pre><code class="language-javascript">const Pusher = require('pusher');

const pusher = new Pusher({
    appId: 'your-app-id',
    key: 'your-app-key',
    secret: 'your-app-secret',
    host: 'ws.relaycloud.dev',
    port: 443,
    useTLS: true,
});</code></pre>
</div>
<div class="tab-panel" data-tab-group="cloud-connect" data-tab="js">
<pre><code class="language-javascript">import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: 'your-app-key',
    wsHost: 'ws.relaycloud.dev',
    wssPort: 443,
    forceTLS: true,
    disableStats: true,
});</code></pre>
</div>

<div class="step"><span class="step-num">4</span><h2>Publish Your First Event</h2></div>
<pre><code class="language-php">// Laravel example
broadcast(new MessageSent($message));

// Or directly
$pusher->trigger('chat', 'new-message', [
    'user' => 'Alice',
    'text' => 'Hello from Relay Cloud!'
]);</code></pre>

<p><strong>That's it!</strong> Your app is now connected to Relay Cloud.</p>

<div class="note">
    <strong>Note:</strong> Relay Cloud uses the same Pusher-compatible protocol as the self-hosted version. Any existing Pusher SDK works with Relay Cloud &mdash; just change the host configuration.
</div>
@endsection
