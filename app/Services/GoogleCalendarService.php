<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Creates Google Calendar events from bookings using a service account.
 *
 * No external SDK: we sign the service-account JWT with openssl, exchange it
 * for an access token, then call the Calendar REST API via Laravel's HTTP
 * client. Every method fails soft — a booking must still succeed even if the
 * calendar is misconfigured or Google is unreachable.
 *
 * Setup lives in docs/TEST-ACCOUNT-SETUP.md.
 */
class GoogleCalendarService
{
    public function isEnabled(): bool
    {
        return (bool) config('emcey.google_calendar.enabled')
            && filled(config('emcey.google_calendar.calendar_id'))
            && filled(config('emcey.google_calendar.credentials'));
    }

    /**
     * @return array{id: string, link: string}|null
     */
    public function createEventForAppointment(Appointment $appointment): ?array
    {
        if (! $this->isEnabled()) {
            return null;
        }

        try {
            $token = $this->accessToken();
            if (! $token) {
                return null;
            }

            $calendarId = (string) config('emcey.google_calendar.calendar_id');
            $timezone = (string) config('emcey.google_calendar.timezone', 'Asia/Manila');

            $start = $appointment->scheduled_at->copy();
            $end = $start->copy()->addMinutes($appointment->duration_minutes ?? 60);
            $service = $appointment->service?->name ?? 'Appointment';

            $body = [
                'summary' => "{$service} — {$appointment->customer_name}",
                'description' => implode("\n", array_filter([
                    "Service: {$service}",
                    "Client: {$appointment->customer_name}",
                    "Phone: {$appointment->customer_phone}",
                    "Email: {$appointment->customer_email}",
                    "Reference: {$appointment->reference}",
                    $appointment->notes ? "Notes: {$appointment->notes}" : null,
                ])),
                'start' => [
                    'dateTime' => $start->format('Y-m-d\TH:i:s'),
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $end->format('Y-m-d\TH:i:s'),
                    'timeZone' => $timezone,
                ],
            ];

            $response = Http::withToken($token)
                ->post(
                    'https://www.googleapis.com/calendar/v3/calendars/'
                        . rawurlencode($calendarId) . '/events',
                    $body,
                );

            if (! $response->successful()) {
                Log::warning('Google Calendar event creation failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return [
                'id' => (string) $response->json('id'),
                'link' => (string) $response->json('htmlLink'),
            ];
        } catch (\Throwable $e) {
            Log::warning('Google Calendar error: ' . $e->getMessage());

            return null;
        }
    }

    /**
     * Remove a previously-created event from the studio calendar (e.g. when a
     * customer cancels). Best-effort; clears the stored id on success or if the
     * event is already gone (410).
     */
    public function deleteEventForAppointment(Appointment $appointment): bool
    {
        if (! $this->isEnabled() || blank($appointment->google_event_id)) {
            return false;
        }

        try {
            $token = $this->accessToken();
            if (! $token) {
                return false;
            }

            $calendarId = (string) config('emcey.google_calendar.calendar_id');

            $response = Http::withToken($token)->delete(
                'https://www.googleapis.com/calendar/v3/calendars/'
                    . rawurlencode($calendarId) . '/events/'
                    . rawurlencode((string) $appointment->google_event_id),
            );

            if ($response->successful() || $response->status() === 410) {
                $appointment->forceFill([
                    'google_event_id' => null,
                    'google_event_link' => null,
                ])->save();

                return true;
            }

            Log::warning('Google Calendar event delete failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return false;
        } catch (\Throwable $e) {
            Log::warning('Google Calendar delete error: ' . $e->getMessage());

            return false;
        }
    }

    /**
     * Busy intervals from the business calendar, grouped by date and expressed
     * as minute-of-day ranges so the booking calendar can grey out slots that
     * overlap real calendar events. Fails soft to an empty map. Cached 5 min.
     *
     * @return array<string, array<int, array{0:int,1:int}>>  "Y-m-d" => [[startMin,endMin], …]
     */
    public function busyIntervals(CarbonImmutable $from, CarbonImmutable $to): array
    {
        if (! $this->isEnabled() || ! (bool) config('emcey.google_calendar.block_availability', true)) {
            return [];
        }

        $tz = (string) config('emcey.google_calendar.timezone', 'Asia/Manila');
        $calendarId = (string) config('emcey.google_calendar.calendar_id');
        $cacheKey = 'gcal_busy_' . md5($calendarId . $from->toDateString() . $to->toDateString());

        try {
            return Cache::remember($cacheKey, now()->addMinutes(5), function () use ($from, $to, $tz, $calendarId) {
                $token = $this->accessToken();
                if (! $token) {
                    return [];
                }

                $response = Http::withToken($token)->post('https://www.googleapis.com/calendar/v3/freeBusy', [
                    'timeMin' => $from->startOfDay()->toRfc3339String(),
                    'timeMax' => $to->endOfDay()->toRfc3339String(),
                    'timeZone' => $tz,
                    'items' => [['id' => $calendarId]],
                ]);

                if (! $response->successful()) {
                    Log::warning('Google Calendar freeBusy failed', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);

                    return [];
                }

                $calendars = $response->json('calendars') ?? [];
                $busy = $calendars[$calendarId]['busy'] ?? [];

                $map = [];
                foreach ($busy as $b) {
                    if (empty($b['start']) || empty($b['end'])) {
                        continue;
                    }
                    $start = CarbonImmutable::parse($b['start'])->setTimezone($tz);
                    $end = CarbonImmutable::parse($b['end'])->setTimezone($tz);

                    // Split the interval across each date it touches.
                    for ($cursor = $start->startOfDay(); $cursor->lessThanOrEqualTo($end); $cursor = $cursor->addDay()) {
                        $dayStart = $cursor->startOfDay();
                        $dayEnd = $cursor->endOfDay();
                        $segStart = $start->greaterThan($dayStart) ? $start : $dayStart;
                        $segEnd = $end->lessThan($dayEnd) ? $end : $dayEnd;

                        if ($segEnd->lessThanOrEqualTo($segStart)) {
                            continue;
                        }

                        $startMin = $segStart->hour * 60 + $segStart->minute;
                        // Busy running to (or past) end of day blocks through 1440.
                        $endMin = $end->greaterThan($dayEnd)
                            ? 24 * 60
                            : $segEnd->hour * 60 + $segEnd->minute;

                        $map[$cursor->toDateString()][] = [$startMin, $endMin];
                    }
                }

                return $map;
            });
        } catch (\Throwable $e) {
            Log::warning('Google Calendar busyIntervals error: ' . $e->getMessage());

            return [];
        }
    }

    private function accessToken(): ?string
    {
        $path = (string) config('emcey.google_calendar.credentials');

        if (! is_file($path)) {
            Log::warning("Google service-account key not found at: {$path}");

            return null;
        }

        $creds = json_decode((string) file_get_contents($path), true);

        if (! is_array($creds) || ! isset($creds['client_email'], $creds['private_key'])) {
            Log::warning('Invalid Google service-account key file.');

            return null;
        }

        $now = time();
        $jwt = $this->signJwt(
            ['alg' => 'RS256', 'typ' => 'JWT'],
            [
                'iss' => $creds['client_email'],
                // Full calendar scope: covers both writing events and the
                // freeBusy availability query (calendar.events alone 403s on freeBusy).
                'scope' => 'https://www.googleapis.com/auth/calendar',
                'aud' => 'https://oauth2.googleapis.com/token',
                'iat' => $now,
                'exp' => $now + 3600,
            ],
            $creds['private_key'],
        );

        if (! $jwt) {
            return null;
        }

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (! $response->successful()) {
            Log::warning('Google token exchange failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        }

        return $response->json('access_token');
    }

    private function signJwt(array $header, array $claims, string $privateKey): ?string
    {
        $signingInput = $this->b64url((string) json_encode($header))
            . '.' . $this->b64url((string) json_encode($claims));

        $signature = '';
        if (! openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            Log::warning('Failed to sign Google service-account JWT.');

            return null;
        }

        return $signingInput . '.' . $this->b64url($signature);
    }

    private function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
