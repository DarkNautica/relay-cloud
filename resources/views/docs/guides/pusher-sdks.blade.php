@extends('layouts.docs')

@section('title', 'Using Relay with Any Pusher SDK')

@section('content')
<div class="prose">
    <h1>Using Relay with Any Pusher SDK</h1>
    <p class="subtitle">Relay implements the full Pusher WebSocket and HTTP API protocol. Any SDK, library, or tool built for Pusher works with Relay &mdash; just change the host.</p>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSockets with a free tier. Connect in 60 seconds.
        <a href="/register">Try free &rarr;</a>
    </div>

    <p>Relay is fully compatible with the Pusher protocol. This means you don't need a Relay-specific SDK &mdash; if Pusher has an SDK for your language or framework, it works with Relay right now. The only change is pointing the host at your Relay server instead of Pusher's servers.</p>

    <h2>Universal Config Pattern</h2>

    <p>Every Pusher SDK accepts host/port overrides. The three things that change for any SDK:</p>

    <table>
        <thead>
            <tr>
                <th>Setting</th>
                <th>Value</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Host</strong></td>
                <td><code>ws.relaycloud.dev</code> (or your self-hosted server)</td>
            </tr>
            <tr>
                <td><strong>Port</strong></td>
                <td><code>443</code></td>
            </tr>
            <tr>
                <td><strong>TLS</strong></td>
                <td>Enabled</td>
            </tr>
            <tr>
                <td><strong>App Key</strong></td>
                <td>From your Relay Cloud dashboard</td>
            </tr>
            <tr>
                <td><strong>App Secret</strong></td>
                <td>From your Relay Cloud dashboard (server-side only)</td>
            </tr>
        </tbody>
    </table>

    <h2>SDK Quick Reference</h2>

    <h3>PHP (pusher/pusher-php-server)</h3>
    <pre><code class="language-php">$pusher = new Pusher\Pusher(
    'your-app-key',
    'your-app-secret',
    'your-app-id',
    [
        'host'   => 'ws.relaycloud.dev',
        'port'   => 443,
        'scheme' => 'https',
    ]
);</code></pre>

    <h3>JavaScript / Node.js (pusher-js client)</h3>
    <pre><code class="language-javascript">const pusher = new Pusher('your-app-key', {
    wsHost: 'ws.relaycloud.dev',
    wsPort: 443,
    wssPort: 443,
    forceTLS: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});</code></pre>

    <h3>Node.js Server (pusher npm package)</h3>
    <pre><code class="language-javascript">const Pusher = require('pusher');
const pusher = new Pusher({
    appId: 'your-app-id',
    key: 'your-app-key',
    secret: 'your-app-secret',
    host: 'ws.relaycloud.dev',
    port: '443',
    useTLS: true,
});</code></pre>

    <h3>Python (pusher)</h3>
    <pre><code class="language-python">import pusher

client = pusher.Pusher(
    app_id='your-app-id',
    key='your-app-key',
    secret='your-app-secret',
    host='ws.relaycloud.dev',
    port=443,
    ssl=True,
)</code></pre>

    <h3>Ruby (pusher gem)</h3>
    <pre><code class="language-ruby">Pusher.app_id  = 'your-app-id'
Pusher.key     = 'your-app-key'
Pusher.secret  = 'your-app-secret'
Pusher.host    = 'ws.relaycloud.dev'
Pusher.port    = 443
Pusher.scheme  = 'https'</code></pre>

    <h3>Go (pusher/pusher-http-go)</h3>
    <pre><code class="language-go">client := pusher.Client{
    AppID:   "your-app-id",
    Key:     "your-app-key",
    Secret:  "your-app-secret",
    Host:    "ws.relaycloud.dev:443",
    Secure:  true,
}</code></pre>

    <h3>Java (pusher-http-java)</h3>
    <pre><code class="language-java">Pusher pusher = new Pusher("your-app-id", "your-app-key", "your-app-secret");
pusher.setHost("ws.relaycloud.dev");
pusher.setPort(443);
pusher.setEncrypted(true);</code></pre>

    <h3>.NET (PusherServer NuGet)</h3>
    <pre><code class="language-csharp">var pusher = new Pusher(
    "your-app-id",
    "your-app-key",
    "your-app-secret",
    new PusherOptions {
        Host = "ws.relaycloud.dev",
        Encrypted = true
    }
);</code></pre>

    <h3>Swift (pusher-websocket-swift)</h3>
    <pre><code class="language-swift">let options = PusherClientOptions(
    authMethod: .inline(secret: "your-app-secret"),
    host: .custom("ws.relaycloud.dev"),
    port: 443,
    useTLS: true
)
let pusher = Pusher(key: "your-app-key", options: options)</code></pre>

    <h3>Kotlin / Android (pusher-websocket-android)</h3>
    <pre><code class="language-kotlin">val options = PusherOptions().apply {
    setHost("ws.relaycloud.dev")
    setWssPort(443)
    isEncrypted = true
}
val pusher = Pusher("your-app-key", options)</code></pre>

    <h2>Self-Hosted</h2>

    <p>If you are self-hosting Relay, replace <code>ws.relaycloud.dev</code> with your server's hostname or IP address. Set the port to whatever port your Relay server listens on (default: 6001). Set scheme to <code>http</code> and useTLS to <code>false</code> if you are not using SSL.</p>

    <pre><code class="language-bash"># Self-hosted config
Host: your-server-ip-or-domain
Port: 6001
TLS: false (unless you have SSL configured)</code></pre>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.
        <a href="/register">Start free &rarr;</a>
    </div>
</div>
@endsection
