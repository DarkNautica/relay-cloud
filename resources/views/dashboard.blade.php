@extends('layouts.app')
@section('breadcrumb', 'Dashboard')

@section('server-status')
<div class="top-bar-status {{ $serverOnline ? 'online' : 'offline' }}" id="srv-status">
    <div class="status-dot {{ $serverOnline ? 'online' : 'offline' }}" id="srv-dot"></div>
    <span id="srv-text">{{ $serverOnline ? 'Online' : 'Offline' }}</span>
</div>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-sub">Welcome back, {{ Auth::user()->name }}</p>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Active Connections</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M1.42 9a16 16 0 0 1 21.16 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
        </div>
        <div class="stat-value" id="s-conn">{{ number_format($serverStats['connections']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Active Channels</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
        </div>
        <div class="stat-value" id="s-chan">{{ number_format($serverStats['channels']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Total Projects</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="stat-value">{{ $projects->count() }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Current Plan</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        </div>
        <div class="stat-value" style="font-size:22px;">{{ $planName }}</div>
        @if($currentPlan === 'hobby')
            <div class="stat-sub"><a href="{{ route('billing.index') }}">Upgrade plan</a></div>
        @endif
    </div>
</div>

<div class="two-col">
    <!-- Projects Table -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">Projects</span>
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                New
            </a>
        </div>
        @if($projects->isEmpty())
            <div class="empty-state">
                <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                <h3>No projects yet</h3>
                <p>Create a project to get your API credentials.</p>
                <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Create Project</a>
            </div>
        @else
            <div style="overflow-x:auto;">
                <table>
                    <thead><tr><th>Name</th><th class="col-status">Status</th><th class="col-conn">Connections</th><th class="col-created">Created</th><th class="col-actions"></th></tr></thead>
                    <tbody>
                    @foreach($projects as $project)
                        <tr onclick="window.location='{{ route('projects.show', $project) }}'">
                            <td>
                                <div style="font-weight:600;color:var(--text-primary);font-size:13px;">{{ $project->name }}</div>
                                <div style="font-family:var(--font-mono);font-size:11px;color:var(--text-tertiary);margin-top:2px;">{{ $project->app_id }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $project->is_active ? 'badge-active' : 'badge-inactive' }}">
                                    <span class="badge-dot"></span>{{ $project->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="font-family:var(--font-mono);font-size:12px;">{{ $projectStats[$project->app_id]['connections'] ?? 0 }} / {{ number_format($project->max_connections) }}</td>
                            <td style="color:var(--text-tertiary);font-size:12px;">{{ $project->created_at->diffForHumans() }}</td>
                            <td style="width:40px;" onclick="event.stopPropagation()">
                                <div class="ctx-menu">
                                    <button class="ctx-trigger" onclick="toggleCtx(this,event)">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
                                    </button>
                                    <div class="ctx-dropdown">
                                        <a href="{{ route('projects.show', $project) }}" class="ctx-item">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                                            View Project
                                        </a>
                                        <button class="ctx-item" onclick="copyToClipboard('{{ $project->app_key }}')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                            Copy App Key
                                        </button>
                                        <div class="ctx-sep"></div>
                                        @if($project->is_active)
                                        <button class="ctx-item" onclick="fetch('{{ route('projects.pause', $project) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload())">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="6" y="4" width="4" height="16"/><rect x="14" y="4" width="4" height="16"/></svg>
                                            Pause
                                        </button>
                                        @else
                                        <button class="ctx-item" onclick="fetch('{{ route('projects.resume', $project) }}',{method:'POST',headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(()=>location.reload())">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                                            Resume
                                        </button>
                                        @endif
                                        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ctx-item danger">
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Server Status -->
    <div class="card card-pad">
        <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Server Status</div>
        <div style="display:flex;flex-direction:column;gap:14px;">
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-tertiary);">Status</span>
                <span id="srv-label" style="font-weight:500;color:{{ $serverOnline ? 'var(--success)' : 'var(--danger)' }}">{{ $serverOnline ? 'Online' : 'Offline' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-tertiary);">Connections</span>
                <span style="font-family:var(--font-mono);font-size:12px;" id="srv-conn">{{ $serverStats['connections'] }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-tertiary);">Channels</span>
                <span style="font-family:var(--font-mono);font-size:12px;" id="srv-chan">{{ $serverStats['channels'] }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-tertiary);">Region</span>
                <span style="font-size:12px;">Helsinki</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:13px;">
                <span style="color:var(--text-tertiary);">Endpoint</span>
                <span style="font-family:var(--font-mono);font-size:11px;color:var(--text-tertiary);">ws.relaycloud.dev</span>
            </div>
        </div>
    </div>
</div>

<script>
setInterval(async()=>{
    try{
        const r=await fetch('/api/dashboard/stats',{credentials:'same-origin'});
        if(!r.ok)return; const d=await r.json();
        document.getElementById('s-conn').textContent=Number(d.server.connections).toLocaleString();
        document.getElementById('s-chan').textContent=Number(d.server.channels).toLocaleString();
        document.getElementById('srv-conn').textContent=d.server.connections;
        document.getElementById('srv-chan').textContent=d.server.channels;
        const dot=document.getElementById('srv-dot'),txt=document.getElementById('srv-text'),st=document.getElementById('srv-status'),lb=document.getElementById('srv-label');
        if(d.server_online){dot.className='status-dot online';txt.textContent='Online';st.className='top-bar-status online';lb.style.color='var(--success)';lb.textContent='Online';}
        else{dot.className='status-dot offline';txt.textContent='Offline';st.className='top-bar-status offline';lb.style.color='var(--danger)';lb.textContent='Offline';}
    }catch(e){}
},5000);
</script>
@endsection
