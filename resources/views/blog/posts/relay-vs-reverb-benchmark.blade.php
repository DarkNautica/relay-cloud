@extends('layouts.docs')
@section('title', 'Relay vs Laravel Reverb: A Real Performance Benchmark')
@section('no-sidebar', true)

@section('content')
<article style="max-width:680px;margin:0 auto;padding:56px 24px;">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;font-size:12px;color:var(--t3);">
        <span>April 13, 2026</span>
        <span>&middot;</span>
        <span>5 min read</span>
    </div>
    <h1 style="font-size:30px;font-weight:800;letter-spacing:-0.02em;line-height:1.2;margin-bottom:16px;">Relay vs Laravel Reverb: A Real Performance Benchmark</h1>
    <p style="font-size:16px;color:var(--t2);line-height:1.7;margin-bottom:32px;border-bottom:1px solid var(--border);padding-bottom:32px;">
        We built Relay because we believed a WebSocket server written in Go would handle real-world connection loads more efficiently than one running in PHP. We wanted to find out by how much. So we ran a real benchmark. Here's exactly what we did, what we found, and what it means.
    </p>

    <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">Test Setup</h2>
    <ul style="font-size:14px;line-height:1.8;color:var(--t2);margin:0 0 8px 20px;">
        <li><strong style="color:var(--t1);">Benchmark runner:</strong> Hetzner CAX11 (4GB RAM, ARM64, Ubuntu 24.04)</li>
        <li><strong style="color:var(--t1);">Relay server:</strong> Hetzner CX23 (2 vCPU, 4GB RAM, Ubuntu 24.04) &mdash; separate machine, tested over network</li>
        <li><strong style="color:var(--t1);">Reverb server:</strong> Running locally on the benchmark box (loopback &mdash; an advantage for Reverb)</li>
        <li><strong style="color:var(--t1);">Load testing tool:</strong> k6 v0.55.0</li>
        <li><strong style="color:var(--t1);">Test:</strong> Ramp from 0 to 1,000 concurrent WebSocket connections over 5 minutes, hold at 1,000 for 60 seconds</li>
        <li><strong style="color:var(--t1);">Behavior:</strong> Each virtual user connects, subscribes to a public channel, and holds the connection open</li>
        <li><strong style="color:var(--t1);">Configuration:</strong> Both servers used default configuration with no tuning</li>
    </ul>
    <div style="padding:12px 16px;border-radius:8px;font-size:13px;color:var(--t2);margin:16px 0 24px;background:rgba(124,58,237,0.06);border:1px solid rgba(124,58,237,0.12);">
        <strong style="color:var(--t1);">Note on testing conditions:</strong> Reverb ran on loopback (same machine as k6) while Relay ran over the network on a separate server. This gave Reverb a latency advantage. Despite this, Relay won on every resource metric.
    </div>

    <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">Results</h2>
    <div style="overflow-x:auto;margin-bottom:24px;">
        <table style="width:100%;border-collapse:collapse;font-size:13px;border:1px solid var(--border);border-radius:8px;overflow:hidden;">
            <thead>
                <tr style="background:rgba(124,58,237,0.08);">
                    <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--accent-l);border-bottom:1px solid var(--border);">Metric</th>
                    <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--accent-l);border-bottom:1px solid var(--border);">Relay (Go)</th>
                    <th style="text-align:left;padding:12px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--accent-l);border-bottom:1px solid var(--border);">Laravel Reverb (PHP)</th>
                </tr>
            </thead>
            <tbody>
                <tr><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">Process memory at 1,000 connections</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--success);font-weight:600;">~38 MB</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">~63 MB</td></tr>
                <tr><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">CPU usage at peak</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--success);font-weight:600;">~18%</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">~95%</td></tr>
                <tr><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">Server load average</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--success);font-weight:600;">0.62</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">2.95</td></tr>
                <tr><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">Total server RAM used</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--success);font-weight:600;">700 MB</td><td style="padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2);">962 MB</td></tr>
                <tr><td style="padding:10px 16px;color:var(--t2);">Connections sustained</td><td style="padding:10px 16px;color:var(--t2);">1,000</td><td style="padding:10px 16px;color:var(--t2);">1,000</td></tr>
            </tbody>
        </table>
    </div>

    <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">What the Numbers Mean</h2>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:14px;">Both servers handled 1,000 connections. That's the baseline. But look at what it cost each of them to get there.</p>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:14px;">Reverb hit <strong style="color:var(--t1);">95% CPU</strong>. On the same hardware that Relay used at 18%. That means Reverb is near its ceiling at 1,000 connections on a $5 server. Relay is barely warming up. The headroom difference is what matters in production &mdash; when traffic spikes, Relay absorbs it. Reverb starts dropping connections.</p>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:14px;">The memory story is similar. Reverb consumed 63MB for the WebSocket process alone, plus the rest of the server pushed total RAM to 962MB &mdash; 26% of the available 3.72GB. Relay's process used 38MB, and the total server footprint was 700MB including Nginx, MySQL, and the Laravel app all running on the same box.</p>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:24px;">Go's goroutine model is the reason. Each WebSocket connection in Relay is handled by a goroutine &mdash; lightweight, cheap to schedule, and independently managed. PHP's event loop in Reverb is fundamentally different. It works, but it pays a higher price per connection.</p>

    <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">The Honest Caveat</h2>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:14px;">We want to be clear about what this benchmark does and does not show. 1,000 connections is the lower end of where these differences become meaningful. At 5,000 or 10,000 connections, we expect the gap to widen significantly &mdash; Reverb would likely need a larger server or horizontal scaling. Relay would continue on the same $5 box.</p>
    <p style="font-size:14px;line-height:1.8;color:var(--t2);margin-bottom:24px;">We also want to be fair to Reverb. It is a well-built product, officially maintained by the Laravel team, and the right choice if you want first-party support and are running a pure Laravel stack. This benchmark is not a reason to dismiss it &mdash; it is data to help you make an informed decision.</p>

    <h2 style="font-size:20px;font-weight:700;margin-bottom:12px;">When to Choose Each</h2>
    <p style="font-size:14px;line-height:1.6;color:var(--t2);margin-bottom:8px;"><strong style="color:var(--t1);">Choose Reverb if:</strong></p>
    <ul style="font-size:14px;line-height:1.8;color:var(--t2);margin:0 0 16px 20px;">
        <li>You are running a pure Laravel monolith</li>
        <li>You want official first-party Laravel support</li>
        <li>You are comfortable managing your own server</li>
    </ul>
    <p style="font-size:14px;line-height:1.6;color:var(--t2);margin-bottom:8px;"><strong style="color:var(--t1);">Choose Relay if:</strong></p>
    <ul style="font-size:14px;line-height:1.8;color:var(--t2);margin:0 0 24px 20px;">
        <li>You want better resource efficiency at scale</li>
        <li>You want a standalone server with no PHP or Laravel runtime dependency</li>
        <li>You want a managed cloud option with an open source exit ramp to self-hosting</li>
        <li>You want a Channel Inspector built into your dashboard</li>
        <li>You want to self-host with the option to migrate to managed hosting without changing any application code</li>
    </ul>

    <div style="border-top:1px solid var(--border);padding-top:28px;margin-top:8px;">
        <p style="font-size:14px;color:var(--t2);margin-bottom:16px;">Relay Cloud is a managed WebSocket hosting platform built on the open source Relay server. Free tier available. No credit card required.</p>
        <div style="display:flex;gap:10px;">
            <a href="{{ route('register') }}" style="padding:10px 22px;border-radius:8px;font-size:13px;font-weight:600;background:var(--accent);color:#fff;transition:all 150ms;">Try Relay Cloud free</a>
            <a href="{{ route('docs') }}" style="padding:10px 22px;border-radius:8px;font-size:13px;font-weight:600;color:var(--t2);border:1px solid var(--border);transition:all 150ms;">Read the docs</a>
        </div>
    </div>
</article>
@endsection
