@extends('layouts.docs')
@section('title', 'Getting Started')

@section('content')
<div class="cloud-nudge">
    <span class="cloud-nudge-icon">&#9889;</span>
    <span><strong>Skip the setup.</strong> Relay Cloud handles hosting, SSL, and ops for you. WebSockets ready in 60 seconds.</span>
    <a href="{{ route('register') }}">Try free &rarr;</a>
</div>

<h1>Getting Started</h1>
<p class="subtitle">Get Relay running and publishing your first real-time event in under 5 minutes.</p>

<h2>Prerequisites</h2>
<ul>
    <li><strong>Go 1.21+</strong> or <strong>Docker</strong></li>
    <li>A backend framework &mdash; Laravel, Node.js, Rails, or Django</li>
    <li>A JavaScript frontend</li>
</ul>

{{-- Step 1 --}}
<div class="step">
    <span class="step-num">1</span>
    <h2>Run the Server</h2>
</div>

<p>Choose how you want to run Relay on your machine or server.</p>

<div class="tabs" data-tab-btn="server">
    <button class="tab active" onclick="showDocTab('server','docker',this)">Docker <span style="font-size:10px;color:var(--t3);font-weight:400;">(recommended)</span></button>
    <button class="tab" onclick="showDocTab('server','binary',this)">Binary</button>
    <button class="tab" onclick="showDocTab('server','source',this)">Build from Source</button>
</div>

<div data-tab-group="server" data-tab="docker" class="tab-panel active">
    <pre><code class="language-bash">docker run -d -p 6001:6001 \
  -e RELAY_APP_KEY=my-key \
  -e RELAY_APP_SECRET=my-secret \
  relayhq/relay:latest</code></pre>
    <p>The server is now listening on <code>ws://localhost:6001</code>.</p>
</div>

<div data-tab-group="server" data-tab="binary" class="tab-panel">
    <p>Download the latest binary from <a href="https://github.com/DarkNautica/Relay/releases" target="_blank">GitHub Releases</a> for your platform, then run:</p>
    <pre><code class="language-bash">./relay-server</code></pre>
</div>

<div data-tab-group="server" data-tab="source" class="tab-panel">
    <pre><code class="language-bash">git clone https://github.com/DarkNautica/Relay.git &amp;&amp; cd Relay
go build -o relay-server .
./relay-server</code></pre>
</div>

{{-- Step 2 --}}
<div class="step">
    <span class="step-num">2</span>
    <h2>Connect the JavaScript Client</h2>
</div>

<p>Install the Relay JavaScript SDK:</p>

<pre><code class="language-bash">npm install @relayhq/relay-js</code></pre>

<p>Then connect to your Relay server and subscribe to a channel:</p>

<pre><code class="language-javascript">import Relay from '@relayhq/relay-js';

const relay = new Relay('my-key', {
    host: 'localhost',
    port: 6001,
    encrypted: false,
});

relay.on('connected', () =&gt; console.log('Connected to Relay!'));
relay.on('disconnected', () =&gt; console.log('Disconnected.'));
relay.on('error', (err) =&gt; console.error('Error:', err));

const channel = relay.subscribe('my-channel');
channel.bind('my-event', (data) =&gt; {
    console.log('Received:', data);
});</code></pre>

{{-- Step 3 --}}
<div class="step">
    <span class="step-num">3</span>
    <h2>Publish from Your Backend</h2>
</div>

<p>Relay is wire-compatible with the Pusher protocol, so you can use any Pusher-compatible server SDK to trigger events.</p>

<div class="tabs" data-tab-btn="publish">
    <button class="tab active" onclick="showDocTab('publish','laravel',this)">Laravel</button>
    <button class="tab" onclick="showDocTab('publish','node',this)">Node.js</button>
    <button class="tab" onclick="showDocTab('publish','rails',this)">Rails</button>
    <button class="tab" onclick="showDocTab('publish','django',this)">Django</button>
</div>

<div data-tab-group="publish" data-tab="laravel" class="tab-panel active">
    <h4>Install</h4>
    <pre><code class="language-bash">composer require relayhq/relay-php</code></pre>

    <h4>config/broadcasting.php</h4>
    <pre><code class="language-php">'relay' =&gt; [
    'driver' =&gt; 'pusher',
    'key' =&gt; env('RELAY_APP_KEY'),
    'secret' =&gt; env('RELAY_APP_SECRET'),
    'app_id' =&gt; env('RELAY_APP_ID', 'app'),
    'options' =&gt; [
        'host' =&gt; env('RELAY_HOST', 'localhost'),
        'port' =&gt; env('RELAY_PORT', 6001),
        'scheme' =&gt; 'http',
    ],
],</code></pre>

    <h4>.env</h4>
    <pre><code class="language-bash">BROADCAST_CONNECTION=relay
RELAY_APP_KEY=my-key
RELAY_APP_SECRET=my-secret</code></pre>

    <h4>Publish an event</h4>
    <pre><code class="language-php">broadcast(new OrderShipped($order));</code></pre>
</div>

<div data-tab-group="publish" data-tab="node" class="tab-panel">
    <pre><code class="language-javascript">const Pusher = require('pusher');

const pusher = new Pusher({
    appId: 'app',
    key: 'my-key',
    secret: 'my-secret',
    host: 'localhost',
    port: 6001,
    useTLS: false,
});

await pusher.trigger('my-channel', 'my-event', {
    message: 'Hello!'
});</code></pre>
</div>

