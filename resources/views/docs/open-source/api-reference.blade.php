@extends('layouts.docs')
@section('title', 'API Reference')

@section('content')
<div class="cloud-nudge">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Relay Cloud</strong> provides a fully managed API with zero configuration. No signature headaches, no server to maintain.</span>
    <a href="{{ route('register') }}">Try Relay Cloud &rarr;</a>
</div>

<h1>HTTP API Reference</h1>
<p class="subtitle">All endpoints prefixed with <code>/apps/{appId}</code>.</p>

<h2>Authentication</h2>

<p>All API requests require a signature for authentication. The signature is generated using your app secret with <strong>HMAC-SHA256</strong>. The signature is computed over a string composed of the HTTP method, request path, and query parameters.</p>

<p>Server SDKs (Laravel Broadcasting, Pusher PHP, pusher-js, etc.) handle signature generation automatically. You only need to provide your app ID, key, and secret.</p>

<h2>Publishing</h2>

<h3>POST /apps/{appId}/events</h3>

<p>Publish a single event to one or more channels.</p>

<h4>Request Body</h4>

<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>name</code></td>
            <td>string</td>
            <td>Yes</td>
            <td>Event name</td>
        </tr>
        <tr>
            <td><code>channel</code></td>
            <td>string</td>
            <td>Yes*</td>
            <td>Channel to publish to</td>
        </tr>
        <tr>
            <td><code>channels</code></td>
            <td>string[]</td>
            <td>Yes*</td>
            <td>Multiple channels to publish to</td>
        </tr>
        <tr>
            <td><code>data</code></td>
            <td>string</td>
            <td>Yes</td>
            <td>Event payload (JSON-encoded string)</td>
        </tr>
        <tr>
            <td><code>socket_id</code></td>
            <td>string</td>
            <td>No</td>
            <td>Exclude a socket from receiving the event</td>
        </tr>
    </tbody>
</table>

<div class="note">
    <strong>Note:</strong> One of <code>channel</code> or <code>channels</code> is required. You cannot provide both.
</div>

<h4>Example Request</h4>

<pre><code class="language-http">POST /apps/my-app/events
Content-Type: application/json

{
  "name": "user.updated",
  "channel": "private-users",
  "data": "{\"id\":1,\"name\":\"Jayden\"}"
}</code></pre>

<h3>POST /apps/{appId}/events/batch</h3>

<p>Publish multiple events in a single request. Each event in the batch follows the same format as the single event endpoint.</p>

<h4>Example Request</h4>

<pre><code class="language-http">POST /apps/my-app/events/batch
Content-Type: application/json

{
  "batch": [
    {
      "name": "user.updated",
      "channel": "private-users",
      "data": "{\"id\":1,\"name\":\"Jayden\"}"
    },
    {
      "name": "order.created",
      "channel": "private-orders",
      "data": "{\"order_id\":42,\"total\":19.99}"
    }
  ]
}</code></pre>

<h2>Channels</h2>

<h3>GET /apps/{appId}/channels</h3>

<p>Returns a list of active channels.</p>

<h4>Query Parameters</h4>

<table>
    <thead>
        <tr>
            <th>Parameter</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>filter_by_prefix</code></td>
            <td>Filter channels by prefix, e.g. <code>presence-</code></td>
        </tr>
        <tr>
            <td><code>info</code></td>
            <td>Comma-separated list of attributes to return, e.g. <code>user_count,subscription_count</code></td>
        </tr>
    </tbody>
</table>

<h4>Example Response</h4>

<pre><code class="language-json">{
  "channels": {
    "private-users": { "subscription_count": 12 },
    "presence-chat": { "user_count": 5 }
  }
}</code></pre>

<h3>GET /apps/{appId}/channels/{channelName}</h3>

<p>Returns information about a specific channel, including whether it is occupied and its subscription count.</p>

<h4>Example Response</h4>

