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
                ->get("{$this->baseUrl}/apps/{$appId}/stats");

            if ($response->successful()) {
                return [
                    'connections' => (int) ($response->json('connections') ?? 0),
                    'peak_connections' => (int) ($response->json('peak_connections') ?? 0),
                    'messages_count' => (int) ($response->json('messages_count') ?? 0),
                    // Keep subscriber_count as alias for connections for backward compat
                    'subscriber_count' => (int) ($response->json('connections') ?? 0),
                    'channels' => (int) ($response->json('channels') ?? 0),
                ];
            }
        } catch (\Exception $e) {
            //
        }

        return ['connections' => 0, 'peak_connections' => 0, 'messages_count' => 0, 'subscriber_count' => 0, 'channels' => 0];
    }

    public function getProjectEventLog(string $appId, string $appSecret): array
    {
        try {
            // Fetch all active channels, then recent events from each
            $channelsResponse = Http::timeout(2)
                ->withToken($appSecret)
                ->get("{$this->baseUrl}/apps/{$appId}/channels");

            if (! $channelsResponse->successful()) {
                return [];
            }

            $channels = $channelsResponse->json('channels') ?? [];
            $allEvents = [];

            foreach (array_keys($channels) as $channelName) {
                try {
                    $evResponse = Http::timeout(2)
                        ->withToken($appSecret)
                        ->get("{$this->baseUrl}/apps/{$appId}/channels/{$channelName}/events", ['limit' => 10]);

                    if ($evResponse->successful()) {
                        $events = $evResponse->json('events') ?? [];
                        foreach ($events as &$event) {
                            $event['channel'] = $channelName;
                        }
                        $allEvents = array_merge($allEvents, $events);
                    }
                } catch (\Exception $e) {
                    //
                }
            }

            // Sort by timestamp descending
            usort($allEvents, function ($a, $b) {
                return strcmp($b['timestamp'] ?? '', $a['timestamp'] ?? '');
            });

            return array_slice($allEvents, 0, 20);
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

    public function getProjectEvents(Project $project, int $limit = 25, ?string $cursor = null, ?string $channel = null): array
    {
        try {
            $query = ['limit' => $limit];
            if ($cursor) {
                $query['cursor'] = $cursor;
            }
            if ($channel) {
                $query['channel'] = $channel;
            }

            $response = Http::timeout(2)
                ->withToken($project->app_secret)
                ->get("{$this->baseUrl}/apps/{$project->app_id}/events", $query);

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

    public function getEventDetail(Project $project, string $eventId): ?array
    {
        try {
            $response = Http::timeout(2)
                ->withToken($project->app_secret)
                ->get("{$this->baseUrl}/apps/{$project->app_id}/events/{$eventId}");

            if ($response->successful()) {
                return $response->json();
            }
        } catch (\Exception $e) {
            //
        }

        return null;
    }

    public function replayEvent(Project $project, string $eventId): bool
    {
        try {
            $response = Http::timeout(3)
                ->withToken($project->app_secret)
                ->post("{$this->baseUrl}/apps/{$project->app_id}/events/{$eventId}/replay");

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }

    public function getProjectMetrics(Project $project): array
    {
        try {
            $response = Http::timeout(2)
                ->withToken($project->app_secret)
                ->get("{$this->baseUrl}/apps/{$project->app_id}/metrics");

            if ($response->successful()) {
                return $response->json() ?? [];
            }
        } catch (\Exception $e) {
            //
        }

        return [];
    }

    public function publishEvent(string $appId, string $appSecret, string $channel, string $event, array $data): bool
    {
        try {
            $response = Http::timeout(3)
                ->withToken($appSecret)
                ->post("{$this->baseUrl}/apps/{$appId}/events", [
                    'event' => $event,
                    'channel' => $channel,
                    'data' => json_encode($data),
                ]);

            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
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
