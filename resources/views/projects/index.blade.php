@extends('layouts.app')

@section('header', 'Projects')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Projects</h1>
        <p class="page-subtitle">Manage your Relay WebSocket projects.</p>
    </div>
    <a href="{{ route('projects.create') }}" class="btn btn-primary">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:18px;height:18px"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        New Project
    </a>
</div>

@if($projects->isEmpty())
    <div class="card">
        <div class="empty-state">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 2 7 12 12 22 7 12 2"/><polyline points="2 17 12 22 22 17"/><polyline points="2 12 12 17 22 12"/></svg>
            <h3>No projects yet</h3>
            <p>Create your first project to get API credentials and start using Relay.</p>
            <a href="{{ route('projects.create') }}" class="btn btn-primary">Create Project</a>
        </div>
    </div>
@else
    <div class="project-grid">
        @foreach($projects as $project)
        <a href="{{ route('projects.show', $project) }}" class="card" style="transition: border-color 0.15s; display:block;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                <h3 style="font-size:16px; font-weight:600;">{{ $project->name }}</h3>
                @if($project->is_active)
                    <span class="badge badge-active"><span class="badge-dot"></span>Active</span>
                @else
                    <span class="badge badge-inactive"><span class="badge-dot"></span>Inactive</span>
                @endif
            </div>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted)">App ID</span>
                    <code style="color:var(--text-muted)">{{ $project->app_id }}</code>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted)">Max Connections</span>
                    <span>{{ number_format($project->max_connections) }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; font-size:13px;">
                    <span style="color:var(--text-muted)">Created</span>
                    <span style="color:var(--text-muted)">{{ $project->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif
@endsection
