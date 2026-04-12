@extends('layouts.docs')
@section('title', 'Documentation')
@section('no-sidebar', true)

@section('content')
<div style="max-width:800px;margin:0 auto;padding:60px 24px;">
    <h1 style="font-size:28px;font-weight:700;letter-spacing:-0.02em;margin-bottom:8px;text-align:center;">Documentation</h1>
    <p style="font-size:15px;color:var(--t2);text-align:center;margin-bottom:40px;">Everything you need to build with Relay.</p>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div style="background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04);">
            <h2 style="font-size:18px;font-weight:700;margin-bottom:4px;">Self-Hosting Guide</h2>
            <p style="font-size:13px;color:var(--t2);margin-bottom:18px;">Run Relay on your own infrastructure. Docker, binary, or build from source.</p>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('docs.os.getting-started') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">Getting Started &rarr;</a>
                <a href="{{ route('docs.os.configuration') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">Configuration &rarr;</a>
                <a href="{{ route('docs.os.api-reference') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">API Reference &rarr;</a>
                <a href="{{ route('docs.os.sdks') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">SDKs &rarr;</a>
            </div>
        </div>

        <div style="background:linear-gradient(145deg,rgba(124,58,237,0.06),#111115);border:1px solid rgba(124,58,237,0.15);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04);">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
                <h2 style="font-size:18px;font-weight:700;">Relay Cloud</h2>
                <span style="font-size:9px;padding:2px 6px;border-radius:3px;background:rgba(124,58,237,0.15);color:var(--accent-l);font-weight:700;text-transform:uppercase;">Cloud</span>
            </div>
            <p style="font-size:13px;color:var(--t2);margin-bottom:18px;">Managed WebSocket hosting. No ops required.</p>
            <div style="display:flex;flex-direction:column;gap:6px;">
                <a href="{{ route('docs.cloud.getting-started') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">Getting Started &rarr;</a>
                <a href="{{ route('docs.cloud.projects') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">Projects &rarr;</a>
                <a href="{{ route('docs.cloud.billing') }}" style="font-size:13px;color:var(--accent-l);font-weight:500;">Billing &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
