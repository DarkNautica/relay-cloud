@extends('layouts.docs')
@section('title', 'Managing Projects')

@section('content')
<h1>Managing Projects</h1>
<p class="subtitle">Everything you need to know about Relay Cloud projects.</p>

<h2>What is a Project?</h2>
<p>A project is an isolated WebSocket application with its own set of credentials. Each project has independent connection limits and can be paused or deleted without affecting other projects.</p>
<p>Think of a project as a single app &mdash; your production app, staging environment, or a side project each get their own project.</p>

<h2>Creating a Project</h2>
<p>From the <a href="{{ route('dashboard') }}">dashboard</a> or <a href="{{ route('projects.index') }}">projects page</a>, click <strong>"+ New Project"</strong>.</p>
<p>Enter a name (e.g. "Production API", "Staging") and click <strong>Create Project</strong>. Credentials are generated instantly.</p>

<h2>Your Credentials</h2>
<p>Each project provides four values:</p>
<table>
    <thead><tr><th>Credential</th><th>Usage</th><th>Keep Secret?</th></tr></thead>
    <tbody>
        <tr><td><strong>App ID</strong></td><td>Unique project identifier</td><td>No</td></tr>
        <tr><td><strong>App Key</strong></td><td>Public key for client connections</td><td>No</td></tr>
        <tr><td><strong>App Secret</strong></td><td>Server-side signing and publishing</td><td><strong>Yes</strong></td></tr>
        <tr><td><strong>WebSocket URL</strong></td><td><code>wss://ws.relaycloud.dev/app/{key}</code></td><td>No</td></tr>
    </tbody>
</table>
<div class="note"><strong>Important:</strong> Never expose your App Secret in client-side code. It should only be used on your backend server.</div>

<h2>Connection Limits by Plan</h2>
<table>
    <thead><tr><th>Plan</th><th>Max Connections</th><th>Max Projects</th><th>Messages/Day</th></tr></thead>
    <tbody>
        <tr><td>Hobby ($0)</td><td>100</td><td>1</td><td>500,000</td></tr>
        <tr><td>Startup ($19)</td><td>1,000</td><td>5</td><td>5,000,000</td></tr>
        <tr><td>Business ($49)</td><td>10,000</td><td>20</td><td>Unlimited</td></tr>
    </tbody>
</table>

<h2>Pausing and Resuming Projects</h2>
<p>You can pause a project to temporarily disable connections without deleting it. Paused projects:</p>
<ul>
    <li>Reject new WebSocket connections</li>
    <li>Are removed from the active app registry</li>
    <li>Retain all credentials (unchanged when resumed)</li>
    <li>Don't count against your connection limits</li>
</ul>
<p>To pause or resume, use the three-dot menu on any project row, or the project detail page.</p>

<h2>Deleting a Project</h2>
<p>Deleting a project is <strong>permanent</strong>. All credentials are revoked immediately, and any connected clients will be disconnected. This cannot be undone.</p>
<p>Delete from the project detail page under "Danger Zone".</p>

<h2>Rotating Credentials</h2>
<div class="note"><strong>Coming soon.</strong> Credential rotation will let you generate new keys without downtime. In the meantime, create a new project and migrate your config.</div>
@endsection
