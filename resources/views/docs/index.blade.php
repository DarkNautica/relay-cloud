@extends('layouts.docs')
@section('title', 'Documentation')
@section('no-sidebar', true)

@section('content')
<style>
    .hub{max-width:840px;margin:0 auto;padding:56px 24px}
    .hub-hero{text-align:center;margin-bottom:40px}
    .hub-hero h1{font-size:30px;font-weight:700;letter-spacing:-0.02em;margin-bottom:8px}
    .hub-hero p{font-size:15px;color:var(--t2);max-width:520px;margin:0 auto 24px}
    .hub-ctas{display:flex;justify-content:center;gap:10px}
    .hub-btn{padding:10px 22px;border-radius:8px;font-size:13px;font-weight:600;border:none;cursor:pointer;font-family:var(--sans);transition:all 150ms}
    .hub-btn:active{transform:scale(0.98)}
    .hub-btn-primary{background:var(--accent);color:#fff}
    .hub-btn-primary:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
    .hub-btn-ghost{background:transparent;color:var(--t2);border:1px solid var(--border)}
    .hub-btn-ghost:hover{border-color:var(--t2);color:var(--t1)}
    .hub-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
    .hub-card{background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px 28px 24px;box-shadow:0 1px 3px rgba(0,0,0,0.5),0 4px 16px rgba(0,0,0,0.3),inset 0 1px 0 rgba(255,255,255,0.04)}
    .hub-card.cloud{border-color:rgba(124,58,237,0.2);background:linear-gradient(145deg,rgba(124,58,237,0.06),#111115);box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04),0 0 20px rgba(124,58,237,0.06)}
    .hub-card-icon{width:32px;height:32px;color:var(--t3);margin-bottom:14px}
    .hub-card.cloud .hub-card-icon{color:var(--accent-l)}
    .hub-link{display:flex;align-items:baseline;gap:8px;padding:10px 14px;margin:0 -14px;border-radius:8px;transition:background 150ms}
    .hub-link:hover{background:rgba(255,255,255,0.03)}
    .hub-link-title{font-size:14px;font-weight:600;color:var(--accent-l);white-space:nowrap}
    .hub-link-desc{font-size:13px;color:var(--t3)}
    .hub-link-arrow{margin-left:auto;color:var(--t3);font-size:12px;flex-shrink:0}
    .hub-card-cta{display:block;text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:600;background:var(--accent);color:#fff;margin-top:14px;transition:all 150ms}
    .hub-card-cta:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
    @media(max-width:768px){.hub-grid{grid-template-columns:1fr}.hub-ctas{flex-direction:column;align-items:center}}
</style>
<div class="hub">
    <div class="hub-hero">
        <h1>Relay Documentation</h1>
        <p>Everything you need to self-host Relay or connect to Relay Cloud.</p>
        <div class="hub-ctas">
            <a href="{{ route('docs.cloud.getting-started') }}" class="hub-btn hub-btn-primary">Cloud Quick Start</a>
            <a href="{{ route('docs.os.getting-started') }}" class="hub-btn hub-btn-ghost">Self-Host Guide &rarr;</a>
        </div>
    </div>

    <div class="hub-grid">
        <div class="hub-card">
            <svg class="hub-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="8" rx="2"/><rect x="2" y="14" width="20" height="8" rx="2"/><line x1="6" y1="6" x2="6.01" y2="6"/><line x1="6" y1="18" x2="6.01" y2="18"/></svg>
            <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Open Source</h2>
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
            <svg class="hub-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                <h2 style="font-size:18px;font-weight:700;">Relay Cloud</h2>
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
            <a href="{{ route('register') }}" class="hub-card-cta">Start Free &rarr;</a>
        </div>
    </div>

    <!-- Framework Guides -->
    <div class="hub-card" style="margin-top:16px;">
        <svg class="hub-card-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
        <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Framework Guides</h2>
        <p style="font-size:13px;color:var(--t2);margin-bottom:12px;">Step-by-step quickstarts for popular frameworks.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:0;">
            <a href="{{ route('docs.guides.nextjs') }}" class="hub-link">
                <span class="hub-link-title">Next.js</span>
                <span class="hub-link-desc">React + Pusher JS client</span>
                <span class="hub-link-arrow">&rarr;</span>
            </a>
            <a href="{{ route('docs.guides.rails') }}" class="hub-link">
                <span class="hub-link-title">Rails</span>
                <span class="hub-link-desc">Ruby Pusher gem</span>
                <span class="hub-link-arrow">&rarr;</span>
            </a>
            <a href="{{ route('docs.guides.django') }}" class="hub-link">
                <span class="hub-link-title">Django</span>
                <span class="hub-link-desc">Python Pusher SDK</span>
                <span class="hub-link-arrow">&rarr;</span>
            </a>
            <a href="{{ route('docs.guides.node') }}" class="hub-link">
                <span class="hub-link-title">Node.js</span>
                <span class="hub-link-desc">Express + Pusher server SDK</span>
                <span class="hub-link-arrow">&rarr;</span>
            </a>
        </div>
    </div>

    <!-- Comparisons -->
    <div style="margin-top:20px;text-align:center;">
        <a href="{{ route('docs.vs-reverb') }}" style="font-size:13px;font-weight:600;color:var(--accent-l);">Relay vs Laravel Reverb &mdash; honest comparison &rarr;</a>
    </div>
</div>
@endsection