<div data-tab-group="publish" data-tab="rails" class="tab-panel">
    <h4>Gemfile</h4>
    <pre><code class="language-ruby">gem 'pusher'</code></pre>

    <h4>config/initializers/pusher.rb</h4>
    <pre><code class="language-ruby">Pusher.app_id = 'app'
Pusher.key = 'my-key'
Pusher.secret = 'my-secret'
Pusher.host = 'localhost'
Pusher.port = 6001
Pusher.encrypted = false</code></pre>

    <h4>Trigger an event</h4>
    <pre><code class="language-ruby">Pusher.trigger('my-channel', 'my-event', { message: 'Hello!' })</code></pre>
</div>

<div data-tab-group="publish" data-tab="django" class="tab-panel">
    <h4>Install</h4>
    <pre><code class="language-bash">pip install pusher</code></pre>

    <h4>Trigger an event</h4>
    <pre><code class="language-python">import pusher

client = pusher.Pusher(
    app_id='app',
    key='my-key',
    secret='my-secret',
    host='localhost',
    port=6001,
    ssl=False
)

client.trigger('my-channel', 'my-event', {'message': 'Hello!'})</code></pre>
</div>

{{-- Step 4 --}}
<div class="step">
    <span class="step-num">4</span>
    <h2>Private Channels</h2>
</div>

<p>Private channels restrict access to authenticated users. Channel names must be prefixed with <code>private-</code>. When a client subscribes to a private channel, Relay sends an authentication request to your backend.</p>

<div class="note">
    <strong>Auth endpoint:</strong> Your backend must expose a <code>POST /broadcasting/auth</code> endpoint that validates the user and returns a signed auth token.
</div>

<h4>Subscribe on the client</h4>
<pre><code class="language-javascript">const privateChannel = relay.subscribe('private-orders');
privateChannel.bind('new-order', (data) =&gt; {
    console.log('New order:', data);
});</code></pre>

<h4>Laravel auth setup</h4>
<p>Laravel handles this automatically via the <code>BroadcastServiceProvider</code>. Define your channel authorization in <code>routes/channels.php</code>:</p>

<pre><code class="language-php">// routes/channels.php
Broadcast::channel('private-orders', function ($user) {
    return $user-&gt;canViewOrders();
});</code></pre>

<p>Make sure broadcasting is enabled in <code>config/app.php</code> by uncommenting the <code>BroadcastServiceProvider</code>:</p>

<pre><code class="language-php">// config/app.php &mdash; providers array
App\Providers\BroadcastServiceProvider::class,</code></pre>

{{-- Step 5 --}}
<div class="step">
    <span class="step-num">5</span>
    <h2>Presence Channels</h2>
</div>

<p>Presence channels extend private channels with awareness of who is currently subscribed. They are ideal for showing online indicators, typing notifications, or collaborative features. Channel names must be prefixed with <code>presence-</code>.</p>

<h4>Subscribe on the client</h4>
<pre><code class="language-javascript">const presenceChannel = relay.subscribe('presence-chat-room');

presenceChannel.bind('relay:subscription_succeeded', (members) =&gt; {
    console.log('Online now:', members.count);
    members.each((member) =&gt; {
        console.log(member.id, member.info);
    });
});

presenceChannel.bind('relay:member_added', (member) =&gt; {
    console.log('Joined:', member.id);
});

presenceChannel.bind('relay:member_removed', (member) =&gt; {
    console.log('Left:', member.id);
});</code></pre>

<h4>Authorize on the backend (Laravel)</h4>
<pre><code class="language-php">// routes/channels.php
Broadcast::channel('presence-chat-room', function ($user) {
    return ['id' =&gt; $user-&gt;id, 'name' =&gt; $user-&gt;name];
});</code></pre>

<p>Returning an array (instead of <code>true</code>) tells Relay to treat this as a presence channel and share the returned data with all subscribers.</p>

<h2>Common Issues</h2>

<h3>BROADCAST_CONNECTION vs BROADCAST_DRIVER</h3>
<p>In <strong>Laravel 11+</strong>, the environment variable was renamed from <code>BROADCAST_DRIVER</code> to <code>BROADCAST_CONNECTION</code>. If events are not reaching Relay, make sure your <code>.env</code> uses the correct key for your Laravel version:</p>
<pre><code class="language-bash"># Laravel 11+
BROADCAST_CONNECTION=relay

# Laravel 10 and earlier
BROADCAST_DRIVER=relay</code></pre>

<h3>CSRF Exemption for Auth Endpoint</h3>
<p>The Relay JavaScript client sends a POST request to <code>/broadcasting/auth</code>. If you receive a <code>419</code> status code, add the route to your CSRF exceptions:</p>
<pre><code class="language-php">// app/Http/Middleware/VerifyCsrfToken.php
protected $except = [
    'broadcasting/auth',
];</code></pre>

<h3>Event Name Prefixes</h3>
<p>Laravel automatically prefixes broadcast event names with the fully qualified class name, e.g. <code>App\Events\OrderShipped</code>. If you are listening for events by their raw name on the client, prefix the listener with a dot to bypass the namespace:</p>
<pre><code class="language-javascript">// Listen for the raw event name (no App\Events\ prefix)
channel.bind('.my-event', (data) =&gt; {
    console.log(data);
});</code></pre>

<div class="cloud-nudge" style="margin-top:40px;">
    <span class="cloud-nudge-icon">&#9889;</span>
    <span><strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.</span>
    <a href="{{ route('register') }}">Start free &rarr;</a>
</div>
@endsection
