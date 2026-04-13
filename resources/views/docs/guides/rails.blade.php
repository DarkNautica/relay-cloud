@extends('layouts.docs')

@section('title', 'Using Relay with Ruby on Rails')

@section('content')
<div class="prose">
    <h1>Using Relay with Ruby on Rails</h1>
    <p>Connect your Rails app to Relay as a Pusher-compatible broadcaster.</p>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Skip the server setup.</strong> Relay Cloud gives you managed WebSockets with a free tier. Connect in 60 seconds.
        <a href="/register">Try free &rarr;</a>
    </div>

    <h2>Prerequisites</h2>
    <ul>
        <li>A running Relay server</li>
        <li>Your App Key, Secret, and ID</li>
    </ul>

    <div class="step">
        <span class="step-num">1</span>
        <h2>Add Gem</h2>
    </div>

    <p>Add the Pusher gem to your Gemfile:</p>

    <pre><code class="language-ruby">gem 'pusher'</code></pre>

    <p>Then install:</p>

    <pre><code class="language-bash">bundle install</code></pre>

    <div class="step">
        <span class="step-num">2</span>
        <h2>Configure the Initializer</h2>
    </div>

    <p>Create <code>config/initializers/relay.rb</code>:</p>

    <pre><code class="language-ruby">require 'pusher'

Pusher.app_id  = ENV['RELAY_APP_ID']
Pusher.key     = ENV['RELAY_APP_KEY']
Pusher.secret  = ENV['RELAY_APP_SECRET']
Pusher.host    = ENV.fetch('RELAY_HOST', 'ws.relaycloud.dev')
Pusher.port    = ENV.fetch('RELAY_PORT', '443').to_i
Pusher.scheme  = ENV.fetch('RELAY_SCHEME', 'https')</code></pre>

    <div class="step">
        <span class="step-num">3</span>
        <h2>Environment Variables</h2>
    </div>

    <pre><code class="language-bash">RELAY_APP_ID=your-app-id
RELAY_APP_KEY=your-app-key
RELAY_APP_SECRET=your-app-secret
RELAY_HOST=ws.relaycloud.dev</code></pre>

    <div class="step">
        <span class="step-num">4</span>
        <h2>Trigger an Event</h2>
    </div>

    <pre><code class="language-ruby">Pusher.trigger('public-feed', 'message.sent', { message: 'Hello from Rails!' })</code></pre>

    <div class="step">
        <span class="step-num">5</span>
        <h2>Controller Example</h2>
    </div>

    <pre><code class="language-ruby">class MessagesController &lt; ApplicationController
  def create
    @message = Message.create!(message_params)
    Pusher.trigger('public-feed', 'message.sent', {
      message: @message.body,
      user_id: current_user.id
    })
    render json: @message
  end
end</code></pre>

    <div class="step">
        <span class="step-num">6</span>
        <h2>Frontend</h2>
    </div>

    <p>On the client side, use the <a href="https://github.com/pusher/pusher-js">Pusher JS client</a> to subscribe to channels and receive events in real time. See the <a href="/docs/guides/nextjs">Next.js guide</a> for a full frontend example.</p>

    <div class="note">
        <p>Relay implements the full Pusher HTTP API. Any gem or library that supports custom Pusher hosts works with Relay.</p>
    </div>

    <div class="cloud-nudge">
        <span class="cloud-nudge-icon">&#9889;</span>
        <strong>Ready to skip the server setup?</strong> Relay Cloud gives you a production WebSocket server instantly. Start free, upgrade when you grow.
        <a href="/register">Start free &rarr;</a>
    </div>
</div>
@endsection
