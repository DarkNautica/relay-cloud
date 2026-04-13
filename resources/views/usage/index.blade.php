@extends('layouts.app')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Usage & Analytics</h1>
        <p class="page-sub">Monitor connections, messages, and per-project usage.</p>
    </div>
</div>

<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Current Connections</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.55a11 11 0 0 1 14.08 0"/><path d="M8.53 16.11a6 6 0 0 1 6.95 0"/><line x1="12" y1="20" x2="12.01" y2="20"/></svg>
        </div>
        <div class="stat-value">{{ number_format($serverStats['connections']) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Peak Today</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
        </div>
        <div class="stat-value">{{ number_format($todayPeak) }}</div>
    </div>
    <div class="stat-card">
        <div class="stat-top">
            <div class="stat-label">Messages Today</div>
            <svg class="stat-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        </div>
        <div class="stat-value">{{ number_format($todayMessages) }}</div>
    </div>
</div>

<!-- Chart -->
<div class="card card-pad" style="margin-bottom:16px;">
    <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Active Connections — Last 24 Hours</div>
    <div id="chart-placeholder" style="text-align:center;padding:40px;color:var(--text-tertiary);font-size:13px;display:none;">
        Collecting data... check back in an hour.
    </div>
    <canvas id="usage-chart" height="200"></canvas>
</div>

<!-- Per-project breakdown -->
<div class="card" style="margin-bottom:16px;">
    <div class="card-header"><span class="card-title">Per-Project Breakdown</span></div>
    <div style="overflow-x:auto;">
        <table>
            <thead><tr><th>Project</th><th>App ID</th><th class="col-conn">Connections</th><th class="col-conn">Max</th><th class="col-status">Usage</th><th class="col-status">Status</th></tr></thead>
            <tbody>
            @foreach($projects as $project)
                @php
                    $ps = $projectStats[$project->id] ?? ['connections' => 0];
                    $usagePct = $project->max_connections > 0 ? round(($ps['connections'] / $project->max_connections) * 100) : 0;
                    $usageColor = $usagePct >= 90 ? 'var(--danger)' : ($usagePct >= 70 ? 'var(--warning)' : 'var(--success)');
                @endphp
                <tr style="cursor:default;">
                    <td style="font-weight:600;color:var(--text-primary);">{{ $project->name }}</td>
                    <td style="font-family:var(--font-mono);font-size:11px;color:var(--text-tertiary);">{{ $project->app_id }}</td>
                    <td style="font-family:var(--font-mono);font-size:12px;">{{ $ps['connections'] }}</td>
                    <td style="font-family:var(--font-mono);font-size:12px;">{{ number_format($project->max_connections) }}</td>
                    <td style="font-weight:600;color:{{ $usageColor }};font-size:12px;">{{ $usagePct }}%</td>
                    <td>
                        <span class="badge {{ $project->is_active ? 'badge-active' : 'badge-inactive' }}">
                            <span class="badge-dot"></span>{{ $project->is_active ? 'Active' : 'Paused' }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
fetch('/api/usage/stats', {credentials:'same-origin'}).then(r=>r.json()).then(d=>{
    const allZero = d.data.every(v=>v===0);
    if(allZero){document.getElementById('chart-placeholder').style.display='block';document.getElementById('usage-chart').style.display='none';return;}
    new Chart(document.getElementById('usage-chart'),{
        type:'line',
        data:{labels:d.labels,datasets:[{label:'Connections',data:d.data,borderColor:'#7c3aed',backgroundColor:'rgba(124,58,237,0.08)',fill:true,tension:0.3,pointRadius:2,pointBackgroundColor:'#7c3aed'}]},
        options:{responsive:true,plugins:{legend:{display:false}},scales:{
            x:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'#4f4e5c',font:{size:11}}},
            y:{grid:{color:'rgba(255,255,255,0.04)'},ticks:{color:'#4f4e5c',font:{size:11}},beginAtZero:true}
        }}
    });
}).catch(()=>{document.getElementById('chart-placeholder').style.display='block';document.getElementById('usage-chart').style.display='none';});
</script>
@endsection
