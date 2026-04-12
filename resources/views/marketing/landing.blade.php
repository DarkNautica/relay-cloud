@extends('layouts.docs')
@section('title', 'Real-time WebSockets, Self-hosted or Cloud')
@section('no-sidebar', true)

@section('content')
<style>
    .hero{text-align:center;padding:80px 24px 60px;max-width:800px;margin:0 auto}
    .hero-eyebrow{font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--accent-l);margin-bottom:16px}
    .hero h1{font-size:48px;font-weight:800;letter-spacing:-0.03em;line-height:1.1;margin-bottom:18px;background:linear-gradient(135deg,#f1f0f5 30%,#8b5cf6);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
    .hero p{font-size:17px;line-height:1.6;color:var(--t2);max-width:560px;margin:0 auto 28px}
    .hero-btns{display:flex;justify-content:center;gap:12px;margin-bottom:48px}
    .hero-btn{padding:12px 28px;border-radius:8px;font-size:14px;font-weight:600;border:none;cursor:pointer;font-family:var(--sans);transition:all 150ms}
    .hero-btn:active{transform:scale(0.98)}
    .hero-btn-primary{background:var(--accent);color:#fff}.hero-btn-primary:hover{background:var(--accent-l);box-shadow:0 0 0 3px var(--accent-glow)}
    .hero-btn-ghost{background:transparent;color:var(--t2);border:1px solid var(--border)}.hero-btn-ghost:hover{border-color:var(--t2);color:var(--t1)}
    .terminal{background:#0a0a0c;border:1px solid var(--border);border-radius:12px;max-width:580px;margin:0 auto;text-align:left;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,0.5)}
    .terminal-bar{display:flex;align-items:center;gap:6px;padding:10px 16px;border-bottom:1px solid var(--border)}
    .terminal-dot{width:10px;height:10px;border-radius:50%;border:1px solid var(--border)}
    .terminal pre{padding:18px 20px;font-family:var(--mono);font-size:12px;line-height:1.8;color:var(--t2);overflow-x:auto}
    .terminal .cmd{color:var(--t1)}.terminal .ok{color:var(--success)}
    .features{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;max-width:960px;margin:0 auto;padding:0 24px 64px}
    .feat-card{background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04)}
    .feat-card h3{font-size:16px;font-weight:700;margin-bottom:6px}
    .feat-card p{font-size:13px;line-height:1.6;color:var(--t2);margin:0}
    .section{max-width:960px;margin:0 auto;padding:0 24px 64px}
    .section-title{font-size:24px;font-weight:700;letter-spacing:-0.02em;text-align:center;margin-bottom:8px}
    .section-sub{text-align:center;font-size:14px;color:var(--t2);margin-bottom:32px}
    .cmp-table{width:100%;border-collapse:collapse;font-size:13px;background:var(--surface);border:1px solid var(--border);border-radius:12px;overflow:hidden}
    .cmp-table th{padding:12px 16px;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--t3);border-bottom:1px solid var(--border);text-align:left}
    .cmp-table td{padding:10px 16px;border-bottom:1px solid var(--border);color:var(--t2)}
    .cmp-table tr:last-child td{border-bottom:none}
    .cmp-table .check{color:var(--success)}.cmp-table .cross{color:var(--danger)}
    .pricing{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:16px}
    .pr-card{background:linear-gradient(145deg,#161620,#111115);border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:28px;box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04);text-align:center}
    .pr-name{font-size:18px;font-weight:700;margin-bottom:2px}
    .pr-price{font-size:32px;font-weight:700;letter-spacing:-0.02em;margin-bottom:4px}.pr-price span{font-size:14px;font-weight:400;color:var(--t3)}
    .pr-desc{font-size:13px;color:var(--t3);margin-bottom:16px}
    .pr-list{list-style:none;margin:0 0 20px;padding:0;font-size:13px;color:var(--t2);text-align:left}
    .pr-list li{padding:4px 0;display:flex;align-items:center;gap:6px}
    .pr-list li::before{content:'';width:4px;height:4px;border-radius:50%;background:var(--accent-l);flex-shrink:0}
    .pr-cta{display:block;text-align:center;padding:10px;border-radius:8px;font-size:13px;font-weight:600;background:var(--accent);color:#fff;transition:all 150ms}.pr-cta:hover{background:var(--accent-l)}
    @media(max-width:768px){.hero h1{font-size:32px}.features,.pricing{grid-template-columns:1fr}.hero-btns{flex-direction:column;align-items:center}}
</style>

<div class="hero">
    <div class="hero-eyebrow">Open Source &middot; MIT Licensed</div>
    <h1>Real-time WebSockets,<br>Self-hosted or Cloud.</h1>
    <p>Drop-in Pusher replacement. Single binary, zero dependencies. Run it yourself or let Relay Cloud handle the ops.</p>
    <div class="hero-btns">
        <a href="{{ route('register') }}" class="hero-btn hero-btn-primary">Start on Cloud &mdash; Free</a>
        <a href="{{ route('docs.os.getting-started') }}" class="hero-btn hero-btn-ghost">Self-Host &rarr;</a>
    </div>
    <div class="terminal">
        <div class="terminal-bar"><div class="terminal-dot"></div><div class="terminal-dot"></div><div class="terminal-dot"></div></div>
        <pre><span class="cmd">$ docker run -d -p 6001:6001 \
    -e RELAY_APP_KEY=my-key \
    -e RELAY_APP_SECRET=my-secret \
    relayhq/relay:latest</span>

<span class="ok">&#10003; Server starting on 0.0.0.0:6001</span>
<span class="ok">&#10003; WebSocket ready &mdash; accepting connections</span></pre>
    </div>
</div>

<div class="features">
    <div class="feat-card">
        <h3>Self-Hosted</h3>
        <p>Full control. Your server, your data, your rules. One binary, no runtime dependencies.</p>
    </div>
    <div class="feat-card">
        <h3>Relay Cloud</h3>
        <p>Managed hosting from $19/month. SSL, monitoring, and automatic updates included.</p>
    </div>
    <div class="feat-card">
        <h3>Pusher Compatible</h3>
        <p>Drop-in replacement. Switch with one config change. Same protocol, same client libraries.</p>
    </div>
</div>

<div class="section">
    <h2 class="section-title">Compare Options</h2>
    <p class="section-sub">Self-host for free, or let us manage it.</p>
    <table class="cmp-table">
        <thead><tr><th>Feature</th><th>Relay (Self-Host)</th><th>Relay Cloud</th><th>Pusher</th></tr></thead>
        <tbody>
            <tr><td>Self-hosted</td><td class="check">&#10003;</td><td>&#10003; managed</td><td class="cross">&#10007;</td></tr>
            <tr><td>Open source</td><td class="check">&#10003; MIT</td><td class="check">&#10003; MIT</td><td class="cross">&#10007;</td></tr>
            <tr><td>Free connections</td><td>Unlimited</td><td>100 (hobby)</td><td>100</td></tr>
            <tr><td>Price</td><td>$0 server cost</td><td>from $19/mo</td><td>from $49/mo</td></tr>
            <tr><td>SSL included</td><td>DIY</td><td class="check">&#10003;</td><td class="check">&#10003;</td></tr>
            <tr><td>Managed updates</td><td>DIY</td><td class="check">&#10003;</td><td class="check">&#10003;</td></tr>
            <tr><td>SLA / Support</td><td>Community</td><td class="check">&#10003;</td><td class="check">&#10003;</td></tr>
        </tbody>
    </table>
</div>

<div class="section">
    <h2 class="section-title">Simple Pricing</h2>
    <p class="section-sub">Start free. Upgrade when you grow.</p>
    <div class="pricing">
        <div class="pr-card">
            <div class="pr-name">Hobby</div>
            <div class="pr-price">$0<span>/mo</span></div>
            <div class="pr-desc">For side projects</div>
            <ul class="pr-list"><li>100 connections</li><li>500k messages/day</li><li>1 project</li></ul>
            <a href="{{ route('register') }}" class="pr-cta">Get started free &rarr;</a>
        </div>
        <div class="pr-card" style="border-color:var(--accent);box-shadow:0 0 20px var(--accent-glow)">
            <div class="pr-name">Startup</div>
            <div class="pr-price">$19<span>/mo</span></div>
            <div class="pr-desc">For growing apps</div>
            <ul class="pr-list"><li>1,000 connections</li><li>5M messages/day</li><li>5 projects</li></ul>
            <a href="{{ route('register') }}" class="pr-cta">Get started free &rarr;</a>
        </div>
        <div class="pr-card">
            <div class="pr-name">Business</div>
            <div class="pr-price">$49<span>/mo</span></div>
            <div class="pr-desc">For production at scale</div>
            <ul class="pr-list"><li>10,000 connections</li><li>Unlimited messages</li><li>20 projects</li></ul>
            <a href="{{ route('register') }}" class="pr-cta">Get started free &rarr;</a>
        </div>
    </div>
    <p style="text-align:center;font-size:13px;color:var(--t3);">Or self-host for free forever. MIT licensed, no strings attached.</p>
</div>
@endsection
