<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $logs = $request->user()->activityLogs()
            ->latest('created_at')
            ->paginate(50);

        return view('activity.index', compact('logs'));
    }
}
