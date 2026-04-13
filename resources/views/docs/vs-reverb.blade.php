@extends('layouts.docs')
@section('title', 'Relay vs Laravel Reverb')

@section('content')
<h1>Relay vs Laravel Reverb</h1>
<p class="subtitle">An honest comparison for developers choosing a WebSocket server.</p>

<p>Reverb is Laravel's official first-party WebSocket server, maintained by Taylor Otwell and the Laravel team. It is deeply integrated into the Laravel ecosystem and an excellent choice for Laravel-first teams. Relay takes a different approach &mdash; a standalone Go binary with no runtime dependencies, better resource efficiency, and a built-in channel inspector. Here is the honest breakdown.</p>

<h2>Feature Comparison</h2>

<table>
    <thead>
        <tr><th>Feature</th><th>Relay</th><th>Laravel Reverb</th></tr>
    </thead>
    <tbody>
        <tr><td><strong>Language</strong></td><td>Go</td><td>PHP</td></tr>
        <tr><td><strong>Runtime required to host</strong></td><td style="color:var(--success);">None &mdash; standalone binary</td><td>PHP + Laravel app required</td></tr>
        <tr><td><strong>Managed cloud option</strong></td><td style="color:var(--success);">&#10003; Relay Cloud</td><td style="color:var(--success);">&#10003; Laravel Cloud</td></tr>
        <tr><td><strong>Self-hostable</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Yes</td></tr>
        <tr><td><strong>Open source</strong></td><td>&#10003; MIT</td><td>&#10003; MIT</td></tr>
        <tr><td><strong>Works with any Pusher client</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Yes</td></tr>
        <tr><td><strong>Multi-app support</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Yes</td></tr>
        <tr><td><strong>Channel Inspector</strong></td><td style="color:var(--success);">&#10003; Built-in</td><td style="color:var(--danger);">&#10007; No</td></tr>
        <tr><td><strong>Memory at 1,000 connections</strong></td><td style="color:var(--success);">~38 MB</td><td>~63 MB</td></tr>
        <tr><td><strong>CPU at 1,000 connections</strong></td><td style="color:var(--success);">~18%</td><td>~95%</td></tr>
        <tr><td><strong>Exit from managed hosting</strong></td><td style="color:var(--success);">&#10003; Self-host same binary</td><td style="color:var(--danger);">&#10007; Locked to Laravel Cloud</td></tr>
        <tr><td><strong>First-party Laravel package</strong></td><td style="color:var(--success);">&#10003; Yes</td><td style="color:var(--success);">&#10003; Built into Laravel</td></tr>
    </tbody>
</table>

<h2>Runtime and Performance</h2>

<p>The fundamental architectural difference: Reverb requires a running PHP and Laravel application to host the WebSocket server. Relay is a standalone Go binary &mdash; no PHP, no Composer, no Laravel runtime required. Deploy it anywhere you can run a binary or a Docker container.</p>

<p>Go's concurrency model handles WebSocket connections via goroutines &mdash; lightweight, cheap to schedule, and shared-nothing. PHP runs in a synchronous event loop (ReactPHP under the hood in Reverb). At low connection counts the difference is negligible. At thousands of concurrent connections, the gap in resource usage is significant.</p>

<p>In our <a href="{{ route('blog.show', 'relay-vs-reverb-benchmark') }}">benchmark at 1,000 concurrent connections</a>: Relay used <strong>~18% CPU and ~38 MB RAM</strong>. Reverb used <strong>~95% CPU and ~63 MB RAM</strong> on equivalent hardware. Go's goroutine model handles WebSocket concurrency more efficiently than PHP's event loop. This isn't a criticism of PHP &mdash; it's simply the difference between a language designed for long-lived connections and one adapted for them.</p>

<h2>Channel Inspector</h2>

<p>Relay includes a built-in <strong>Channel Inspector</strong> &mdash; a live dashboard view of active channels, subscriber counts, and real-time event payloads with syntax highlighting. This is invaluable for debugging WebSocket connections during development and monitoring production traffic. Nothing equivalent exists in Reverb or Laravel Cloud.</p>

<h2>The Managed Cloud Option</h2>

<p>Both Relay and Reverb have managed hosting options. <strong>Laravel Cloud</strong> offers fully managed Reverb clusters integrated into the Laravel ecosystem. <strong>Relay Cloud</strong> offers managed WebSocket hosting with plans starting at $0 for the Hobby tier.</p>

<p>The key difference is exit strategy. Laravel Cloud WebSockets ties you to their ecosystem &mdash; there is no way to take your managed Reverb setup and self-host it independently. Relay Cloud is the only managed WebSocket platform where you can take the same binary and self-host with two environment variable changes. No lock-in.</p>

<p>Pusher and Ably are also options, but neither offers a self-hosted version. With Relay, you always have an open source exit ramp.</p>

<h2>When to Choose Reverb</h2>

<p>Reverb is a great product and the right choice for many teams. Choose Reverb if:</p>
<ul>
    <li>You are running a <strong>pure Laravel stack</strong> and want first-party Taylor Otwell support</li>
    <li>You want deep integration with <strong>Laravel Pulse</strong> for monitoring</li>
    <li>You want a WebSocket server that's <strong>built into the Laravel framework</strong> with no extra binary to deploy</li>
    <li>You are comfortable with <strong>Laravel Cloud</strong> for managed hosting</li>
    <li>Simplicity within the Laravel ecosystem is your top priority</li>
</ul>

<h2>When to Choose Relay</h2>

<p>Choose Relay if:</p>
<ul>
    <li>You want a <strong>lightweight standalone server</strong> with no PHP or Laravel runtime dependency</li>
    <li>You want better <strong>resource efficiency</strong> &mdash; lower CPU and memory per connection at scale</li>
    <li>You want a built-in <strong>Channel Inspector</strong> for debugging live connections and event payloads</li>
    <li>You want a <strong>managed cloud option without lock-in</strong> &mdash; self-host the same binary anytime</li>
    <li>You want better <strong>performance per dollar</strong> at scale</li>
    <li>You want to <strong>self-host now and move to cloud later</strong> (or vice versa) with no code changes</li>
</ul>

<h2>Pricing</h2>

<p>Relay Cloud pricing is simple and transparent: Hobby free, Startup $19/mo, Business $49/mo.</p>

<h2>Get Started</h2>

<p>Ready to try Relay?</p>
<ul>
    <li><a href="{{ route('docs.cloud.getting-started') }}"><strong>Relay Cloud</strong></a> &mdash; Managed hosting, free tier, production-ready in 60 seconds</li>
    <li><a href="{{ route('docs.os.getting-started') }}"><strong>Self-host Relay</strong></a> &mdash; One binary, Docker or bare metal, MIT licensed</li>
</ul>
@endsection
