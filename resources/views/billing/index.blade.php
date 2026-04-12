@extends('layouts.app')

@section('header', 'Billing')

@section('content')
<div style="margin-bottom: 32px;">
    <h1 class="page-title">Billing & Plans</h1>
    <p class="page-subtitle">Manage your subscription and view usage.</p>
</div>

@if(request('success'))
    <div class="alert alert-success">Your subscription has been activated! Your plan limits are now in effect.</div>
@endif

<!-- Manage Billing -->
@if($isSubscribed)
    <div class="card" style="margin-bottom: 24px;">
        <div style="display:flex; align-items:center; justify-content:space-between;">
            <div>
                <div style="font-size:16px; font-weight:600; margin-bottom:4px;">Subscription Active</div>
                <div style="font-size:14px; color:var(--text-muted);">You're on the {{ $plans[$currentPlan]['name'] }} plan. Manage your payment method, invoices, or cancel anytime.</div>
            </div>
            <a href="{{ route('billing.portal') }}" class="btn btn-secondary">Manage Billing</a>
        </div>
    </div>
@endif

<!-- Usage Summary -->
<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h2 class="card-title">Current Usage</h2>
    </div>
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap: 20px;">
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                <span style="color:var(--text-muted)">Projects</span>
                <span>{{ $projectCount }} / {{ $maxProjects }}</span>
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $maxProjects > 0 ? min(100, ($projectCount / $maxProjects) * 100) : 0 }}%"></div>
            </div>
        </div>
        <div>
            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                <span style="color:var(--text-muted)">Max Connections</span>
                <span>{{ number_format($totalConnections) }} / {{ $maxConnections == -1 ? 'Unlimited' : number_format($maxConnections) }}</span>
            </div>
            <div class="progress-bar">
                @php
                    $connPercent = $maxConnections > 0 ? min(100, ($totalConnections / $maxConnections) * 100) : 0;
                @endphp
                <div class="progress-fill" style="width: {{ $connPercent }}%"></div>
            </div>
        </div>
    </div>
</div>

<!-- Plan Cards -->
<div class="plan-cards">
    @foreach($plans as $slug => $plan)
    <div class="plan-card {{ $currentPlan === $slug ? 'current' : '' }}">
        @if($currentPlan === $slug)
            <div style="position:absolute; top:16px; right:16px;">
                <span class="plan-badge">Current Plan</span>
            </div>
        @endif
        <div class="plan-card-name">{{ $plan['name'] }}</div>
        <div class="plan-card-price">
            ${{ $plan['price'] }}<span>/mo</span>
        </div>
        <div class="plan-card-desc">
            @if($slug === 'hobby') Perfect for side projects and testing.
            @elseif($slug === 'startup') For growing apps with real users.
            @else For production apps at scale.
            @endif
        </div>
        <ul class="plan-features">
            <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ number_format($plan['max_connections']) }} max connections
            </li>
            <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ $plan['max_messages_day'] == -1 ? 'Unlimited' : number_format($plan['max_messages_day']) }} messages/day
            </li>
            <li>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                {{ $plan['max_projects'] }} project{{ $plan['max_projects'] === 1 ? '' : 's' }}
            </li>
        </ul>
        @if($currentPlan === $slug)
            <button class="btn btn-secondary" disabled style="width:100%; justify-content:center; opacity:0.5;">Current Plan</button>
        @elseif($slug === 'hobby')
            {{-- No button for hobby — users downgrade via Stripe portal --}}
            <button class="btn btn-secondary" disabled style="width:100%; justify-content:center; opacity:0.5;">Free Tier</button>
        @elseif(array_search($slug, array_keys($plans)) > array_search($currentPlan, array_keys($plans)))
            <form method="POST" action="{{ route('billing.checkout', $slug) }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Upgrade &mdash; ${{ $plan['price'] }}/mo</button>
            </form>
        @else
            <button class="btn btn-secondary" disabled style="width:100%; justify-content:center; opacity:0.5;">Downgrade</button>
        @endif
    </div>
    @endforeach
</div>
@endsection
