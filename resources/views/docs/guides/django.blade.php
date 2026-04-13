@extends('layouts.docs')

@section('title', 'Using Relay with Django')

@section('content')
<div class="prose">
    <h1>Using Relay with Django</h1>
    <p>Broadcast WebSocket events from your Django app using Relay's Pusher-compatible HTTP API.</p>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSockets with a free tier. Connect in 60 seconds.
        <a href="{{ route('register') }}">Try free &rarr;</a>
    </div>

    <h2>Prerequisites</h2>
    <ul>
        <li>Python 3.8+</li>
        <li>A running Relay server</li>
        <li>Your App Key, Secret, and App ID</li>
    </ul>

    <div class="step">
        <span class="step-num">1</span>
        <h2>Install the Pusher SDK</h2>
    </div>

    <pre><code class="language-bash">pip install pusher</code></pre>

    <div class="step">
        <span class="step-num">2</span>
        <h2>Configure settings.py</h2>
    </div>

    <pre><code class="language-python">RELAY_APP_ID  = os.environ.get('RELAY_APP_ID')
RELAY_APP_KEY = os.environ.get('RELAY_APP_KEY')
RELAY_APP_SECRET = os.environ.get('RELAY_APP_SECRET')
RELAY_HOST    = os.environ.get('RELAY_HOST', 'ws.relaycloud.dev')
RELAY_PORT    = int(os.environ.get('RELAY_PORT', 443))
RELAY_SCHEME  = os.environ.get('RELAY_SCHEME', 'https')</code></pre>

    <div class="step">
        <span class="step-num">3</span>
        <h2>Create relay.py Client Utility</h2>
    </div>

    <pre><code class="language-python">import pusher
from django.conf import settings

relay_client = pusher.Pusher(
    app_id=settings.RELAY_APP_ID,
    key=settings.RELAY_APP_KEY,
    secret=settings.RELAY_APP_SECRET,
    host=settings.RELAY_HOST,
    port=settings.RELAY_PORT,
    ssl=(settings.RELAY_SCHEME == 'https'),
)</code></pre>

    <div class="step">
        <span class="step-num">4</span>
        <h2>Trigger from a Django View</h2>
    </div>

    <pre><code class="language-python">from relay import relay_client
from django.http import JsonResponse

def send_message(request):
    message = request.POST.get('message')
    relay_client.trigger('public-feed', 'message.sent', {
        'message': message,
        'user': request.user.username,
    })
    return JsonResponse({'status': 'sent'})</code></pre>

    <div class="step">
        <span class="step-num">5</span>
        <h2>Trigger from a Celery Task</h2>
    </div>

    <pre><code class="language-python">from celery import shared_task
from relay import relay_client

@shared_task
def notify_users(message):
    relay_client.trigger('public-feed', 'notification', {
        'message': message,
    })</code></pre>

    <div class="step">
        <span class="step-num">6</span>
        <h2>Connect the Frontend</h2>
    </div>

    <p>On the client side, use the <a href="https://github.com/pusher/pusher-js">Pusher JS client</a> to subscribe to channels and listen for events. Point the client at your Relay host and app key. See the <a href="/docs/guides/nextjs">Next.js guide</a> for a full frontend example.</p>

    <div class="note">
        <p>The Pusher Python SDK supports custom hosts natively. Relay is fully compatible with all Pusher SDK methods including batch triggers and channel queries.</p>
    </div>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.
        <a href="{{ route('register') }}">Start free &rarr;</a>
    </div>
</div>
@endsection
