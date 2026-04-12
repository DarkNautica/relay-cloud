@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Webhooks</h1>
        <p class="page-sub">Receive real-time notifications when events happen on your projects.</p>
    </div>
</div>

@if(session('new_secret'))
    <div class="alert alert-warning" style="flex-direction:column;align-items:flex-start;">
        <strong>Save this secret — it won't be shown again.</strong>
        <code style="font-family:var(--font-mono);font-size:12px;margin-top:6px;background:var(--bg-base);padding:6px 12px;border-radius:6px;display:block;word-break:break-all;">{{ session('new_secret') }}</code>
        <div style="font-size:12px;color:var(--text-tertiary);margin-top:4px;">Use this to verify webhook signatures with HMAC-SHA256.</div>
    </div>
@endif

<!-- Existing Webhooks -->
<div class="card" style="margin-bottom:20px;">
    <div class="card-header"><span class="card-title">Active Webhooks</span></div>
    @if($webhooks->isEmpty())
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <h3>No webhooks configured</h3>
            <p>Add a webhook below to receive event notifications.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead><tr><th>URL</th><th>Project</th><th>Events</th><th>Last Triggered</th><th></th></tr></thead>
                <tbody>
                @foreach($webhooks as $wh)
                    <tr style="cursor:default;">
                        <td style="font-family:var(--font-mono);font-size:12px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $wh->url }}</td>
                        <td>{{ $wh->project ? $wh->project->name : 'All projects' }}</td>
                        <td style="font-size:12px;color:var(--text-tertiary);">{{ count($wh->events) }} events</td>
                        <td style="font-size:12px;color:var(--text-tertiary);">{{ $wh->last_triggered_at ? $wh->last_triggered_at->diffForHumans() : 'Never' }}</td>
                        <td style="width:120px;" onclick="event.stopPropagation()">
                            <div style="display:flex;gap:6px;">
                                <form method="POST" action="{{ route('webhooks.test', $wh) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding:4px 10px;font-size:11px;">Test</button>
                                </form>
                                <form method="POST" action="{{ route('webhooks.destroy', $wh) }}" onsubmit="return confirm('Delete this webhook?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 10px;font-size:11px;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Add Webhook -->
<div class="card card-pad" style="max-width:600px;">
    <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Add Webhook</div>
    <form method="POST" action="{{ route('webhooks.store') }}">
        @csrf
        <div class="form-group">
            <label class="form-label" for="url">Endpoint URL</label>
            <input type="url" name="url" id="url" class="form-input" placeholder="https://yourapp.com/webhooks/relay" required value="{{ old('url') }}">
            @error('url')<div class="form-error">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label" for="project_id">Project</label>
            <select name="project_id" id="project_id" class="form-input">
                <option value="">All projects</option>
                @foreach($projects as $p)
                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Events</label>
            @error('events')<div class="form-error">{{ $message }}</div>@enderror
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;margin-top:4px;">
                @foreach(['connection.created' => 'New connection', 'connection.removed' => 'Connection closed', 'channel.occupied' => 'Channel occupied', 'channel.vacated' => 'Channel vacated', 'member.added' => 'Member joined', 'member.removed' => 'Member left'] as $event => $label)
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;color:var(--text-secondary);cursor:pointer;">
                    <input type="checkbox" name="events[]" value="{{ $event }}" style="accent-color:var(--accent);width:14px;height:14px;">
                    {{ $label }}
                </label>
                @endforeach
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Create Webhook</button>
    </form>
</div>
@endsection
