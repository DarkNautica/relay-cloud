@extends('layouts.app')
@section('breadcrumb', 'Projects / ' . $project->name)

@section('content')
<div style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;gap:6px;font-size:13px;color:var(--text-tertiary);margin-bottom:10px;">
        <a href="{{ route('projects.index') }}" style="color:var(--accent-light);display:flex;align-items:center;gap:4px;">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;"><polyline points="15 18 9 12 15 6"/></svg>
            Projects
        </a>
        <span>/</span>
        <span style="color:var(--text-secondary);">{{ $project->name }}</span>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <h1 class="page-title">{{ $project->name }}</h1>
        <span class="badge {{ $project->is_active ? 'badge-active' : 'badge-inactive' }}">
            <span class="badge-dot"></span>{{ $project->is_active ? 'Active' : 'Inactive' }}
        </span>
    </div>
</div>

<!-- Live Stats -->
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Subscribers</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="stat-value" id="live-subs">{{ $liveStats['connections'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Channels</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        </div>
        <div class="stat-value" id="live-chan">{{ $liveStats['channels'] }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Max Connections</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
        </div>
        <div class="stat-value">{{ number_format($project->max_connections) }}</div>
    </div>
</div>

<!-- Rotated credential reveal -->
@if(session('rotated_key'))
<div style="border:1px solid var(--accent);border-radius:10px;padding:16px 20px;margin-bottom:16px;background:rgba(124,58,237,0.06);">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span style="font-size:13px;font-weight:600;color:var(--accent-light);">New App Key &mdash; Save this now</span>
    </div>
    <input type="text" readonly value="{{ session('rotated_key') }}" style="width:100%;padding:8px 12px;background:var(--bg-base);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-family:var(--font-mono);font-size:12px;margin-bottom:6px;outline:none;" onclick="this.select();copyToClipboard(this.value)">
    <div style="font-size:11px;color:var(--text-tertiary);">This value will not be shown again after you navigate away from this page.</div>
</div>
@endif
@if(session('rotated_secret'))
<div style="border:1px solid var(--accent);border-radius:10px;padding:16px 20px;margin-bottom:16px;background:rgba(124,58,237,0.06);">
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="var(--accent-light)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:16px;height:16px;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <span style="font-size:13px;font-weight:600;color:var(--accent-light);">New App Secret &mdash; Save this now</span>
    </div>
    <input type="text" readonly value="{{ session('rotated_secret') }}" style="width:100%;padding:8px 12px;background:var(--bg-base);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-family:var(--font-mono);font-size:12px;margin-bottom:6px;outline:none;" onclick="this.select();copyToClipboard(this.value)">
    <div style="font-size:11px;color:var(--text-tertiary);">This value will not be shown again after you navigate away from this page.</div>
</div>
@endif

<!-- Credentials -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-header"><span class="card-title">API Credentials</span></div>
    <div style="padding:16px 24px;">
        <div class="cred-row">
            <div class="cred-info">
                <div class="cred-label">App ID</div>
                <div class="cred-value">{{ $project->app_id }}</div>
            </div>
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_id }}',this)" style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span class="tooltip">Copied!</span>
            </button>
        </div>
        <div class="cred-row">
            <div class="cred-info">
                <div class="cred-label">App Key</div>
                <div class="cred-value">{{ $project->app_key }}</div>
            </div>
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_key }}',this)" style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span class="tooltip">Copied!</span>
            </button>
            <button class="icon-btn" onclick="document.getElementById('dlg-rotate-key').showModal()" title="Rotate">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            </button>
        </div>
        <div class="cred-row">
            <div class="cred-info">
                <div class="cred-label">App Secret</div>
                <div class="cred-value cred-blurred" id="secret-value">{{ $project->app_secret }}</div>
            </div>
            <button class="icon-btn" onclick="toggleSecret()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>
            <button class="icon-btn" onclick="copyToClipboard('{{ $project->app_secret }}',this)" style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span class="tooltip">Copied!</span>
            </button>
            <button class="icon-btn" onclick="document.getElementById('dlg-rotate-secret').showModal()" title="Rotate">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            </button>
        </div>
        <div class="cred-row" style="margin-bottom:0;">
            <div class="cred-info">
                <div class="cred-label">WebSocket URL</div>
                <div class="cred-value">wss://ws.relaycloud.dev/app/{{ $project->app_key }}</div>
            </div>
            <button class="icon-btn" onclick="copyToClipboard('wss://ws.relaycloud.dev/app/{{ $project->app_key }}',this)" style="position:relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                <span class="tooltip">Copied!</span>
            </button>
        </div>
    </div>
</div>

<!-- Rotate Key Dialog -->
<dialog id="dlg-rotate-key" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:12px;padding:24px;max-width:440px;color:var(--text-primary);box-shadow:0 4px 24px rgba(0,0,0,0.5);">
    <div style="font-size:16px;font-weight:600;margin-bottom:8px;">Rotate App Key</div>
    <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin-bottom:18px;">
        Rotating your App Key will immediately invalidate the current value.
        Any application using the old credential will disconnect. This cannot be undone.
    </p>
    <div style="display:flex;gap:8px;justify-content:flex-end;">
        <button class="btn btn-secondary btn-sm" onclick="document.getElementById('dlg-rotate-key').close()">Cancel</button>
        <form method="POST" action="{{ route('projects.rotate-key', $project) }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Rotate Now</button>
        </form>
    </div>
