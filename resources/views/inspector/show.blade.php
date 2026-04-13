@extends('layouts.app')

@section('content')
<style>
    .insp-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #1a1a22}
    .insp-title{font-size:18px;font-weight:700;letter-spacing:-0.01em}
    .insp-title span{color:var(--text-tertiary);font-weight:400}
    .insp-controls{display:flex;align-items:center;gap:12px}
    .insp-live{display:flex;align-items:center;gap:6px;font-size:12px;font-weight:600;padding:5px 12px;border-radius:6px;background:rgba(16,185,129,0.08);color:var(--success);border:1px solid rgba(16,185,129,0.15)}
    .insp-live.offline{background:rgba(79,78,92,0.1);color:var(--text-tertiary);border-color:var(--border)}
    .insp-live-dot{width:6px;height:6px;border-radius:50%;background:var(--success);animation:pulse 2s ease-in-out infinite}
    .insp-live.offline .insp-live-dot{background:var(--text-tertiary);animation:none}
    .insp-refresh{width:32px;height:32px;display:flex;align-items:center;justify-content:center;background:none;border:1px solid var(--border);border-radius:6px;cursor:pointer;color:var(--text-tertiary);transition:all 150ms}
    .insp-refresh:hover{border-color:var(--border-strong);color:var(--text-secondary)}
    .insp-refresh svg{width:14px;height:14px}
    .insp-banner{padding:10px 16px;border-radius:8px;font-size:12px;font-weight:500;margin-bottom:16px;display:none;align-items:center;gap:8px;background:rgba(245,158,11,0.08);border:1px solid rgba(245,158,11,0.15);color:var(--warning)}
    .insp-banner.show{display:flex}

    .insp-layout{display:grid;grid-template-columns:280px 1fr;gap:0;min-height:calc(100vh - 200px);border:1px solid var(--border);border-radius:12px;overflow:hidden;background:var(--bg-surface)}
    @media(max-width:768px){.insp-layout{grid-template-columns:1fr;min-height:auto}}

    /* Left panel */
    .ch-panel{border-right:1px solid var(--border);display:flex;flex-direction:column;min-height:400px}
    @media(max-width:768px){.ch-panel{border-right:none;border-bottom:1px solid var(--border);min-height:240px}}
    .ch-header{padding:14px 16px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
    .ch-heading{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-tertiary)}
    .ch-count{font-size:10px;font-weight:600;padding:2px 7px;border-radius:4px;background:var(--bg-elevated);color:var(--text-tertiary);border:1px solid var(--border)}
    .ch-filter{padding:8px 16px;border-bottom:1px solid var(--border)}
    .ch-filter input{width:100%;padding:6px 10px;background:var(--bg-base);border:1px solid var(--border);border-radius:6px;color:var(--text-primary);font-size:12px;font-family:var(--font-sans);outline:none;transition:border-color 150ms}
    .ch-filter input:focus{border-color:var(--accent)}
    .ch-filter input::placeholder{color:var(--text-tertiary)}
    .ch-list{flex:1;overflow-y:auto;padding:4px}
    .ch-list::-webkit-scrollbar{width:4px}.ch-list::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}
    .ch-item{padding:10px 12px;border-radius:8px;cursor:pointer;transition:all 150ms;border-left:2px solid transparent;margin-bottom:2px}
    .ch-item:hover{background:var(--bg-hover)}
    .ch-item.selected{background:rgba(124,58,237,0.08);border-left-color:var(--accent)}
    .ch-name{font-family:var(--font-mono);font-size:12px;font-weight:500;color:var(--text-primary);margin-bottom:4px;display:flex;align-items:center;gap:6px}
    .ch-dot{width:6px;height:6px;border-radius:50%;background:var(--text-tertiary);flex-shrink:0}
    .ch-item.selected .ch-dot{background:var(--success);animation:pulse 2s ease-in-out infinite}
    .ch-meta{display:flex;align-items:center;gap:6px;padding-left:12px}
    .ch-type{font-size:10px;font-weight:600;padding:1px 6px;border-radius:3px;text-transform:uppercase;letter-spacing:0.03em}
    .ch-type-presence{background:rgba(59,130,246,0.1);color:#60a5fa}
    .ch-type-private{background:rgba(245,158,11,0.1);color:#fbbf24}
    .ch-type-public{background:rgba(79,78,92,0.15);color:var(--text-tertiary)}
    .ch-subs{font-size:11px;color:var(--text-tertiary)}
    .ch-empty{padding:32px 16px;text-align:center;color:var(--text-tertiary);font-size:13px}
    .ch-empty pre{text-align:left;background:var(--bg-base);border:1px solid var(--border);border-radius:6px;padding:12px;margin:12px 0 8px;font-family:var(--font-mono);font-size:11px;line-height:1.6;color:var(--text-secondary);overflow-x:auto}

    /* Right panel */
    .ev-panel{display:flex;flex-direction:column;min-height:400px}
    .ev-header{padding:14px 16px;border-bottom:1px solid var(--border);font-family:var(--font-mono);font-size:12px;color:var(--text-secondary);display:flex;align-items:center;gap:8px}
    .ev-header-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-tertiary);font-family:var(--font-sans)}
    .ev-stream{flex:1;overflow-y:auto;padding:0}
    .ev-stream::-webkit-scrollbar{width:4px}.ev-stream::-webkit-scrollbar-thumb{background:var(--border);border-radius:2px}
    .ev-row{border-bottom:1px solid var(--border);transition:background 150ms}
    .ev-row:hover{background:rgba(255,255,255,0.015)}
    .ev-row-main{display:flex;align-items:center;gap:12px;padding:10px 16px;cursor:pointer}
    .ev-time{font-family:var(--font-mono);font-size:11px;color:var(--text-tertiary);flex-shrink:0;width:64px}
    .ev-name{font-size:13px;font-weight:500;color:var(--text-primary);flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .ev-expand{width:20px;height:20px;display:flex;align-items:center;justify-content:center;color:var(--text-tertiary);transition:transform 150ms;flex-shrink:0;font-size:12px}
    .ev-expand.open{transform:rotate(90deg)}
    .ev-payload{display:none;padding:0 16px 12px 92px}
    .ev-payload.open{display:block}
    .ev-payload-box{position:relative;background:var(--bg-base);border:1px solid var(--border);border-radius:6px;padding:12px 16px;overflow-x:auto}
    .ev-payload-box pre{font-family:var(--font-mono);font-size:11px;line-height:1.6;margin:0}
    .ev-payload-box pre code.hljs{background:none;padding:0}
    .ev-payload-copy{position:absolute;top:6px;right:8px;padding:2px 8px;border-radius:4px;font-size:10px;font-weight:500;color:var(--text-tertiary);background:rgba(255,255,255,0.04);border:1px solid var(--border);cursor:pointer;font-family:var(--font-sans);transition:all 150ms}
    .ev-payload-copy:hover{color:var(--text-secondary);border-color:var(--text-tertiary)}
    .ev-empty{padding:48px 24px;text-align:center;color:var(--text-tertiary);font-size:13px}
    .ev-load-more{padding:10px 16px;text-align:center}
    .ev-load-more button{background:none;border:1px solid var(--border);border-radius:6px;padding:6px 16px;font-size:12px;font-weight:500;color:var(--text-secondary);cursor:pointer;font-family:var(--font-sans);transition:all 150ms}
    .ev-load-more button:hover{border-color:var(--border-strong);color:var(--text-primary)}
</style>

<div class="insp-header">
    <div class="insp-title">{{ $project->name }} <span>&mdash; Channel Inspector</span></div>
    <div class="insp-controls">
        <div class="insp-live" id="live-badge">
            <div class="insp-live-dot"></div>
            <span id="live-text">LIVE</span>
        </div>
        <button class="insp-refresh" onclick="forceRefresh()" title="Refresh">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
        </button>
    </div>
</div>

<div class="insp-banner" id="offline-banner">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;flex-shrink:0;"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    Relay server offline &mdash; reconnecting...
</div>

<div class="insp-layout">
    <!-- Channel List -->
    <div class="ch-panel">
        <div class="ch-header">
            <span class="ch-heading">Channels</span>
            <span class="ch-count" id="ch-count">0</span>
        </div>
        <div class="ch-filter">
            <input type="text" id="ch-search" placeholder="Filter channels...">
        </div>
        <div class="ch-list" id="ch-list">
            <div class="ch-empty" id="ch-empty">
                <div style="margin-bottom:8px;font-weight:500;color:var(--text-secondary);">No active channels</div>
                <pre>// Trigger an event from your app
broadcast(new MessageSent($data))
    ->toChannel('public-feed');</pre>
                <div style="font-size:11px;">Events will appear here in real time</div>
            </div>
        </div>
    </div>

    <!-- Event Stream -->
    <div class="ev-panel">
        <div class="ev-header">
            <span class="ev-header-label" id="ev-channel-label">No channel selected</span>
        </div>
        <div class="ev-stream" id="ev-stream">
            <div class="ev-empty" id="ev-empty">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="width:32px;height:32px;margin:0 auto 10px;display:block;opacity:0.4;"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
                Select a channel to view its event stream
            </div>
        </div>
    </div>
</div>

<script>
(function(){
    const PROJECT_ID = {{ $project->id }};
    const BASE = '/projects/{{ $project->id }}/inspector';
    let selectedChannel = null;
    let events = [];
    let channelData = {};
    let isLive = true;
    let userScrolled = false;
    let nextCursor = null;
    const expandedEvents = new Set();
    const renderedEventIds = new Set();

    const chList = document.getElementById('ch-list');
    const chEmpty = document.getElementById('ch-empty');
    const chCount = document.getElementById('ch-count');
    const chSearch = document.getElementById('ch-search');
    const evStream = document.getElementById('ev-stream');
    const evEmpty = document.getElementById('ev-empty');
    const evLabel = document.getElementById('ev-channel-label');
    const liveBadge = document.getElementById('live-badge');
    const liveText = document.getElementById('live-text');
    const offlineBanner = document.getElementById('offline-banner');

    function channelType(name) {
        if (name.startsWith('presence-')) return 'presence';
        if (name.startsWith('private-')) return 'private';
        return 'public';
    }

    function shortEventName(name) {
        if (!name) return '';
        const parts = name.split('\\');
        return parts[parts.length - 1];
    }

    function formatTime(ts) {
        if (!ts) return '--:--:--';
        const d = new Date(ts);
        if (isNaN(d)) return ts.substring(11, 19) || ts;
        return d.toLocaleTimeString('en-GB', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
    }

    function prettyJson(data) {
        if (!data) return '';
        if (typeof data === 'string') {
            try { return JSON.stringify(JSON.parse(data), null, 2); } catch(e) { return data; }
        }
        return JSON.stringify(data, null, 2);
    }

    function setLive(online) {
        isLive = online;
        if (online) {
            liveBadge.className = 'insp-live';
            liveText.textContent = 'LIVE';
            offlineBanner.classList.remove('show');
        } else {
            liveBadge.className = 'insp-live offline';
            liveText.textContent = 'PAUSED';
            offlineBanner.classList.add('show');
        }
    }

    // Track scroll position for auto-scroll
    evStream.addEventListener('scroll', () => {
        userScrolled = evStream.scrollTop > 10;
    });

    // Filter channels client-side
    chSearch.addEventListener('input', () => renderChannels());

    function renderChannels() {
        const filter = chSearch.value.toLowerCase();
        const keys = Object.keys(channelData);
        const filtered = filter ? keys.filter(k => k.toLowerCase().includes(filter)) : keys;

        if (filtered.length === 0) {
            chEmpty.style.display = '';
            chCount.textContent = keys.length;
            // Remove rendered items but keep empty
            chList.querySelectorAll('.ch-item').forEach(el => el.remove());
            return;
        }
        chEmpty.style.display = 'none';
        chCount.textContent = keys.length;

        // Build new list
        const frag = document.createDocumentFragment();
        filtered.sort().forEach(name => {
            const info = channelData[name] || {};
            const type = channelType(name);
            const subs = info.subscription_count || info.user_count || 0;
            const isSelected = name === selectedChannel;

            const div = document.createElement('div');
            div.className = 'ch-item' + (isSelected ? ' selected' : '');
            div.onclick = () => selectChannel(name);
            div.innerHTML = `
                <div class="ch-name"><div class="ch-dot"></div>${escHtml(name)}</div>
                <div class="ch-meta">
                    <span class="ch-type ch-type-${type}">${type}</span>
                    <span class="ch-subs">${subs} sub${subs !== 1 ? 's' : ''}</span>
                </div>`;
            frag.appendChild(div);
        });
        chList.querySelectorAll('.ch-item').forEach(el => el.remove());
        chList.appendChild(frag);
    }

    function selectChannel(name) {
        selectedChannel = name;
        events = [];
        nextCursor = null;
        expandedEvents.clear();
        renderedEventIds.clear();
        evStream.querySelectorAll('.ev-row').forEach(el => el.remove());
        evLabel.innerHTML = '<span style="font-family:var(--font-mono);color:var(--text-primary);">' + escHtml(name) + '</span>';
        renderChannels();
        fetchEvents();
    }

    function makeEventRow(ev) {
        const uid = eventKey(ev);
        const id = 'evp-' + uid.replace(/[^a-zA-Z0-9]/g, '').substring(0, 40);
        const isOpen = expandedEvents.has(uid);
        const row = document.createElement('div');
        row.className = 'ev-row';
        row.dataset.uid = uid;

        const json = prettyJson(ev.data);
        row.innerHTML = `
            <div class="ev-row-main">
                <span class="ev-time">${formatTime(ev.timestamp || ev.created_at)}</span>
                <span class="ev-name" title="${escHtml(ev.event || ev.name || '')}">${escHtml(shortEventName(ev.event || ev.name || ''))}</span>
                <span class="ev-expand${isOpen ? ' open' : ''}">&#8250;</span>
            </div>
            <div class="ev-payload${isOpen ? ' open' : ''}" id="${id}">
                <div class="ev-payload-box">
                    <button class="ev-payload-copy" onclick="event.stopPropagation();navigator.clipboard.writeText(this.parentElement.querySelector('code').textContent);this.textContent='Copied!';setTimeout(()=>this.textContent='Copy',1500)">Copy</button>
                    <pre><code class="language-json">${escHtml(json)}</code></pre>
                </div>
            </div>`;
        row.querySelector('.ev-row-main').onclick = () => {
            const payload = row.querySelector('.ev-payload');
            const chevron = row.querySelector('.ev-expand');
            payload.classList.toggle('open');
            chevron.classList.toggle('open');
            if (payload.classList.contains('open')) {
                expandedEvents.add(uid);
            } else {
                expandedEvents.delete(uid);
            }
        };
        // Highlight
        const code = row.querySelector('pre code');
        if (code && !code.dataset.highlighted) { hljs.highlightElement(code); code.dataset.highlighted = '1'; }
        return row;
    }

    function renderEvents() {
        if (events.length === 0) {
            evEmpty.style.display = '';
            evStream.querySelectorAll('.ev-row,.ev-load-more').forEach(el => el.remove());
            renderedEventIds.clear();
            return;
        }
        evEmpty.style.display = 'none';

        const visible = events.slice(0, 50);
        const newEvents = visible.filter(ev => !renderedEventIds.has(eventKey(ev)));

        if (newEvents.length === 0) return; // Nothing new — don't touch DOM

        // Prepend new events at top
        const firstRow = evStream.querySelector('.ev-row');
        newEvents.reverse().forEach(ev => {
            const uid = eventKey(ev);
            renderedEventIds.add(uid);
            const row = makeEventRow(ev);
            if (firstRow) {
                evStream.insertBefore(row, firstRow);
            } else {
                evStream.appendChild(row);
            }
        });

        // Trim excess rows beyond 50
        const rows = evStream.querySelectorAll('.ev-row');
        if (rows.length > 50) {
            for (let i = 50; i < rows.length; i++) {
                renderedEventIds.delete(rows[i].dataset.uid);
                rows[i].remove();
            }
        }

        if (!userScrolled) evStream.scrollTop = 0;
    }

    function escHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    // Fetch channels
    async function fetchChannels() {
        try {
            const r = await fetch(BASE + '/channels', {credentials:'same-origin'});
            if (!r.ok) { setLive(false); return; }
            setLive(true);
            channelData = await r.json();
            renderChannels();
        } catch(e) { setLive(false); }
    }

    // Fetch events for selected channel
    async function fetchEvents(loadMore) {
        if (!selectedChannel) return;
        try {
            let url = BASE + '/events/' + encodeURIComponent(selectedChannel) + '?limit=25';
            if (loadMore && nextCursor) url += '&cursor=' + encodeURIComponent(nextCursor);
            const r = await fetch(url, {credentials:'same-origin'});
            if (!r.ok) return;
            const data = await r.json();
            nextCursor = data.next_cursor;

            if (loadMore) {
                // Append older events
                const newEvents = (data.events || []).filter(e => !events.some(ex => eventKey(ex) === eventKey(e)));
                events = events.concat(newEvents);
            } else {
                // Prepend new events
                const newEvents = (data.events || []).filter(e => !events.some(ex => eventKey(ex) === eventKey(e)));
                events = newEvents.concat(events);
            }
            renderEvents();
        } catch(e) {}
    }

    function eventKey(ev) {
        return (ev.timestamp || '') + ':' + (ev.event || ev.name || '') + ':' + JSON.stringify(ev.data || '').substring(0, 50);
    }

    function forceRefresh() {
        fetchChannels();
        if (selectedChannel) fetchEvents();
    }

    // Initial load
    fetchChannels();

    // Polling
    setInterval(fetchChannels, 3000);
    setInterval(() => { if (selectedChannel) fetchEvents(); }, 2000);

    // Expose for refresh button
    window.forceRefresh = forceRefresh;

    // Pause when tab hidden
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) setLive(false);
    });
})();
</script>
@endsection
