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
    <div class="card-header"><span class="card-title">Webhooks</span></div>
    @if($webhooks->isEmpty())
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <h3>No webhooks configured</h3>
            <p>Add a webhook below to receive event notifications.</p>
        </div>
    @else
        <div style="overflow-x:auto;">
            <table>
                <thead><tr><th>URL</th><th>Project</th><th>Events</th><th>Status</th><th>Last Triggered</th><th></th></tr></thead>
                <tbody>
                @foreach($webhooks as $wh)
                    <tr style="cursor:default;">
                        <td style="font-family:var(--font-mono);font-size:12px;max-width:240px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $wh->url }}</td>
                        <td>{{ $wh->project ? $wh->project->name : 'All projects' }}</td>
                        <td style="font-size:12px;color:var(--text-tertiary);">{{ count($wh->events) }} events</td>
                        <td>
                            @if($wh->is_paused)
                                <span class="badge" style="background:rgba(245,158,11,0.1);color:var(--warning);">
                                    <span class="badge-dot" style="background:var(--warning);"></span> Paused
                                </span>
                            @else
                                <span class="badge badge-active"><span class="badge-dot"></span> Active</span>
                            @endif
                        </td>
                        <td style="font-size:12px;color:var(--text-tertiary);">{{ $wh->last_triggered_at ? $wh->last_triggered_at->diffForHumans() : 'Never' }}</td>
                        <td style="width:220px;" onclick="event.stopPropagation()">
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <form method="POST" action="{{ route('webhooks.test', $wh) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm" style="padding:4px 10px;font-size:11px;">Test</button>
                                </form>
                                @if($wh->is_paused)
                                    <form method="POST" action="{{ route('webhooks.resume', $wh) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm" style="padding:4px 10px;font-size:11px;">Resume</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('webhooks.pause', $wh) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary btn-sm" style="padding:4px 10px;font-size:11px;">Pause</button>
                                    </form>
                                @endif
                                <button type="button" class="btn btn-secondary btn-sm" style="padding:4px 10px;font-size:11px;" onclick="toggleDeliveries({{ $wh->id }})">Deliveries</button>
                                <form method="POST" action="{{ route('webhooks.destroy', $wh) }}" onsubmit="return confirm('Delete this webhook?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 10px;font-size:11px;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @if($wh->is_paused && $wh->failure_count >= 3)
                        <tr style="cursor:default;">
                            <td colspan="6" style="padding:8px 20px;font-size:12px;color:var(--warning);background:rgba(245,158,11,0.04);">
                                Paused after 3 consecutive delivery failures.
                                @if($wh->last_failed_at)
                                    Last failure: {{ $wh->last_failed_at->diffForHumans() }}.
                                @endif
                            </td>
                        </tr>
                    @endif
                    <tr id="deliveries-{{ $wh->id }}" style="display:none;cursor:default;">
                        <td colspan="6" style="padding:0;background:var(--bg-base);">
                            <div style="padding:12px 20px;">
                                <div style="font-size:12px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.04em;margin-bottom:8px;">Recent Deliveries</div>
                                <div id="deliveries-content-{{ $wh->id }}" style="font-size:12px;color:var(--text-tertiary);">Loading...</div>
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

<script>
function toggleDeliveries(id) {
    const row = document.getElementById('deliveries-' + id);
    if (row.style.display === 'none') {
        row.style.display = '';
        loadDeliveries(id);
    } else {
        row.style.display = 'none';
    }
}

async function loadDeliveries(id) {
    const container = document.getElementById('deliveries-content-' + id);
    try {
        const res = await fetch('/webhooks/' + id + '/deliveries');
        const data = await res.json();
        if (data.length === 0) {
            container.innerHTML = '<div style="color:var(--text-tertiary);padding:8px 0;">No deliveries yet.</div>';
            return;
        }
        let html = '<table style="width:100%;font-size:12px;"><thead><tr>' +
            '<th style="padding:6px 10px;">Time</th><th style="padding:6px 10px;">Event</th>' +
            '<th style="padding:6px 10px;">Status</th><th style="padding:6px 10px;">HTTP</th>' +
            '<th style="padding:6px 10px;"></th></tr></thead><tbody>';
        data.forEach(d => {
            const time = new Date(d.created_at).toLocaleString();
            const statusColor = d.status === 'delivered' ? 'var(--success)' : d.status === 'retrying' ? 'var(--warning)' : 'var(--danger)';
            html += '<tr style="cursor:default;">' +
                '<td style="padding:6px 10px;color:var(--text-tertiary);">' + escHtml(time) + '</td>' +
                '<td style="padding:6px 10px;color:var(--text-secondary);font-family:var(--font-mono);">' + escHtml(d.event) + '</td>' +
                '<td style="padding:6px 10px;"><span style="color:' + statusColor + ';font-weight:600;">' + escHtml(d.status) + '</span></td>' +
                '<td style="padding:6px 10px;color:var(--text-tertiary);">' + (d.response_status || '—') + '</td>' +
                '<td style="padding:6px 10px;"><button class="btn btn-secondary btn-sm" style="padding:2px 8px;font-size:10px;" onclick="toggleDetail(this,'+d.id+','+JSON.stringify(JSON.stringify(d))+')">View</button></td>' +
                '</tr>';
            html += '<tr id="detail-' + d.id + '" style="display:none;cursor:default;">' +
                '<td colspan="5" style="padding:8px 10px;background:rgba(0,0,0,0.2);">' +
                '<div style="margin-bottom:6px;font-size:11px;font-weight:600;color:var(--text-tertiary);">REQUEST PAYLOAD</div>' +
                '<pre style="font-family:var(--font-mono);font-size:11px;color:var(--text-secondary);white-space:pre-wrap;word-break:break-all;margin-bottom:8px;">' + escHtml(JSON.stringify(d.payload, null, 2)) + '</pre>' +
                '<div style="font-size:11px;font-weight:600;color:var(--text-tertiary);">RESPONSE BODY</div>' +
                '<pre style="font-family:var(--font-mono);font-size:11px;color:var(--text-secondary);white-space:pre-wrap;word-break:break-all;">' + escHtml(d.response_body || '(empty)') + '</pre>' +
                '</td></tr>';
        });
        html += '</tbody></table>';
        container.innerHTML = html;
    } catch (e) {
        container.innerHTML = '<div style="color:var(--danger);">Failed to load deliveries.</div>';
    }
}

function toggleDetail(btn, id, dataStr) {
    const row = document.getElementById('detail-' + id);
    row.style.display = row.style.display === 'none' ? '' : 'none';
}

function escHtml(str) {
    if (str === null || str === undefined) return '';
    const d = document.createElement('div');
    d.textContent = String(str);
    return d.innerHTML;
}
</script>
@endsection
