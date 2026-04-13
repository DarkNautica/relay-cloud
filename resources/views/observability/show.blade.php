@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">{{ $project->name }} &mdash; Observability</h1>
        <p class="page-sub">Event timeline, delivery tracking, and latency metrics.</p>
    </div>
    <div style="display:flex;align-items:center;gap:12px;">
        <div id="obs-live" style="display:flex;align-items:center;gap:6px;font-size:11px;font-weight:600;color:var(--success);text-transform:uppercase;letter-spacing:0.05em;">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--success);animation:pulse 2s ease-in-out infinite;"></span>
            Live
        </div>
        <button class="btn btn-secondary btn-sm" onclick="obsRefresh()" id="obs-refresh-btn">Refresh</button>
    </div>
</div>

<!-- Metrics -->
<div class="stats-grid" style="margin-bottom:24px;">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Total Events</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        </div>
        <div class="stat-value" id="m-events">{{ number_format($metrics['total_events'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Total Deliveries</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        </div>
        <div class="stat-value" id="m-deliveries">{{ number_format($metrics['total_deliveries'] ?? 0) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Avg Latency</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        </div>
        <div class="stat-value" id="m-avg-latency">{{ $metrics['avg_latency_ms'] ?? 0 }}<span style="font-size:14px;font-weight:400;color:var(--text-tertiary);">ms</span></div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">p95 Latency</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 2v10l6.93 4"/></svg>
        </div>
        <div class="stat-value" id="m-p95-latency">{{ $metrics['p95_latency_ms'] ?? 0 }}<span style="font-size:14px;font-weight:400;color:var(--text-tertiary);">ms</span></div>
    </div>
</div>

<!-- Event Timeline -->
<div class="card" style="margin-bottom:24px;">
    <div class="card-header" style="flex-wrap:wrap;gap:10px;">
        <span class="card-title">Event Timeline</span>
        <div id="obs-new-badge" style="display:none;padding:3px 10px;border-radius:12px;font-size:11px;font-weight:600;background:rgba(124,58,237,0.15);color:var(--accent-light);cursor:pointer;" onclick="obsScrollTop()"></div>
        <div style="margin-left:auto;display:flex;align-items:center;gap:8px;">
            <input type="text" id="obs-channel-filter" class="form-input" placeholder="Filter by channel..." style="width:200px;padding:5px 10px;font-size:12px;">
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table>
            <thead>
                <tr>
                    <th style="width:120px;">Time</th>
                    <th>Event</th>
                    <th>Channel</th>
                    <th style="width:100px;">Delivered</th>
                    <th style="width:100px;">Latency</th>
                    <th style="width:140px;"></th>
                </tr>
            </thead>
            <tbody id="obs-tbody">
            </tbody>
        </table>
    </div>

    <div id="obs-empty" style="display:none;text-align:center;padding:48px 20px;">
        <svg style="width:36px;height:36px;color:var(--text-tertiary);margin:0 auto 14px;opacity:0.4;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        <h3 style="font-size:14px;font-weight:600;color:var(--text-primary);margin-bottom:6px;">No events recorded yet</h3>
        <p style="font-size:13px;color:var(--text-tertiary);margin-bottom:16px;">Publish your first event to see it appear here.</p>
        <pre style="text-align:left;display:inline-block;background:var(--bg-base);border:1px solid var(--border);border-radius:8px;padding:14px 18px;font-family:var(--font-mono);font-size:12px;line-height:1.7;color:var(--text-secondary);">curl -X POST {{ config('services.relay.url') }}/apps/{{ $project->app_id }}/events \
  -H "Authorization: Bearer {{ $project->app_secret }}" \
  -H "Content-Type: application/json" \
  -d '{"event":"test","channel":"demo","data":"{\"hello\":\"world\"}"}'</pre>
    </div>

    <div id="obs-load-more" style="display:none;padding:12px;text-align:center;border-top:1px solid var(--border);">
        <button class="btn btn-secondary btn-sm" onclick="obsLoadMore()">Load more</button>
    </div>
</div>

<script>
(function() {
    const BASE = '/projects/{{ $project->id }}/observability';
    const CSRF = '{{ csrf_token() }}';
    let nextCursor = @json($initial['next_cursor']);
    let knownIds = new Set();
    let polling = true;
    let channelFilter = '';

    // Render initial events
    const initialEvents = @json($initial['events']);
    if (initialEvents.length === 0) {
        document.getElementById('obs-empty').style.display = '';
    } else {
        initialEvents.forEach(ev => renderEvent(ev, false));
        if (nextCursor) document.getElementById('obs-load-more').style.display = '';
    }

    function renderEvent(ev, prepend) {
        const id = ev.id || ev.timestamp || Math.random().toString(36);
        if (knownIds.has(id)) return;
        knownIds.add(id);

        document.getElementById('obs-empty').style.display = 'none';

        const tbody = document.getElementById('obs-tbody');
        const tr = document.createElement('tr');
        tr.style.cursor = 'default';
        tr.dataset.eventId = id;

        const ts = ev.timestamp ? new Date(ev.timestamp) : new Date();
        const timeStr = ts.toLocaleTimeString('en-US', {hour12:false}) + '.' + String(ts.getMilliseconds()).padStart(3,'0');

        let latency = ev.latency_ms ?? ev.latency ?? ev.duration_ms ?? null;
        // If latency came in microseconds, convert
        if (latency === null && ev.latency_us != null) latency = Math.round(ev.latency_us / 1000);
        let latencyColor = 'var(--text-tertiary)';
        let latencyText = '—';
        if (latency !== null && latency !== undefined) {
            latencyText = latency + 'ms';
            if (latency < 20) latencyColor = 'var(--success)';
            else if (latency <= 100) latencyColor = 'var(--warning)';
            else latencyColor = 'var(--danger)';
        }

        let delivered = ev.delivered_count ?? ev.subscribers ?? ev.subscriber_count ?? ev.delivery_count ?? ev.num_recipients ?? null;
        // Also check inside a nested deliveries/stats object
        if (delivered === null && ev.stats) delivered = ev.stats.delivered ?? ev.stats.recipients ?? null;
        const deliveredText = delivered !== null && delivered !== undefined ? delivered + ' client' + (delivered !== 1 ? 's' : '') : '—';

        tr.innerHTML =
            '<td style="font-family:var(--font-mono);font-size:12px;color:var(--text-tertiary);">' + esc(timeStr) + '</td>' +
            '<td style="font-family:var(--font-mono);font-size:13px;color:var(--accent-light);font-weight:500;">' + esc(ev.event || ev.name || '') + '</td>' +
            '<td style="font-size:12px;color:var(--text-secondary);">' + esc(ev.channel || '') + '</td>' +
            '<td style="font-size:12px;color:var(--text-secondary);">' + deliveredText + '</td>' +
            '<td style="font-size:12px;font-weight:600;color:' + latencyColor + ';">' + latencyText + '</td>' +
            '<td style="width:140px;" onclick="event.stopPropagation()">' +
                '<div style="display:flex;gap:4px;">' +
                    '<button class="btn btn-secondary btn-sm" style="padding:3px 8px;font-size:10px;" onclick="obsToggleDetail(\'' + esc(id) + '\')">Details</button>' +
                    '<button class="btn btn-secondary btn-sm" style="padding:3px 8px;font-size:10px;" onclick="obsReplay(\'' + esc(id) + '\',this)">Replay</button>' +
                '</div>' +
            '</td>';

        const detailTr = document.createElement('tr');
        detailTr.id = 'obs-detail-' + id;
        detailTr.style.display = 'none';
        detailTr.style.cursor = 'default';

        let payloadStr = '';
        try {
            let payload = ev.data || '';
            let parsed = typeof payload === 'string' ? JSON.parse(payload) : payload;
            // Double-encoded: relay server sends JSON-encoded string inside JSON response
            if (typeof parsed === 'string') {
                parsed = JSON.parse(parsed);
            }
            payloadStr = JSON.stringify(parsed, null, 2);
        } catch(e) {
            payloadStr = typeof ev.data === 'string' ? ev.data : JSON.stringify(ev.data);
        }

        const publishedAt = ev.published_at || ev.timestamp || '';
        const socketIds = ev.socket_ids || ev.recipients || [];

        detailTr.innerHTML =
            '<td colspan="6" style="padding:0;background:var(--bg-base);">' +
                '<div style="padding:14px 20px;">' +
                    '<div style="display:flex;gap:20px;flex-wrap:wrap;">' +
                        '<div style="flex:1;min-width:280px;">' +
                            '<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">' +
                                '<span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:var(--text-tertiary);">Payload</span>' +
                                '<button class="btn btn-secondary btn-sm" style="padding:2px 8px;font-size:10px;" onclick="navigator.clipboard.writeText(this.closest(\'div\').querySelector(\'pre\').textContent)">Copy</button>' +
                            '</div>' +
                            '<pre style="font-family:var(--font-mono);font-size:11px;line-height:1.6;color:var(--text-secondary);background:rgba(0,0,0,0.3);padding:10px 12px;border-radius:6px;overflow-x:auto;white-space:pre-wrap;word-break:break-all;margin:0;"><code class="language-json">' + esc(payloadStr) + '</code></pre>' +
                        '</div>' +
                        '<div style="min-width:200px;">' +
                            '<div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:var(--text-tertiary);margin-bottom:6px;">Details</div>' +
                            '<div style="font-size:12px;color:var(--text-secondary);line-height:1.8;">' +
                                '<div><strong style="color:var(--text-tertiary);">Published:</strong> ' + esc(publishedAt) + '</div>' +
                                (socketIds.length ? '<div><strong style="color:var(--text-tertiary);">Recipients:</strong> ' + socketIds.map(s => '<code style="font-family:var(--font-mono);font-size:11px;background:var(--bg-elevated);padding:1px 4px;border-radius:3px;">' + esc(s) + '</code>').join(' ') + '</div>' : '') +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</td>';

        if (prepend) {
            tbody.prepend(detailTr);
            tbody.prepend(tr);
        } else {
            tbody.appendChild(tr);
            tbody.appendChild(detailTr);
        }

        // Highlight code blocks in the new row
        detailTr.querySelectorAll('pre code').forEach(el => {
            if (window.hljs) hljs.highlightElement(el);
        });
    }

    function esc(str) {
        if (str === null || str === undefined) return '';
        const d = document.createElement('div');
        d.textContent = String(str);
        return d.innerHTML;
    }

    // Details toggle
    window.obsToggleDetail = function(id) {
        const row = document.getElementById('obs-detail-' + id);
        if (row) row.style.display = row.style.display === 'none' ? '' : 'none';
    };

    // Replay
    window.obsReplay = async function(id, btn) {
        if (!confirm('Replay this event? It will be re-delivered to all current subscribers.')) return;
        btn.disabled = true;
        btn.textContent = '...';
        try {
            const res = await fetch(BASE + '/events/' + id + '/replay', {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json'}
            });
            const data = await res.json();
            btn.textContent = data.ok ? 'Sent' : 'Failed';
        } catch(e) {
            btn.textContent = 'Error';
        }
        setTimeout(() => { btn.textContent = 'Replay'; btn.disabled = false; }, 2000);
    };

    // Channel filter
    let filterTimeout;
    document.getElementById('obs-channel-filter').addEventListener('input', (e) => {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(() => {
            channelFilter = e.target.value.trim();
            // Clear and reload
            document.getElementById('obs-tbody').innerHTML = '';
            knownIds.clear();
            nextCursor = null;
            fetchEvents();
        }, 400);
    });

    // Load more
    window.obsLoadMore = async function() {
        if (!nextCursor) return;
        await fetchEvents(nextCursor);
    };

    async function fetchEvents(cursor) {
        let url = BASE + '/events?limit=25';
        if (cursor) url += '&cursor=' + encodeURIComponent(cursor);
        if (channelFilter) url += '&channel=' + encodeURIComponent(channelFilter);
        try {
            const res = await fetch(url);
            const data = await res.json();
            nextCursor = data.next_cursor;
            document.getElementById('obs-load-more').style.display = nextCursor ? '' : 'none';
            if (data.events.length === 0 && knownIds.size === 0) {
                document.getElementById('obs-empty').style.display = '';
            }
            data.events.forEach(ev => renderEvent(ev, !cursor));
        } catch(e) {
            //
        }
    }

    // Refresh
    window.obsRefresh = function() {
        document.getElementById('obs-tbody').innerHTML = '';
        knownIds.clear();
        nextCursor = null;
        fetchEvents();
        refreshMetrics();
    };

    // Scroll-to-top for new events badge
    window.obsScrollTop = function() {
        document.getElementById('obs-new-badge').style.display = 'none';
        window.scrollTo({top: document.getElementById('obs-tbody').offsetTop - 100, behavior: 'smooth'});
    };

    // Auto-poll every 5 seconds
    let newCount = 0;
    setInterval(async () => {
        if (!polling) return;
        let url = BASE + '/events?limit=10';
        if (channelFilter) url += '&channel=' + encodeURIComponent(channelFilter);
        try {
            const res = await fetch(url);
            const data = await res.json();
            const prevSize = knownIds.size;
            data.events.forEach(ev => renderEvent(ev, true));
            const added = knownIds.size - prevSize;
            if (added > 0) {
                const scrolledDown = window.scrollY > 400;
                if (scrolledDown) {
                    newCount += added;
                    const badge = document.getElementById('obs-new-badge');
                    badge.textContent = newCount + ' new event' + (newCount > 1 ? 's' : '');
                    badge.style.display = '';
                } else {
                    newCount = 0;
                    document.getElementById('obs-new-badge').style.display = 'none';
                }
            }
        } catch(e) {}
    }, 5000);

    // Clear new badge on scroll up
    window.addEventListener('scroll', () => {
        if (window.scrollY < 400) {
            newCount = 0;
            document.getElementById('obs-new-badge').style.display = 'none';
        }
    });

    // Metrics refresh
    async function refreshMetrics() {
        try {
            const res = await fetch(BASE + '/events?limit=0');
            // Metrics come from a separate endpoint in a real implementation
            // For now, just poll events to keep the timeline fresh
        } catch(e) {}
    }
})();
</script>
@endsection
