@extends('layouts.docs')
@section('title', 'Relay vs Laravel Reverb')

@section('content')
<h1>Relay vs Laravel Reverb</h1>
<p class="subtitle">An honest comparison for developers choosing a WebSocket server.</p>

<p>Reverb is Laravel's official first-party WebSocket server. It is a solid choice if you are running a pure Laravel monolith and want zero additional dependencies. Relay is the better choice for almost every other scenario. Here is the honest breakdown.</p>

<h2>Feature Comparison</h2>

<table>
    <thead>
        <tr><th>Feature</th><th>Relay</th><th>Laravel Reverb</th></tr>
    </thead>
    <tbody>
        <tr><td><strong>Language</strong></td><td>Go</td><td>PHP</td></tr>
        <tr><td><strong>Works with Laravel</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Yes</td></tr>
        <tr><td><strong>Works with Next.js</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Works with Rails</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Works with Django</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Works with Node.js</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Managed cloud option</strong></td><td style="color:var(--success);">&#10003; Relay Cloud</td><td style="color:var(--danger);">&#10007; None</td></tr>
        <tr><td><strong>Self-hostable</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Yes</td></tr>
        <tr><td><strong>Open source</strong></td><td>&#10003; MIT</td><td>&#10003; MIT</td></tr>
        <tr><td><strong>Multi-app support</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Pusher protocol compatible</strong></td><td>&#10003; Full</td><td>&#10003; Full</td></tr>
        <tr><td><strong>Built-in dashboard</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Channel Inspector</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Memory at 1,000 connections</strong></td><td>~12MB</td><td>~180MB</td></tr>
    </tbody>
</table>

<h2>Runtime and Performance</h2>

<p>Go's concurrency model handles WebSocket connections via goroutines &mdash; lightweight, cheap to schedule, and shared-nothing. PHP runs in a synchronous event loop (ReactPHP under the hood in Reverb). At low connection counts the difference is negligible. At thousands of concurrent connections, the difference in memory and CPU usage is significant.</p>

<p>Relay sustains <strong>10,000 concurrent connections on a $6/month server</strong>. Reverb needs substantially more resources at the same scale. This isn't a criticism of PHP &mdash; it's simply the difference between a language designed for long-lived connections (Go) and one adapted for them (PHP).</p>

<h2>Framework Compatibility</h2>

<p>This is the most important difference. <strong>Reverb only works with Laravel.</strong> It speaks Laravel Broadcasting and nothing else.</p>

<p>If your team runs a Laravel API with a Next.js frontend, a mobile app, and a Node.js background worker &mdash; Reverb can only serve the Laravel side. Relay serves all of them from one server on one set of credentials.</p>

<p>Relay implements the full Pusher protocol. Any SDK in any language that supports Pusher &mdash; PHP, JavaScript, Python, Ruby, Go, Swift, Kotlin &mdash; works with Relay out of the box. See our <a href="{{ route('docs.guides.nextjs') }}">Next.js</a>, <a href="{{ route('docs.guides.rails') }}">Rails</a>, <a href="{{ route('docs.guides.django') }}">Django</a>, and <a href="{{ route('docs.guides.node') }}">Node.js</a> guides.</p>

<h2>The Managed Cloud Option</h2>

<p>There is no Reverb Cloud. If you want managed WebSockets in the Laravel ecosystem, your options are Pusher (expensive, proprietary) or Ably (expensive, proprietary).</p>

<p><strong>Relay Cloud</strong> is the only managed option that is open source compatible, Pusher protocol compatible, and priced for indie developers and small teams. Plans start at $0 for the Hobby tier with 100 connections.</p>

<p>And if you outgrow Relay Cloud, you pull your credentials and self-host the same binary with <strong>zero application code changes</strong>.</p>

<h2>When to Choose Reverb</h2>

<p>Choose Reverb if you are running a <strong>pure Laravel monolith</strong>, you want first-party Taylor Otwell support, and you are comfortable managing your own server infrastructure. It is a well-built product and the right tool for that specific scenario.</p>

<p>Reverb also has the advantage of being included in the Laravel ecosystem by default &mdash; no extra binary to deploy, no separate process to manage. If simplicity within Laravel is your top priority, Reverb is a legitimate choice.</p>

<h2>When to Choose Relay</h2>

<p>Choose Relay if:</p>
<ul>
    <li>You are on a <strong>polyglot stack</strong> (Laravel + Next.js, Rails, Django, Node, mobile)</li>
    <li>You want a <strong>managed cloud option</strong> without vendor lock-in</li>
    <li>You want a built-in <strong>Channel Inspector</strong> for debugging live connections</li>
    <li>You need <strong>multi-app support</strong> (multiple projects on one server)</li>
    <li>You want better <strong>performance per dollar</strong> at scale</li>
    <li>You want to <strong>self-host now and move to cloud later</strong> (or vice versa) with no code changes</li>
</ul>

<h2>Get Started</h2>

<p>Ready to try Relay?</p>
<ul>
    <li><a href="{{ route('docs.cloud.getting-started') }}"><strong>Relay Cloud</strong></a> &mdash; Managed hosting, free tier, production-ready in 60 seconds</li>
    <li><a href="{{ route('docs.os.getting-started') }}"><strong>Self-host Relay</strong></a> &mdash; One binary, Docker or bare metal, MIT licensed</li>
</ul>
@endsection
