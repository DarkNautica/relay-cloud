@extends('layouts.docs')
@section('title', 'Documentation')
@section('no-sidebar', true)

@section('content')
<style>
    .hub{max-width:800px;margin:0 auto;padding:60px 24px}
    .hub-card{background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px 28px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04);margin-bottom:16px}
    .hub-card.cloud{border-color:rgba(124,58,237,0.2);background:linear-gradient(145deg,rgba(124,58,237,0.06),#111115);box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04),0 0 20px rgba(124,58,237,0.06)}
    .hub-link{display:flex;align-items:baseline;gap:8px;padding:10px 14px;margin:0 -14px;border-radius:8px;transition:background 150ms}
    .hub-link:hover{background:rgba(255,255,255,0.03)}
    .hub-link-title{font-size:14px;font-weight:600;color:var(--accent-l);white-space:nowrap}
    .hub-link-desc{font-size:13px;color:var(--t3)}
    .hub-link-arrow{margin-left:auto;color:var(--t3);font-size:12px;flex-shrink:0}
    .hub-cta{display:block;text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:600;background:var(--accent);color:#fff;margin-top:14px;transition:all 150ms}
    .hub-cta:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
</style>
<div class="hub">
    <h1 style="font-size:28px;font-weight:700;letter-spacing:-0.02em;margin-bottom:8px;text-align:center;">Documentation</h1>
    <p style="font-size:15px;color:var(--t2);text-align:center;margin-bottom:40px;">Everything you need to build with Relay.</p>

    <div class="hub-card">
        <h2 style="font-size:20px;font-weight:700;margin-bottom:4px;">Self-Hosting Guide</h2>
        <p style="font-size:13px;color:var(--t2);margin-bottom:12px;">Run Relay on your own infrastructure. Docker, binary, or build from source.</p>
        <a href="{{ route('docs.os.getting-started') }}" class="hub-link">
            <span class="hub-link-title">Getting Started</span>
            <span class="hub-link-desc">Up and running in 5 minutes</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('docs.os.configuration') }}" class="hub-link">
            <span class="hub-link-title">Configuration</span>
            <span class="hub-link-desc">Environment variables and multi-app setup</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('docs.os.api-reference') }}" class="hub-link">
            <span class="hub-link-title">API Reference</span>
            <span class="hub-link-desc">HTTP endpoints and WebSocket protocol</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('docs.os.sdks') }}" class="hub-link">
            <span class="hub-link-title">SDKs</span>
            <span class="hub-link-desc">Laravel, Node.js, Rails, and Django</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
    </div>

    <div class="hub-card cloud">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <h2 style="font-size:20px;font-weight:700;">Relay Cloud</h2>
            <span style="font-size:9px;padding:2px 6px;border-radius:3px;background:rgba(124,58,237,0.15);color:var(--accent-l);font-weight:700;text-transform:uppercase;">Cloud</span>
        </div>
        <p style="font-size:13px;color:var(--t2);margin-bottom:12px;">Managed WebSocket hosting. No ops required.</p>
        <a href="{{ route('docs.cloud.getting-started') }}" class="hub-link">
            <span class="hub-link-title">Getting Started</span>
            <span class="hub-link-desc">From signup to production in 60 seconds</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('docs.cloud.projects') }}" class="hub-link">
            <span class="hub-link-title">Projects</span>
            <span class="hub-link-desc">Manage credentials and monitor connections</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('docs.cloud.billing') }}" class="hub-link">
            <span class="hub-link-title">Billing</span>
            <span class="hub-link-desc">Plans, upgrades, and subscription management</span>
            <span class="hub-link-arrow">&rarr;</span>
        </a>
        <a href="{{ route('register') }}" class="hub-cta">Start Free &rarr;</a>
    </div>
</div>
@endsection
