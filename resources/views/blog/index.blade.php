@extends('layouts.docs')
@section('title', 'Blog')
@section('no-sidebar', true)

@section('content')
<div style="max-width:680px;margin:0 auto;padding:56px 24px;">
    <h1 style="font-size:28px;font-weight:700;letter-spacing:-0.02em;margin-bottom:6px;">Blog</h1>
    <p style="font-size:15px;color:var(--t2);margin-bottom:40px;">Technical writing from the Relay team.</p>

    @foreach($posts as $post)
    <a href="{{ route('blog.show', $post['slug']) }}" style="display:block;padding:24px;margin-bottom:16px;border-radius:12px;border:1px solid var(--border);border-top:1px solid rgba(255,255,255,0.06);background:linear-gradient(145deg,#161620,#111115);box-shadow:0 1px 3px rgba(0,0,0,0.5),inset 0 1px 0 rgba(255,255,255,0.04);transition:border-color 150ms;">
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px;font-size:12px;color:var(--t3);">
            <span>{{ $post['published_at'] }}</span>
            <span>&middot;</span>
            <span>{{ $post['read_time'] }}</span>
        </div>
        <div style="font-size:18px;font-weight:700;color:var(--t1);margin-bottom:6px;letter-spacing:-0.01em;">{{ $post['title'] }}</div>
        <div style="font-size:14px;color:var(--t2);line-height:1.6;">{{ $post['excerpt'] }}</div>
        <div style="margin-top:12px;font-size:13px;font-weight:600;color:var(--accent-l);">Read more &rarr;</div>
    </a>
    @endforeach
</div>
@endsection