</dialog>

<!-- Rotate Secret Dialog -->
<dialog id="dlg-rotate-secret" style="background:var(--bg-elevated);border:1px solid var(--border);border-radius:12px;padding:24px;max-width:440px;color:var(--text-primary);box-shadow:0 4px 24px rgba(0,0,0,0.5);">
    <div style="font-size:16px;font-weight:600;margin-bottom:8px;">Rotate App Secret</div>
    <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin-bottom:18px;">
        Rotating your App Secret will immediately invalidate the current value.
        Any application using the old credential will disconnect. This cannot be undone.
    </p>
    <div style="display:flex;gap:8px;justify-content:flex-end;">
        <button class="btn btn-secondary btn-sm" onclick="document.getElementById('dlg-rotate-secret').close()">Cancel</button>
        <form method="POST" action="{{ route('projects.rotate-secret', $project) }}">
            @csrf
            <button type="submit" class="btn btn-danger btn-sm">Rotate Now</button>
        </form>
    </div>
</dialog>

<!-- Quick Start -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-header"><span class="card-title">Quick Start</span></div>
    <div class="code-tabs">
        <button class="code-tab active" onclick="showTab('laravel',this)">Laravel</button>
        <button class="code-tab" onclick="showTab('node',this)">Node.js</button>
        <button class="code-tab" onclick="showTab('js',this)">JavaScript</button>
    </div>
    <div class="code-block" id="tab-laravel"><span class="code-lang">PHP</span><pre>// config/broadcasting.php
'relay' => [
    'driver' => 'pusher',
    'key' => '{{ $project->app_key }}',
    'secret' => '{{ $project->app_secret }}',
    'app_id' => '{{ $project->app_id }}',
    'options' => [
        'host' => 'ws.relaycloud.dev',
        'port' => 443,
        'scheme' => 'https',
    ],
],</pre></div>
    <div class="code-block" id="tab-node" style="display:none;"><span class="code-lang">Node</span><pre>import Pusher from 'pusher';

const pusher = new Pusher({
    appId: '{{ $project->app_id }}',
    key: '{{ $project->app_key }}',
    secret: '{{ $project->app_secret }}',
    host: 'ws.relaycloud.dev',
    port: 443,
    useTLS: true,
});

pusher.trigger('my-channel', 'my-event', {
    message: 'Hello from Relay!'
});</pre></div>
    <div class="code-block" id="tab-js" style="display:none;"><span class="code-lang">JS</span><pre>import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

const echo = new Echo({
    broadcaster: 'pusher',
    key: '{{ $project->app_key }}',
    wsHost: 'ws.relaycloud.dev',
    wssPort: 443,
    forceTLS: true,
    disableStats: true,
});

echo.channel('my-channel')
    .listen('my-event', (e) => console.log(e));</pre></div>
</div>

<!-- Event Log -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-header">
        <span class="card-title" style="display:flex;align-items:center;gap:8px;">
            <span class="status-dot online" style="width:5px;height:5px;"></span> Live Event Log
        </span>
        <span style="font-size:11px;color:var(--text-tertiary);">Auto-refreshes 5s</span>
    </div>
    @if(empty($eventLog))
        <div class="empty-state" style="padding:36px 20px;">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <h3>No events yet</h3>
            <p>Events appear here when clients connect and send messages.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead><tr><th>Timestamp</th><th>Channel</th><th>Event</th></tr></thead>
                <tbody>
                @foreach($eventLog as $event)
                    <tr style="cursor:default;">
                        <td style="font-family:var(--font-mono);font-size:11px;color:var(--text-tertiary);">{{ $event['timestamp'] ?? '-' }}</td>
                        <td style="font-family:var(--font-mono);font-size:12px;">{{ $event['channel'] ?? '-' }}</td>
                        <td>{{ $event['event'] ?? '-' }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Danger Zone -->
<div class="danger-zone">
    <h3>Danger Zone</h3>
    <p>Permanently delete this project and revoke all API credentials.</p>
    <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project? This cannot be undone.')">
        @csrf @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm">Delete Project</button>
    </form>
</div>

<script>
function showTab(t,btn){
    document.querySelectorAll('.code-block').forEach(e=>e.style.display='none');
    document.querySelectorAll('.code-tab').forEach(e=>e.classList.remove('active'));
    document.getElementById('tab-'+t).style.display='block'; btn.classList.add('active');
}
setInterval(async()=>{
    try{
        const r=await fetch('/api/dashboard/stats',{credentials:'same-origin'});
        if(!r.ok)return; const d=await r.json(), ps=d.projects[{{ $project->id }}];
        if(ps){document.getElementById('live-subs').textContent=ps.connections;document.getElementById('live-chan').textContent=ps.channels;}
    }catch(e){}
},5000);
</script>
@endsection
