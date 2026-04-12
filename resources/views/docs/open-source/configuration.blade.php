@extends('layouts.docs')
@section('title', 'Configuration')

@section('content')
<div class="cloud-nudge">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Relay Cloud</strong> handles all configuration for you. No environment variables, no server tuning.</span>
    <a href="{{ route('register') }}">Try Relay Cloud &rarr;</a>
</div>

<h1>Configuration</h1>
<p class="subtitle">All configuration is done via environment variables.</p>

<h2>Environment Variables</h2>

<table>
    <thead>
        <tr>
            <th>Variable</th>
            <th>Default</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>RELAY_HOST</code></td>
            <td><code>0.0.0.0</code></td>
            <td>Host to bind</td>
        </tr>
        <tr>
            <td><code>RELAY_PORT</code></td>
            <td><code>6001</code></td>
            <td>Port to listen on</td>
        </tr>
        <tr>
            <td><code>RELAY_APP_ID</code></td>
            <td><code>app</code></td>
            <td>Default app ID</td>
        </tr>
        <tr>
            <td><code>RELAY_APP_KEY</code></td>
            <td>(required)</td>
            <td>App authentication key</td>
        </tr>
        <tr>
            <td><code>RELAY_APP_SECRET</code></td>
            <td>(required)</td>
            <td>App secret for signing</td>
        </tr>
        <tr>
            <td><code>RELAY_DEBUG</code></td>
            <td><code>false</code></td>
            <td>Enable debug logging</td>
        </tr>
        <tr>
            <td><code>RELAY_MAX_CONNECTIONS</code></td>
            <td><code>0</code> (unlimited)</td>
            <td>Max concurrent connections</td>
        </tr>
        <tr>
            <td><code>RELAY_DASHBOARD_ENABLED</code></td>
            <td><code>true</code></td>
            <td>Enable web dashboard</td>
        </tr>
        <tr>
            <td><code>RELAY_DASHBOARD_PATH</code></td>
            <td><code>/dashboard</code></td>
            <td>Dashboard URL path</td>
        </tr>
        <tr>
            <td><code>RELAY_HISTORY_ENABLED</code></td>
            <td><code>false</code></td>
            <td>Enable event history</td>
        </tr>
        <tr>
            <td><code>RELAY_HISTORY_MAX</code></td>
            <td><code>100</code></td>
            <td>Max events to store</td>
        </tr>
        <tr>
            <td><code>RELAY_WEBHOOK_URL</code></td>
            <td>(empty)</td>
            <td>Webhook endpoint URL</td>
        </tr>
        <tr>
            <td><code>RELAY_WEBHOOK_EVENTS</code></td>
            <td>(empty)</td>
            <td>Events to send to webhook</td>
        </tr>
        <tr>
            <td><code>RELAY_RATE_LIMIT_ENABLED</code></td>
            <td><code>false</code></td>
            <td>Enable rate limiting</td>
        </tr>
        <tr>
            <td><code>RELAY_RATE_LIMIT_MAX</code></td>
            <td><code>100</code></td>
            <td>Max events per second</td>
        </tr>
    </tbody>
</table>

<h2>Multi-App Configuration</h2>

<p>To run multiple apps on a single Relay server, create an <code>apps.json</code> file. Each app gets its own credentials and connection limits, allowing you to isolate tenants or environments on one instance.</p>

<p>Place the file at <code>/etc/relay/apps.json</code>:</p>

<pre><code class="language-json">[
  {
    "id": "app_1",
    "key": "key-1",
    "secret": "secret-1",
    "max_connections": 1000
  },
  {
    "id": "app_2",
    "key": "key-2",
    "secret": "secret-2",
    "max_connections": 500
  }
]</code></pre>

<div class="note">
    <strong>Note:</strong> When using <code>apps.json</code>, the <code>RELAY_APP_KEY</code> and <code>RELAY_APP_SECRET</code> environment variables are ignored. All app credentials are read from the JSON file instead.
</div>

<h2>Docker Compose</h2>

<p>A complete <code>docker-compose.yml</code> example with all common options:</p>

<pre><code class="language-yaml">version: '3.8'
services:
  relay:
    image: relayhq/relay:latest
    ports:
      - "6001:6001"
    environment:
      RELAY_APP_KEY: my-key
      RELAY_APP_SECRET: my-secret
      RELAY_DEBUG: "false"
      RELAY_DASHBOARD_ENABLED: "true"
    volumes:
      - ./apps.json:/etc/relay/apps.json
    restart: unless-stopped</code></pre>

<div class="cloud-nudge" style="margin-top:36px;">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.</span>
    <a href="{{ route('register') }}">Start Free &rarr;</a>
</div>
@endsection