<pre><code class="language-json">{
  "occupied": true,
  "subscription_count": 8
}</code></pre>

<h3>GET /apps/{appId}/channels/{channelName}/users</h3>

<p>Returns a list of users subscribed to a presence channel. Only works with <code>presence-</code> channels.</p>

<h4>Example Response</h4>

<pre><code class="language-json">{
  "users": [
    { "id": "1" },
    { "id": "2" },
    { "id": "3" }
  ]
}</code></pre>

<h2>Authentication Endpoint</h2>

<h3>POST /apps/{appId}/auth</h3>

<p>Authenticates a user for a private or presence channel. Your backend calls this endpoint (or the server SDK handles it) to authorize a client subscription.</p>

<h4>Request Body</h4>

<table>
    <thead>
        <tr>
            <th>Field</th>
            <th>Type</th>
            <th>Required</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>socket_id</code></td>
            <td>string</td>
            <td>Yes</td>
            <td>The socket ID of the connecting client</td>
        </tr>
        <tr>
            <td><code>channel_name</code></td>
            <td>string</td>
            <td>Yes</td>
            <td>The channel the client wants to subscribe to</td>
        </tr>
    </tbody>
</table>

<h2>Other Endpoints</h2>

<h3>GET /apps/{appId}/channels/{channelName}/events</h3>

<p>Returns event history for a channel. Only available when event history is enabled (<code>RELAY_HISTORY_ENABLED=true</code>).</p>

<h3>GET /apps/{appId}/events/log</h3>

<p>Returns the server event log. Useful for debugging and monitoring event flow.</p>

<h3>GET /stats</h3>

<p>Returns server-wide statistics including the total number of active connections and channels.</p>

<h4>Example Response</h4>

<pre><code class="language-json">{
  "connections": 142,
  "channels": 37
}</code></pre>

<h3>GET /health</h3>

<p>Returns <code>200 OK</code> if the server is healthy. Use this endpoint for load balancer health checks and uptime monitoring.</p>

<h2>WebSocket Protocol</h2>

<h3>Connection URL</h3>

<p>Connect to the WebSocket server at:</p>

<pre><code class="language-plaintext">ws://localhost:6001/app/{appKey}</code></pre>

<p>Replace <code>{appKey}</code> with your app key. For TLS connections, use <code>wss://</code> instead.</p>

<h3>Server &rarr; Client Events</h3>

<table>
    <thead>
        <tr>
            <th>Event</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>pusher:connection_established</code></td>
            <td>Connection successful. Payload contains <code>socket_id</code>.</td>
        </tr>
        <tr>
            <td><code>pusher:error</code></td>
            <td>An error occurred. Payload contains the error message.</td>
        </tr>
        <tr>
            <td><code>pusher_internal:subscription_succeeded</code></td>
            <td>Channel subscription confirmed.</td>
        </tr>
        <tr>
            <td><code>pusher_internal:member_added</code></td>
            <td>A member joined a presence channel.</td>
        </tr>
        <tr>
            <td><code>pusher_internal:member_removed</code></td>
            <td>A member left a presence channel.</td>
        </tr>
    </tbody>
</table>

<h3>Client &rarr; Server Events</h3>

<table>
    <thead>
        <tr>
            <th>Event</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>pusher:subscribe</code></td>
            <td>Subscribe to a channel.</td>
        </tr>
        <tr>
            <td><code>pusher:unsubscribe</code></td>
            <td>Unsubscribe from a channel.</td>
        </tr>
        <tr>
            <td><code>pusher:ping</code></td>
            <td>Keepalive ping to maintain the connection.</td>
        </tr>
    </tbody>
</table>

<div class="cloud-nudge" style="margin-top:36px;">
    <span class="cloud-nudge-icon">&#9729;</span>
    <span><strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket API instantly. Start free, upgrade when you grow.</span>
    <a href="{{ route('register') }}">Start Free &rarr;</a>
</div>
@endsection
