<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Support\Facades\Http;

class RelayServerService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.relay.url', 'http://127.0.0.1:6001'), '/');
    }

    public function getServerStats(): array
    {
        try {
            $response = Http::timeout(2)->get("{$this->baseUrl}/stats");

            if ($response->successful()) {
                return [
                    'connections' => (int) ($response->json('connections') ?? 0),
                    'channels' => (int) ($response->json('channels') ?? 0),
                ];
            }
        } catch (\Exception $e) {
            //
        }

        return ['connections' => 0, 'channels' => 0];
    }

    public function getProjectStats(string $appId, string $appSecret): array
    {
        try {
            $response = Http::timeout(2)
                ->withToken($appSecret)
                ->get("{$this->baseUrl}/apps/{$appId}/channels");

            if ($response->successful()) {
                return [
                    'channels' => (int) ($response->json('channels') ?? 0),
                    'subscriber_count' => (int) ($response->json('subscriber_count') ?? 0),
                ];
            }
        } catch (\Exception $e) {
            //
        }

        return ['channels' => 0, 'subscriber_count' => 0];
    }

    public function getProjectEventLog(string $appId, string $appSecret): array
    {
        try {
            $response = Http::timeout(2)
                ->withToken($appSecret)
                ->get("{$this->baseUrl}/apps/{$appId}/events/log");

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Exception $e) {
            //
        }

        return [];
    }

    public function getProjectChannels(Project $project): array
    {
        try {
            $response = Http::timeout(2)
                ->withToken($project->app_secret)
                ->get("{$this->baseUrl}/apps/{$project->app_id}/channels");

            if ($response->successful()) {
                return $response->json('channels') ?? [];
            }
        } catch (\Exception $e) {
            //
        }

        return [];
    }

    public function getChannelEvents(Project $project, string $channel, int $limit = 25, ?string $cursor = null): array
    {
        try {
            $query = ['limit' => $limit];
            if ($cursor) {
                $query['cursor'] = $cursor;
            }

            $response = Http::timeout(2)
                ->withToken($project->app_secret)
                ->get("{$this->baseUrl}/apps/{$project->app_id}/channels/{$channel}/events", $query);

            if ($response->successful()) {
                return [
                    'events' => $response->json('events') ?? [],
                    'next_cursor' => $response->json('next_cursor'),
                ];
            }
        } catch (\Exception $e) {
            //
        }

        return ['events' => [], 'next_cursor' => null];
    }

    public function isServerOnline(): bool
    {
        try {
            $response = Http::timeout(2)->get("{$this->baseUrl}/health");

            return $response->status() === 200;
        } catch (\Exception $e) {
            return false;
        }
    }
}
