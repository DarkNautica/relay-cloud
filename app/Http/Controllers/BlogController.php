<?php

namespace App\Http\Controllers;

class BlogController extends Controller
{
    public function index()
    {
        $posts = [
            [
                'slug' => 'relay-vs-reverb-benchmark',
                'title' => 'Relay vs Laravel Reverb: A Real Performance Benchmark',
                'excerpt' => 'We ran 1,000 concurrent WebSocket connections against both servers. Here\'s what happened.',
                'published_at' => '2026-04-13',
                'read_time' => '5 min read',
            ],
        ];

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $validSlugs = ['relay-vs-reverb-benchmark'];

        if (! in_array($slug, $validSlugs)) {
            abort(404);
        }

        return view("blog.posts.{$slug}");
    }
}
