@extends('layouts.app')
@section('breadcrumb', 'Billing')

@section('content')
<div style="margin-bottom:24px;">
    <h1 class="page-title">Billing & Plans</h1>
    <p class="page-sub">Manage your subscription and usage.</p>
</div>

@if(request('success'))
    <div class="alert alert-success">Subscription activated! Your plan limits are now in effect.</div>
@endif

@if($isSubscribed)
    <div class="card card-pad" style="margin-bottom:16px;">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-size:14px;font-weight:600;margin-bottom:2px;">Subscription Active</div>
                <div style="font-size:13px;color:var(--text-tertiary);">{{ \App\Services\PlanService::getPlan($currentPlan)['name'] }} plan. Manage payment, invoices, or cancel.</div>
            </div>
            <a href="{{ route('billing.portal') }}" class="btn btn-secondary btn-sm">Manage Billing</a>
        </div>
    </div>
@endif

<!-- Usage -->
<div class="card card-pad" style="margin-bottom:20px;">
    <div style="font-size:14px;font-weight:600;margin-bottom:16px;">Current Usage</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
                <span style="color:var(--text-tertiary);">Projects</span>
                <span style="font-family:var(--font-mono);font-size:11px;">{{ $projectCount }} / {{ $maxProjects }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width:{{ $maxProjects > 0 ? min(100, ($projectCount / $maxProjects) * 100) : 0 }}%"></div>
            </div>
        </div>
        <div>
            <div style="display:flex;justify-content:space-between;margin-bottom:6px;font-size:12px;">
                <span style="color:var(--text-tertiary);">Max Connections</span>
                <span style="font-family:var(--font-mono);font-size:11px;">{{ number_format($totalConnections) }} / {{ $maxConnections == -1 ? 'Unlimited' : number_format($maxConnections) }}</span>
            </div>
            <div class="progress-bar">
                @php $pct = $maxConnections > 0 ? min(100, ($totalConnections / $maxConnections) * 100) : 0; @endphp
                <div class="progress-fill" style="width:{{ $pct }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Plan Cards -->
<div class="plan-cards">
    @php $check = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:14px;height:14px;color:var(--accent-light);flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>'; @endphp
    @foreach($plans as $slug => $plan)
    <div class="plan-card {{ $currentPlan === $slug ? 'current' : '' }}">
        @if($currentPlan === $slug)
            <div style="position:absolute;top:14px;right:14px;">
                <span style="font-size:10px;font-weight:600;padding:3px 8px;border-radius:4px;background:rgba(124,58,237,0.15);color:var(--accent-light);text-transform:uppercase;">Current</span>
            </div>
        @endif
        <div class="plan-name">{{ $plan['name'] }}</div>
        <div class="plan-price">${{ $plan['price'] }}<span>/mo</span></div>
        <div class="plan-desc">
            @if($slug === 'hobby') Perfect for side projects and testing.
            @elseif($slug === 'startup') For growing apps with real users.
            @else For production apps at scale.
            @endif
        </div>
        <ul class="plan-features">
            <li>{!! $check !!} {{ number_format($plan['max_connections']) }} max connections</li>
            <li>{!! $check !!} {{ $plan['max_messages_day'] == -1 ? 'Unlimited' : number_format($plan['max_messages_day']) }} messages/day</li>
            <li>{!! $check !!} {{ $plan['max_projects'] }} project{{ $plan['max_projects'] === 1 ? '' : 's' }}</li>
        </ul>
        @if($currentPlan === $slug)
            <button class="btn btn-secondary" disabled style="width:100%;opacity:0.5;">Current Plan</button>
        @elseif($slug === 'hobby')
            <button class="btn btn-secondary" disabled style="width:100%;opacity:0.5;">Free Tier</button>
        @elseif(array_search($slug, array_keys($plans)) > array_search($currentPlan, array_keys($plans)))
            <form method="POST" action="{{ route('billing.checkout', $slug) }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%;">Upgrade &mdash; ${{ $plan['price'] }}/mo</button>
            </form>
        @else
            <button class="btn btn-secondary" disabled style="width:100%;opacity:0.5;">Downgrade</button>
        @endif
    </div>
    @endforeach
</div>
@endsection
