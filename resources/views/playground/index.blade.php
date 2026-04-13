@extends('layouts.docs')
@section('title', 'Relay Playground')
@section('no-sidebar', true)

@section('content')
<style>
    .pg-wrap{max-width:1100px;margin:0 auto;padding:40px 24px 60px}
    .pg-hero{text-align:center;margin-bottom:32px}
    .pg-hero h1{font-size:28px;font-weight:700;letter-spacing:-0.02em;margin-bottom:8px}
    .pg-hero p{font-size:15px;color:var(--t2);max-width:520px;margin:0 auto}
    .pg-panels{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .pg-panel{background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;overflow:hidden;box-shadow:0 1px 3px rgba(0,0,0,0.5),0 4px 16px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.04)}
    .pg-panel-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:8px}
    .pg-panel-header h3{font-size:14px;font-weight:600}
    .pg-panel-header svg{width:16px;height:16px;color:var(--accent-l)}
    .pg-panel-body{padding:20px}
    .pg-form-group{margin-bottom:14px}
    .pg-label{display:block;font-size:12px;font-weight:500;color:var(--t3);margin-bottom:4px;text-transform:uppercase;letter-spacing:0.04em}
    .pg-input{width:100%;padding:8px 12px;background:var(--bg);border:1px solid var(--border);border-radius:6px;color:var(--t1);font-size:13px;font-family:var(--sans);outline:none;transition:border-color 150ms}
    .pg-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-glow)}
    .pg-textarea{resize:vertical;min-height:100px;font-family:var(--mono);font-size:12px;line-height:1.6}
    .pg-btn{padding:9px 18px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--sans);transition:all 150ms}
    .pg-btn:active{transform:scale(0.98)}
    .pg-btn-primary{background:var(--accent);color:#fff}
    .pg-btn-primary:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
    .pg-btn-primary:disabled{opacity:0.5;cursor:not-allowed}
    .pg-presets{display:flex;gap:6px;margin-top:12px;flex-wrap:wrap}
    .pg-preset{padding:5px 12px;border-radius:6px;font-size:12px;font-weight:500;background:rgba(255,255,255,0.04);border:1px solid var(--border);color:var(--t2);cursor:pointer;font-family:var(--sans);transition:all 150ms}
    .pg-preset:hover{border-color:var(--accent);color:var(--accent-l)}
    .pg-note{font-size:11px;color:var(--t3);margin-top:12px;text-align:center}
    .pg-events{max-height:420px;overflow-y:auto;padding:0 20px 20px}
    .pg-events::-webkit-scrollbar{width:4px}.pg-events::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}
    .pg-event{padding:10px 12px;border:1px solid var(--border);border-radius:8px;margin-bottom:8px;background:var(--bg)}
    .pg-event-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px}
    .pg-event-name{font-size:12px;font-weight:600;color:var(--accent-l)}
    .pg-event-time{font-size:10px;color:var(--t3);font-family:var(--mono)}
    .pg-event-channel{font-size:11px;color:var(--t3);margin-bottom:4px}
    .pg-event-payload{font-family:var(--mono);font-size:11px;line-height:1.5;color:var(--t2);background:rgba(0,0,0,0.3);padding:8px 10px;border-radius:4px;overflow-x:auto;white-space:pre-wrap;word-break:break-all}
    .pg-empty{text-align:center;padding:60px 20px;color:var(--t3);font-size:13px}
    .pg-empty svg{width:32px;height:32px;margin:0 auto 12px;opacity:0.4}
    .pg-status{display:flex;align-items:center;gap:6px;font-size:11px;font-weight:500}
    .pg-status-dot{width:6px;height:6px;border-radius:50%}
    .pg-cta{text-align:center;margin-top:32px;padding:24px;background:linear-gradient(135deg,rgba(124,58,237,0.06),rgba(124,58,237,0.02));border:1px solid rgba(124,58,237,0.15);border-radius:12px}
    .pg-cta p{font-size:14px;color:var(--t2);margin-bottom:12px}
    .pg-cta strong{color:var(--t1)}
    .pg-cta a{display:inline-block;padding:10px 24px;border-radius:8px;font-size:13px;font-weight:600;background:var(--accent);color:#fff;transition:all 150ms}
    .pg-cta a:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
    @media(max-width:768px){.pg-panels{grid-template-columns:1fr}}
</style>

<div class="pg-wrap">
    <div class="pg-hero">
        <h1>Relay Playground</h1>
        <p>Connect to a live Relay server, publish events, and watch them arrive in real time. No account required.</p>
    </div>

    <div class="pg-panels">
        <!-- Publisher -->
        <div class="pg-panel">
            <div class="pg-panel-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                <h3>Publish an Event</h3>
            </div>
            <div class="pg-panel-body">
                <div class="pg-form-group">
                    <label class="pg-label" for="pg-channel">Channel</label>
                    <input type="text" id="pg-channel" class="pg-input" value="playground-demo">
                </div>
                <div class="pg-form-group">
                    <label class="pg-label" for="pg-event">Event Name</label>
                    <input type="text" id="pg-event" class="pg-input" value="message.sent">
                </div>
                <div class="pg-form-group">
                    <label class="pg-label" for="pg-payload">Payload (JSON)</label>
                    <textarea id="pg-payload" class="pg-input pg-textarea">{"message": "Hello from Relay!", "user": "anonymous"}</textarea>
                </div>
                <button class="pg-btn pg-btn-primary" id="pg-publish" style="width:100%;">Publish Event</button>
                <div class="pg-presets">
                    <button class="pg-preset" onclick="pgPreset('message.sent', {message:'Hello from Relay!',user:'anonymous'})">Say Hello</button>
                    <button class="pg-preset" onclick="pgPreset('notification.new', {title:'New signup',body:'A user just signed up',priority:'high'})">Send Notification</button>
                    <button class="pg-preset" onclick="pgPreset('chart.update', {metric:'active_users',value:Math.floor(Math.random()*500)+100,timestamp:new Date().toISOString()})">Update Chart</button>
                </div>
                <div class="pg-note">Shared demo server &mdash; please be respectful</div>
            </div>
        </div>

        <!-- Event Stream -->
        <div class="pg-panel">
            <div class="pg-panel-header">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                <h3>Live Event Stream</h3>
                <div style="margin-left:auto;" id="pg-connection-status">
                    <div class="pg-status">
                        <div class="pg-status-dot" style="background:var(--t3);" id="pg-dot"></div>
                        <span id="pg-status-text" style="color:var(--t3);">Connecting...</span>
                    </div>
                </div>
            </div>
            <div class="pg-events" id="pg-events">
                <div class="pg-empty" id="pg-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                    Events you publish will appear here in real time
                </div>
            </div>
        </div>
    </div>

    <div class="pg-cta">
        <p><strong>Want your own private channels?</strong> Create a free account for private channels, authentication, and your own Channel Inspector.</p>
        <a href="{{ route('register') }}">Create Free Account</a>
    </div>
</div>

<script src="https://js.pusher.com/7.6.0/pusher.min.js"></script>
<script>
(function() {
    const appKey = @json($config['app_key']);
    const host = @json($config['host']);
    const port = @json($config['port']);

    if (!appKey) {
        document.getElementById('pg-status-text').textContent = 'Not configured';
        return;
    }

    window.playgroundPusher = new Pusher(appKey, {
        wsHost: host,
        wsPort: port,
        wssPort: port,
        forceTLS: true,
        disableStats: true,
        enabledTransports: ['ws', 'wss'],
        cluster: 'relay'
    });

    const pusher = window.playgroundPusher;
    const dot = document.getElementById('pg-dot');
    const statusText = document.getElementById('pg-status-text');

    pusher.connection.bind('connected', () => {
        dot.style.background = 'var(--success)';
        statusText.style.color = 'var(--success)';
        statusText.textContent = 'Connected';
    });
    pusher.connection.bind('disconnected', () => {
        dot.style.background = 'var(--danger)';
        statusText.style.color = 'var(--danger)';
        statusText.textContent = 'Disconnected';
    });
    pusher.connection.bind('error', (err) => {
        console.error('Playground WebSocket error:', err);
        dot.style.background = 'var(--danger)';
        statusText.style.color = 'var(--danger)';
        statusText.textContent = 'Connection failed';
    });

    let currentChannel = null;
    function subscribe(channelName) {
        if (currentChannel) {
            pusher.unsubscribe(currentChannel.name);
        }
        currentChannel = pusher.subscribe(channelName);
        currentChannel.bind_global((event, data) => {
            if (event.startsWith('pusher:')) return;
            addEvent(event, channelName, data);
        });
    }

    subscribe(document.getElementById('pg-channel').value);

    document.getElementById('pg-channel').addEventListener('change', (e) => {
        subscribe(e.target.value);
    });

    function addEvent(event, channel, data) {
        const empty = document.getElementById('pg-empty');
        if (empty) empty.remove();

        const container = document.getElementById('pg-events');
        const el = document.createElement('div');
        el.className = 'pg-event';
        const time = new Date().toLocaleTimeString();
        el.innerHTML = `
            <div class="pg-event-header">
                <span class="pg-event-name">${escHtml(event)}</span>
                <span class="pg-event-time">${time}</span>
            </div>
            <div class="pg-event-channel">#${escHtml(channel)}</div>
            <div class="pg-event-payload">${escHtml(JSON.stringify(data, null, 2))}</div>
        `;
        container.prepend(el);
    }

    function escHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    // Publish
    document.getElementById('pg-publish').addEventListener('click', async () => {
        const btn = document.getElementById('pg-publish');
        const channel = document.getElementById('pg-channel').value;
        const event = document.getElementById('pg-event').value;
        const payload = document.getElementById('pg-payload').value;

        if (!channel || !event || !payload) return;

        btn.disabled = true;
        btn.textContent = 'Publishing...';

        try {
            const res = await fetch('{{ route("playground.publish") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ channel, event, payload }),
            });
            if (!res.ok) {
                const data = await res.json();
                alert(data.errors ? Object.values(data.errors).flat().join('\n') : 'Failed to publish');
            }
        } catch (e) {
            alert('Failed to publish event');
        }

        btn.disabled = false;
        btn.textContent = 'Publish Event';
    });

    window.pgPreset = function(event, data) {
        document.getElementById('pg-event').value = event;
        document.getElementById('pg-payload').value = JSON.stringify(data, null, 2);
    };
})();
</script>
@endsection
