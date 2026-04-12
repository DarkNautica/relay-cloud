@extends('layouts.app')

@section('content')
<style>
    .timeline{list-style:none;padding:0}
    .tl-group{margin-bottom:24px}
    .tl-date{font-size:12px;font-weight:600;color:var(--text-tertiary);text-transform:uppercase;letter-spacing:0.05em;margin-bottom:10px;padding-left:32px}
    .tl-item{display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid var(--border)}
    .tl-item:last-child{border-bottom:none}
    .tl-dot{width:8px;height:8px;border-radius:50%;margin-top:5px;flex-shrink:0}
    .tl-dot-green{background:var(--success)}.tl-dot-red{background:var(--danger)}.tl-dot-amber{background:var(--warning)}.tl-dot-purple{background:var(--accent)}.tl-dot-grey{background:var(--text-tertiary)}.tl-dot-blue{background:#3b82f6}
    .tl-icon{width:16px;height:16px;color:var(--text-tertiary);flex-shrink:0;margin-top:1px}
    .tl-text{font-size:13px;color:var(--text-secondary);flex:1}
    .tl-time{font-size:11px;color:var(--text-tertiary);white-space:nowrap;margin-top:2px}
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Activity</h1>
        <p class="page-sub">A timeline of actions on your account.</p>
    </div>
</div>

@if($logs->isEmpty())
    <div class="card">
        <div class="empty-state">
            <svg class="empty-state-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <h3>No activity yet</h3>
            <p>Actions like creating projects, changing plans, and updating settings will appear here.</p>
        </div>
    </div>
@else
    <div class="card card-pad">
        @php
            $grouped = $logs->groupBy(function($log) {
                if ($log->created_at->isToday()) return 'Today';
                if ($log->created_at->isYesterday()) return 'Yesterday';
                return $log->created_at->format('F j');
            });
            $eventConfig = [
                'project.created' => ['dot' => 'tl-dot-green'],
                'project.deleted' => ['dot' => 'tl-dot-red'],
                'project.paused' => ['dot' => 'tl-dot-amber'],
                'project.resumed' => ['dot' => 'tl-dot-green'],
                'plan.upgraded' => ['dot' => 'tl-dot-purple'],
                'plan.downgraded' => ['dot' => 'tl-dot-amber'],
                'account.login' => ['dot' => 'tl-dot-grey'],
                'account.password_changed' => ['dot' => 'tl-dot-blue'],
                'account.deleted' => ['dot' => 'tl-dot-red'],
                'webhook.created' => ['dot' => 'tl-dot-purple'],
                'webhook.deleted' => ['dot' => 'tl-dot-red'],
            ];
        @endphp
        @foreach($grouped as $date => $items)
            <div class="tl-group">
                <div class="tl-date">{{ $date }}</div>
                @foreach($items as $log)
                    @php $cfg = $eventConfig[$log->event] ?? ['dot' => 'tl-dot-grey']; @endphp
                    <div class="tl-item">
                        <div class="tl-dot {{ $cfg['dot'] }}"></div>
                        <div class="tl-text">{{ $log->description }}</div>
                        <div class="tl-time">{{ $log->created_at->format('g:i A') }}</div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    <div style="margin-top:16px;">{{ $logs->links() }}</div>
@endif
@endsection
