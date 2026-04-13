@extends('layouts.app')
@section('breadcrumb', 'Projects')

@section('content')
<div class="page-header">
    <div>
        <div style="display:flex;align-items:center;gap:10px;">
            <h1 class="page-title">Projects</h1>
            <span style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:4px;background:var(--bg-elevated);color:var(--text-tertiary);border:1px solid var(--border);">{{ $projects->count() }}</span>
        </div>
        <p class="page-sub">Manage your Relay WebSocket projects.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="card">
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
            <h3>No projects yet</h3>
            <p>Create your first project to get API credentials and start using Relay.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary btn-sm">Create Project</a>
        </div>
    </div>
@else
    <div class="card">
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
                                        View
                                    </a>
                                    <button class="ctx-item" onclick="copyToClipboard('{{ $project->app_key }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                                        Copy App Key
                                    </button>
                                    <button class="ctx-item" onclick="copyToClipboard('{{ $project->app_secret }}')">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                                        Copy App Secret
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
    </div>
@endif
@endsection
