@extends('layouts.docs')
@section('title', 'SDKs & Integrations')

@section('content')
<div class="cloud-nudge">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Relay Cloud</strong> manages connections and auth for you. Point any SDK at your Cloud endpoint and go.</span>
    <a href="{{ route('register') }}">Try Relay Cloud &rarr;</a>
</div>

<h1>SDKs &amp; Integrations</h1>
<p class="subtitle">Official SDKs for popular frameworks and languages.</p>

<table>
    <thead>
        <tr>
            <th>SDK</th>
            <th>Language</th>
            <th>Install</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><strong>relay-php</strong></td>
            <td>PHP / Laravel</td>
            <td><code>composer require relayhq/relay-php</code></td>
        </tr>
        <tr>
            <td><strong>relay-node</strong></td>
            <td>Node.js</td>
            <td><code>npm install @relayhq/relay-node</code></td>
        </tr>
        <tr>
            <td><strong>relay-ruby</strong></td>
            <td>Ruby / Rails</td>
            <td><code>gem install relay-ruby</code></td>
        </tr>
        <tr>
            <td><strong>relay-python</strong></td>
            <td>Python / Django</td>
            <td><code>pip install relay-python</code></td>
        </tr>
    </tbody>
</table>

<!-- ── Laravel ── -->
<h2>Laravel (relay-php)</h2>

<h3>Installation</h3>
<pre><code class="language-bash">composer require relayhq/relay-php</code></pre>

<h3>Configuration</h3>
<p>Add a <code>relay</code> connection to <code>config/broadcasting.php</code>:</p>
<pre><code class="language-php">'relay' => [
    'driver' => 'pusher',
    'key' => env('RELAY_APP_KEY'),
    'secret' => env('RELAY_APP_SECRET'),
    'app_id' => env('RELAY_APP_ID', 'app'),
    'options' => [
        'host' => env('RELAY_HOST', 'localhost'),
        'port' => env('RELAY_PORT', 6001),
        'scheme' => 'http',
    ],
],</code></pre>

<h3>Publishing Events</h3>
<pre><code class="language-php">// Using Laravel broadcasting
broadcast(new MessageSent($message));

// Direct usage
use Pusher\Pusher;
$pusher = new Pusher($key, $secret, $appId, $options);
$pusher->trigger('my-channel', 'my-event', ['message' => 'Hello']);</code></pre>

<h3>Auth for Private Channels</h3>
<p>Define authorization callbacks in <code>routes/channels.php</code>:</p>
<pre><code class="language-php">Broadcast::channel('orders.{id}', function ($user, $id) {
    return $user->id === Order::findOrNew($id)->user_id;
});</code></pre>

<!-- ── Node.js ── -->
<h2>Node.js (relay-node)</h2>

<h3>Installation</h3>
<pre><code class="language-bash">npm install @relayhq/relay-node</code></pre>

<h3>Setup &amp; Publishing</h3>
<pre><code class="language-javascript">const Pusher = require('pusher');
const pusher = new Pusher({
    appId: 'app',
    key: 'my-key',
    secret: 'my-secret',
    host: 'localhost',
    port: 6001,
    useTLS: false,
});

// Single event
await pusher.trigger('my-channel', 'my-event', { message: 'Hello' });

// Batch
await pusher.triggerBatch([
    { channel: 'ch-1', name: 'evt-1', data: { msg: 'Hello' } },
    { channel: 'ch-2', name: 'evt-2', data: { msg: 'World' } },
]);</code></pre>

<h3>Express Auth Middleware</h3>
<pre><code class="language-javascript">app.post('/broadcasting/auth', (req, res) => {
    const { socket_id, channel_name } = req.body;
    const auth = pusher.authorizeChannel(socket_id, channel_name);
    res.send(auth);
});</code></pre>

<!-- ── Rails ── -->
<h2>Rails (relay-ruby)</h2>

<h3>Installation</h3>
<p>Add to your <code>Gemfile</code>:</p>
<pre><code class="language-ruby">gem 'pusher'</code></pre>

<h3>Configuration</h3>
<pre><code class="language-ruby"># config/initializers/pusher.rb
Pusher.app_id = 'app'
Pusher.key = 'my-key'
Pusher.secret = 'my-secret'
Pusher.host = 'localhost'
Pusher.port = 6001
Pusher.encrypted = false</code></pre>

<h3>Usage</h3>
<pre><code class="language-ruby">Pusher.trigger('my-channel', 'my-event', { message: 'Hello' })

# Batch
Pusher.trigger_batch([
  { channel: 'ch-1', name: 'evt-1', data: { msg: 'Hello' } }
])</code></pre>

<h3>Auth Controller</h3>
<pre><code class="language-ruby">class PusherController < ApplicationController
  def auth
    response = Pusher.authenticate(params[:channel_name], params[:socket_id])
    render json: response
  end
end</code></pre>

<!-- ── Django/Python ── -->
<h2>Django/Python (relay-python)</h2>

<h3>Installation</h3>
<pre><code class="language-bash">pip install relay-python</code></pre>

<h3>Setup &amp; Sync Publishing</h3>
<pre><code class="language-python">import pusher

client = pusher.Pusher(
    app_id='app',
    key='my-key',
    secret='my-secret',
    host='localhost',
    port=6001,
    ssl=False
)

client.trigger('my-channel', 'my-event', {'message': 'Hello'})</code></pre>

<h3>Async</h3>
<pre><code class="language-python">import asyncio
from pusher import AsyncPusher

async def publish():
    client = AsyncPusher(app_id='app', key='my-key', secret='my-secret', host='localhost', port=6001, ssl=False)
    await client.trigger('my-channel', 'my-event', {'message': 'Hello'})</code></pre>

<h3>Batch</h3>
<pre><code class="language-python">client.trigger_batch([
    {'channel': 'ch-1', 'name': 'evt-1', 'data': {'msg': 'Hello'}},
])</code></pre>

<h3>Django Auth View</h3>
<pre><code class="language-python">from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt

@csrf_exempt
def pusher_auth(request):
    response = client.authenticate(
        channel=request.POST['channel_name'],
        socket_id=request.POST['socket_id']
    )
    return JsonResponse(response)</code></pre>

<div class="cloud-nudge" style="margin-top:36px;">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSocket infrastructure. Point your SDK at your Cloud endpoint and ship faster.</span>
    <a href="{{ route('register') }}">Start Free &rarr;</a>
</div>
@endsection
