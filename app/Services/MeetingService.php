<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MeetingService
{
    /**
     * Create a meeting link.
     * Uses Zoom API if configured, otherwise generates a Google Meet-style link.
     */
    public function createMeeting(array $data): array
    {
        if (config('services.zoom.account_id')) {
            return $this->createZoomMeeting($data);
        }

        // Fallback: generate a unique Google Meet-style link
        return $this->generateFallbackLink($data);
    }

    private function createZoomMeeting(array $data): array
    {
        try {
            $token    = $this->getZoomAccessToken();
            $response = Http::withToken($token)->post('https://api.zoom.us/v2/users/me/meetings', [
                'topic'      => $data['topic'] ?? 'Peace Institute Session',
                'type'       => 2,
                'start_time' => date('Y-m-d\TH:i:s', strtotime($data['start_time'])),
                'duration'   => $data['duration'] ?? 60,
                'timezone'   => 'UTC',
                'settings'   => [
                    'host_video'        => true,
                    'participant_video'  => true,
                    'waiting_room'       => true,
                    'mute_upon_entry'    => true,
                ],
            ]);

            $meeting = $response->json();

            return [
                'id'       => (string) $meeting['id'],
                'link'     => $meeting['join_url'],
                'password' => $meeting['password'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Zoom meeting creation failed', ['error' => $e->getMessage()]);
            return $this->generateFallbackLink($data);
        }
    }

    private function getZoomAccessToken(): string
    {
        $response = Http::asForm()->withBasicAuth(
            config('services.zoom.client_id'),
            config('services.zoom.client_secret')
        )->post('https://zoom.us/oauth/token', [
            'grant_type' => 'account_credentials',
            'account_id' => config('services.zoom.account_id'),
        ]);

        return $response->json('access_token');
    }

    private function generateFallbackLink(array $data): array
    {
        // Generate a pseudo-unique meeting room name
        $roomId = strtolower(str_replace(' ', '-', substr($data['topic'] ?? 'session', 0, 20)))
            .'-'.substr(md5(uniqid()), 0, 8);

        return [
            'id'   => $roomId,
            'link' => "https://meet.google.com/{$roomId}",
        ];
    }
}
