@extends('layouts.docs')
@section('title', 'Billing & Plans')

@section('content')
<h1>Billing & Plans</h1>
<p class="subtitle">Simple, transparent pricing. Start free, upgrade when you grow.</p>

<h2>Plan Overview</h2>
<table>
    <thead><tr><th>Feature</th><th>Hobby ($0)</th><th>Startup ($19)</th><th>Business ($49)</th></tr></thead>
    <tbody>
        <tr><td>Max Connections</td><td>100</td><td>1,000</td><td>10,000</td></tr>
        <tr><td>Messages/Day</td><td>500,000</td><td>5,000,000</td><td>Unlimited</td></tr>
        <tr><td>Projects</td><td>1</td><td>5</td><td>20</td></tr>
        <tr><td>SSL</td><td>Included</td><td>Included</td><td>Included</td></tr>
        <tr><td>Support</td><td>Community</td><td>Email</td><td>Priority</td></tr>
    </tbody>
</table>

<h2>Upgrading Your Plan</h2>
<p>Navigate to <a href="{{ route('billing.index') }}">Billing & Plans</a> in your dashboard. Click the <strong>"Upgrade"</strong> button on the plan you want.</p>
<p>You'll be redirected to Stripe Checkout to enter payment details. Your plan upgrades immediately after payment &mdash; connection limits and project slots increase right away.</p>

<h2>Managing Your Subscription</h2>
<p>Click <strong>"Manage Billing"</strong> on the billing page to open the Stripe Customer Portal. From there you can:</p>
<ul>
    <li>Update your payment method</li>
    <li>View and download invoices</li>
    <li>Change your plan</li>
    <li>Cancel your subscription</li>
</ul>

<h2>Cancellation Policy</h2>
<p>You can cancel anytime from the Stripe portal. When you cancel:</p>
<ul>
    <li>Your plan stays active until the end of the current billing period</li>
    <li>After expiry, your account downgrades to the Hobby plan</li>
    <li>Connection limits reduce to 100 and project slots to 1</li>
    <li>Existing projects beyond the Hobby limit are <strong>paused, not deleted</strong></li>
</ul>

<h2>What Happens If I Exceed Limits?</h2>
<p>Relay Cloud handles limit overages gracefully:</p>
<ul>
    <li><strong>Connections:</strong> New connections are queued, not dropped. Existing connections stay active.</li>
    <li><strong>Messages:</strong> Messages continue to deliver. You'll receive an email notification when you hit 80% and 100% of your daily limit.</li>
    <li><strong>Projects:</strong> You can't create new projects beyond your limit, but existing projects remain active.</li>
</ul>

<h2>FAQ</h2>

<h3>Do I need a credit card to start?</h3>
<p>No. The Hobby plan is completely free with no credit card required.</p>

<h3>Can I switch plans at any time?</h3>
<p>Yes. Upgrades take effect immediately. Downgrades take effect at the end of your billing period.</p>

<h3>Is there a free trial for paid plans?</h3>
<p>The Hobby plan serves as a free tier. You can use it indefinitely with 100 connections.</p>

<h3>What payment methods do you accept?</h3>
<p>All major credit and debit cards via Stripe. We also support Apple Pay and Google Pay where available.</p>

<h3>Can I get an annual discount?</h3>
<p>Annual billing is coming soon. Contact us for enterprise pricing.</p>
@endsection
