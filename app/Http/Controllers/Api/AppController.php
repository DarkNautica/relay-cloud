<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;

class AppController extends Controller
{
    public function show(string $appKey)
    {
        $project = Project::where('app_key', $appKey)
            ->where('is_active', true)
            ->first();

        if (! $project) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json([
            'app_id' => $project->app_id,
            'app_key' => $project->app_key,
            'app_secret' => $project->app_secret,
            'max_connections' => $project->max_connections,
        ]);
    }
}
