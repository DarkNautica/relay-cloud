@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<style>
    @keyframes pulse-green { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
    .status-dot-online { width: 8px; height: 8px; border-radius: 50%; background: var(--success); animation: pulse-green 2s ease-in-out infinite; }
    .status-dot-offline { width: 8px; height: 8px; border-radius: 50%; background: var(--danger); }
    .server-status {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 10px 18px; border-radius: 8px; font-size: 14px; font-weight: 500;
        margin-bottom: 24px;
    }
    .server-online { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.2); color: var(--success); }
    .server-offline { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); color: var(--danger); }
</style>

<div style="margin-bottom: 32px;">
    <h1 class="page-title">Welcome back, {{ Auth::user()->name }}</h1>
    <p class="page-subtitle">Here's an overview of your Relay Cloud projects.</p>
</div>

<!-- Server Status -->
<div id="server-status" class="server-status {{ $serverOnline ? 'server-online' : 'server-offline' }}">
    <div class="{{ $serverOnline ? 'status-dot-online' : 'status-dot-offline' }}" id="status-dot"></div>
    <span id="status-text">{{ $serverOnline ? 'Relay Server Online' : 'Relay Server Offline — start relay-server' }}</span>
</div>

<div class="stats-row">
    <div class="stat-card">
        <div class="stat-label">Active Connections</div>
        <div class="stat-value" id="stat-connections">{{ number_format($serverStats['connections']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Active Channels</div>
        <div class="stat-value" id="stat-channels">{{ number_format($serverStats['channels']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Projects</div>
        <div class="stat-value">{{ $projects->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Current Plan</div>
        <div class="stat-value" style="font-size: 24px;">{{ $planName }}</div>
    </div>
</div>

@if($projects->isEmpty())
    <div class="card">
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            <h3>Create your first project</h3>
            <p>Get started by creating a project to get your API credentials.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">New Project</a>
        </div>
    </div>
@else
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Recent Projects</h2>
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">New Project</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>App ID</th>
                        <th>Connections</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $project)
                    <tr style="cursor:pointer" onclick="window.location='{{ route('projects.show', $project) }}'">
                        <td style="font-weight: 600;">{{ $project->name }}</td>
                        <td><code style="font-size:12px;color:var(--text-muted)">{{ $project->app_id }}</code></td>
                        <td>{{ number_format($project->max_connections) }}</td>
                        <td>
                            @if($project->is_active)
                                <span class="badge badge-active"><span class="badge-dot"></span>Active</span>
                            @else
                                <span class="badge badge-inactive"><span class="badge-dot"></span>Inactive</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted)">{{ $project->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

<script>
setInterval(async () => {
    try {
        const res = await fetch('/api/dashboard/stats', { credentials: 'same-origin' });
        if (!res.ok) return;
        const data = await res.json();

        const statusEl = document.getElementById('server-status');
        const dotEl = document.getElementById('status-dot');
        const textEl = document.getElementById('status-text');

        if (data.server_online) {
            statusEl.className = 'server-status server-online';
            dotEl.className = 'status-dot-online';
            textEl.textContent = 'Relay Server Online';
        } else {
            statusEl.className = 'server-status server-offline';
            dotEl.className = 'status-dot-offline';
            textEl.textContent = 'Relay Server Offline — start relay-server';
        }

        document.getElementById('stat-connections').textContent = Number(data.server.connections).toLocaleString();
        document.getElementById('stat-channels').textContent = Number(data.server.channels).toLocaleString();
    } catch (e) {}
}, 5000);
</script>
@endsection
