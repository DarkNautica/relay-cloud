<?php

namespace App\Console\Commands;

use App\Models\Project;
use App\Models\UsageStat;
use App\Services\RelayServerService;
use Illuminate\Console\Command;

class RecordUsageCommand extends Command
{
    protected $signature = 'relay:record-usage';

    protected $description = 'Record current usage stats from the Relay server';

    public function handle(RelayServerService $relay): int
    {
        $projects = Project::where('is_active', true)->get();

        foreach ($projects as $project) {
            $stats = $relay->getProjectStats($project->app_id, $project->app_secret);

            UsageStat::create([
                'project_id' => $project->id,
                'connections_peak' => $stats['subscriber_count'],
                'messages_count' => 0,
                'recorded_at' => now(),
            ]);
        }

        $this->info('Recorded usage for ' . $projects->count() . ' projects.');

        return self::SUCCESS;
    }
}
