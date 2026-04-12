<?php

namespace App\Services;

use App\Models\Project;

class AppRegistryService
{
    private string $path;

    public function __construct()
    {
        $this->path = config('services.relay.apps_json_path', '/etc/relay/apps.json');
    }

    public function syncToServer(): void
    {
        $apps = Project::where('is_active', true)
            ->get()
            ->map(fn (Project $project) => [
                'id' => $project->app_id,
                'key' => $project->app_key,
                'secret' => $project->app_secret,
                'max_connections' => $project->max_connections,
                'history' => false,
            ])
            ->values()
            ->toArray();

        file_put_contents($this->path, json_encode($apps, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");

        shell_exec('pkill -HUP relay 2>/dev/null');
    }
}
