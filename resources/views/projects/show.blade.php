@extends('layouts.app')

@section('header', $project->name)

@section('content')
<style>
    @keyframes pulse-green { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    .live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--success); animation: pulse-green 2s ease-in-out infinite; display: inline-block; }
</style>

<div class="page-header">
    <div>
        <div style="display:flex; align-items:center; gap:12px;">
            <h1 class="page-title">{{ $project->name }}</h1>
            @if($project->is_active)
                <span class="badge badge-active"><span class="badge-dot"></span>Active</span>
            @else
                <span class="badge badge-inactive"><span class="badge-dot"></span>Inactive</span>
            @endif
        </div>
        <p class="page-subtitle">Manage credentials and settings for this project.</p>
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary btn-sm">Back to Projects</a>
</div>

<!-- Live Stats -->
<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label" style="display:flex;align-items:center;gap:8px;">
            <span class="live-dot"></span> Active Subscribers
        </div>
        <div class="stat-value" id="live-subscribers">{{ $liveStats['subscriber_count'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label" style="display:flex;align-items:center;gap:8px;">
            <span class="live-dot"></span> Active Channels
        </div>
        <div class="stat-value" id="live-channels">{{ $liveStats['channels'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Max Connections</div>
        <div class="stat-value">{{ number_format($project->max_connections) }}</div>
    </div>
</div>

<!-- Credentials -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <h2 class="card-title">API Credentials</h2>
    </div>

    <div class="cred-row">
        <div style="flex:1; min-width:0;">
            <div class="cred-label">App ID</div>
            <div class="cred-value">{{ $project->app_id }}</div>
        </div>
        <div class="cred-actions">
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_id }}')" title="Copy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
    </div>

    <div class="cred-row">
        <div style="flex:1; min-width:0;">
            <div class="cred-label">App Key</div>
            <div class="cred-value">{{ $project->app_key }}</div>
        </div>
        <div class="cred-actions">
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_key }}')" title="Copy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
    </div>

    <div class="cred-row">
        <div style="flex:1; min-width:0;">
            <div class="cred-label">App Secret</div>
            <div class="cred-value cred-blurred" id="secret-value">{{ $project->app_secret }}</div>
        </div>
        <div class="cred-actions">
            <button class="icon-btn" onclick="toggleSecret()" title="Reveal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_secret }}')" title="Copy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
    </div>
</div>

<!-- Connection String -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <h2 class="card-title">Connection</h2>
    </div>
    <div class="cred-row">
        <div style="flex:1; min-width:0;">
            <div class="cred-label">WebSocket URL</div>
            <div class="cred-value">ws://relay.yourdomain.com/app/{{ $project->app_key }}</div>
        </div>
        <div class="cred-actions">
            <button class="icon-btn" onclick="copyToClipboard('ws://relay.yourdomain.com/app/{{ $project->app_key }}')" title="Copy">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
            </button>
        </div>
    </div>
</div>

<!-- Quick Start -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <h2 class="card-title">Quick Start</h2>
    </div>
    <div class="code-tabs">
        <button class="code-tab active" onclick="showTab('laravel', this)">Laravel</button>
        <button class="code-tab" onclick="showTab('node', this)">Node.js</button>
        <button class="code-tab" onclick="showTab('javascript', this)">JavaScript</button>
    </div>
    <div class="code-block" id="tab-laravel">
        <pre>// config/broadcasting.php
'relay' => [
    'driver' => 'pusher',
    'key' => '{{ $project->app_key }}',
    'secret' => '{{ $project->app_secret }}',
    'app_id' => '{{ $project->app_id }}',
    'options' => [
        'host' => 'relay.yourdomain.com',
        'port' => 6001,
        'scheme' => 'http',
    ],
],</pre>
    </div>
    <div class="code-block" id="tab-node" style="display:none;">
        <pre>import Pusher from 'pusher';

const pusher = new Pusher({
    appId: '{{ $project->app_id }}',
    key: '{{ $project->app_key }}',
    secret: '{{ $project->app_secret }}',
    host: 'relay.yourdomain.com',
    port: 6001,
    useTLS: false,
});

pusher.trigger('my-channel', 'my-event', {
    message: 'Hello from Relay!'
});</pre>
    </div>
    <div class="code-block" id="tab-javascript" style="display:none;">
        <pre>import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ $project->app_key }}',
    wsHost: 'relay.yourdomain.com',
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

echo.channel('my-channel')
    .listen('my-event', (e) => {
        console.log(e);
    });</pre>
    </div>
</div>

<!-- Live Event Log -->
<div class="card" style="margin-bottom: 20px;">
    <div class="card-header">
        <h2 class="card-title" style="display:flex;align-items:center;gap:10px;">
            <span class="live-dot"></span> Live Event Log
        </h2>
        <span style="font-size:12px;color:var(--text-muted);">Auto-refreshes every 5s</span>
    </div>
    <div class="table-wrap" id="event-log-wrap">
        @if(empty($eventLog))
            <div class="empty-state" style="padding: 40px 20px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                <h3>No events yet</h3>
                <p>Events will appear here when clients connect and send messages.</p>
            </div>
        @else
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>Channel</th>
                        <th>Event</th>
                    </tr>
                </thead>
                <tbody id="event-log-body">
                    @foreach($eventLog as $event)
                    <tr>
                        <td style="color:var(--text-muted);font-size:13px;">{{ $event['timestamp'] ?? '—' }}</td>
                        <td><code style="font-size:12px;">{{ $event['channel'] ?? '—' }}</code></td>
                        <td>{{ $event['event'] ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<!-- Danger Zone -->
<div class="danger-zone">
    <h3>Danger Zone</h3>
    <p>Deleting this project will permanently revoke all API credentials. This action cannot be undone.</p>
    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Are you sure you want to delete this project? This cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Project</button>
    </form>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text);
}

function toggleSecret() {
    document.getElementById('secret-value').classList.toggle('cred-blurred');
}

function showTab(tab, btn) {
    document.querySelectorAll('.code-block').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.code-tab').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + tab).style.display = 'block';
    btn.classList.add('active');
}

setInterval(async () => {
    try {
        const res = await fetch('/api/dashboard/stats', { credentials: 'same-origin' });
        if (!res.ok) return;
        const data = await res.json();
        const ps = data.projects[{{ $project->id }}];
        if (ps) {
            document.getElementById('live-subscribers').textContent = Number(ps.subscriber_count).toLocaleString();
            document.getElementById('live-channels').textContent = Number(ps.channels).toLocaleString();
        }
    } catch (e) {}
}, 5000);
</script>
@endsection
