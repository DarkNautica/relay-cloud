@extends('layouts.docs')

@section('title', 'Using Relay with Next.js')

@section('content')
<div class="prose">
    <h1>Using Relay with Next.js</h1>
    <p>Connect your Next.js app to a Relay WebSocket server using the Pusher JS client.</p>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSockets with a free tier. Connect in 60 seconds.
        <a href="/register">Try free &rarr;</a>
    </div>

    <h2>Prerequisites</h2>
    <ul>
        <li>A running Relay server</li>
        <li>Your App Key and Host</li>
    </ul>

    <div class="step">
        <span class="step-num">1</span>
        <h2>Install</h2>
    </div>

    <pre><code class="language-bash">npm install pusher-js</code></pre>

    <div class="step">
        <span class="step-num">2</span>
        <h2>Create lib/relay.ts</h2>
    </div>

    <pre><code class="language-typescript">import Pusher from 'pusher-js';

const relay = new Pusher(process.env.NEXT_PUBLIC_RELAY_APP_KEY!, {
  wsHost: process.env.NEXT_PUBLIC_RELAY_HOST ?? 'ws.relaycloud.dev',
  wsPort: Number(process.env.NEXT_PUBLIC_RELAY_PORT ?? 443),
  wssPort: Number(process.env.NEXT_PUBLIC_RELAY_PORT ?? 443),
  forceTLS: true,
  disableStats: true,
  enabledTransports: ['ws', 'wss'],
  cluster: 'relay',
});

export default relay;</code></pre>

    <div class="step">
        <span class="step-num">3</span>
        <h2>Environment Variables</h2>
    </div>

    <p>Add these to your <code>.env.local</code> file:</p>

    <pre><code class="language-bash">NEXT_PUBLIC_RELAY_APP_KEY=your-app-key
NEXT_PUBLIC_RELAY_HOST=ws.relaycloud.dev
NEXT_PUBLIC_RELAY_PORT=443</code></pre>

    <div class="step">
        <span class="step-num">4</span>
        <h2>Subscribe in a Component</h2>
    </div>

    <pre><code class="language-typescript">'use client';

import { useEffect, useState } from 'react';
import relay from '@/lib/relay';

export default function LiveFeed() {
  const [messages, setMessages] = useState&lt;string[]&gt;([]);

  useEffect(() =&gt; {
    const channel = relay.subscribe('public-feed');

    channel.bind('message.sent', (data: { message: string }) =&gt; {
      setMessages(prev =&gt; [...prev, data.message]);
    });

    return () =&gt; {
      relay.unsubscribe('public-feed');
    };
  }, []);

  return (
    &lt;ul&gt;
      {messages.map((msg, i) =&gt; &lt;li key={i}&gt;{msg}&lt;/li&gt;)}
    &lt;/ul&gt;
  );
}</code></pre>

    <div class="step">
        <span class="step-num">5</span>
        <h2>Private Channels</h2>
    </div>

    <p>To subscribe to private channels, your Next.js app needs an auth endpoint that validates the user and returns a signed token. Configure the <code>authEndpoint</code> option on the Pusher client to point to your backend route. See the <a href="/docs/api-reference">API reference</a> for full details on the authentication flow.</p>

    <div class="note">
        <p>Relay is fully Pusher-protocol compatible. Any Pusher JS tutorial applies directly &mdash; just swap the host and disable stats.</p>
    </div>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.
        <a href="/register">Start free &rarr;</a>
    </div>
</div>
@endsection
