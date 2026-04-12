@extends('layouts.app')
@section('breadcrumb', 'Projects / New')

@section('content')
<div style="margin-bottom:24px;">
    <h1 class="page-title">Create Project</h1>
    <p class="page-sub">Set up a new Relay WebSocket project.</p>
</div>

<div class="card card-pad" style="max-width:520px;">
    @if(!$canCreate)
        <div class="alert alert-warning">
            You've reached your plan limit of {{ $maxProjects }} project{{ $maxProjects === 1 ? '' : 's' }}.
            <a href="{{ route('billing.index') }}" style="color:var(--accent-light);text-decoration:underline;margin-left:4px;">Upgrade</a>
        </div>
    @elseif($currentCount >= $maxProjects - 1 && $currentCount > 0)
        <div class="alert alert-warning">
            Using {{ $currentCount }} of {{ $maxProjects }} project slots. This will be your last.
        </div>
    @endif

    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="name">Project Name</label>
            <input type="text" name="name" id="name" class="form-input" placeholder="My Awesome App" value="{{ old('name') }}" required autofocus {{ !$canCreate ? 'disabled' : '' }}>
            @error('name')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div style="display:flex;gap:10px;">
            <button type="submit" class="btn btn-primary" {{ !$canCreate ? 'disabled' : '' }}>Create Project</button>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
