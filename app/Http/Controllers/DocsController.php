<?php

namespace App\Http\Controllers;

class DocsController extends Controller
{
    public function index()
    {
        return view('docs.index');
    }

    public function openSourceGettingStarted()
    {
        return view('docs.open-source.getting-started');
    }

    public function openSourceConfiguration()
    {
        return view('docs.open-source.configuration');
    }

    public function openSourceApiReference()
    {
        return view('docs.open-source.api-reference');
    }

    public function openSourceSdks()
    {
        return view('docs.open-source.sdks');
    }

    public function cloudGettingStarted()
    {
        return view('docs.cloud.getting-started');
    }

    public function cloudProjects()
    {
        return view('docs.cloud.projects');
    }

    public function cloudBilling()
    {
        return view('docs.cloud.billing');
    }

    public function nextjs()
    {
        return view('docs.guides.nextjs');
    }

    public function rails()
    {
        return view('docs.guides.rails');
    }

    public function django()
    {
        return view('docs.guides.django');
    }

    public function node()
    {
        return view('docs.guides.node');
    }

    public function pusherSdks()
    {
        return view('docs.guides.pusher-sdks');
    }

    public function vsReverb()
    {
        return view('docs.vs-reverb');
    }
}
