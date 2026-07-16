<?php

// App-specific integration toggles for Emcey Brows. These are safe to leave
// off in production; they exist mainly so you can test the review + calendar
// round-trips against TEST accounts without touching the live business.

return [

    // Public site URL, used to build track/admin links inside emails.
    'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),

    // Where new-booking alerts are sent. Falls back to the mail "from" address.
    'notify_email' => env('ADMIN_NOTIFY_EMAIL', env('MAIL_FROM_ADDRESS')),

    // Default booking schedule. These are only fallbacks — the live values are
    // stored in the `settings` table and editable from the admin "Schedule" page.
    'booking' => [
        'open_hour' => 10,   // studio opens (0–23)
        'close_hour' => 19,  // studio closes (1–24)
        'slot_interval_minutes' => 0, // 0 = space slots by each service's duration
    ],

    'google_calendar' => [
        // Master switch. Bookings only sync to Google Calendar when this is on
        // AND a calendar id + service-account key are configured.
        'enabled' => (bool) env('GOOGLE_CALENDAR_ENABLED', false),

        // The calendar to write events into. For testing use a dedicated/test
        // calendar's id (Calendar settings → "Integrate calendar" → Calendar ID),
        // shared with the service account email as "Make changes to events".
        'calendar_id' => env('GOOGLE_CALENDAR_ID'),

        // Absolute path to the service-account JSON key file downloaded from
        // Google Cloud (keep it OUTSIDE the web root / out of git).
        'credentials' => env('GOOGLE_SERVICE_ACCOUNT_KEY'),

        // Timezone the appointment wall-clock times are interpreted in.
        'timezone' => env('GOOGLE_CALENDAR_TIMEZONE', 'Asia/Manila'),

        // When true, events on the business calendar also block booking slots
        // (busy times become unavailable in the public availability calendar).
        'block_availability' => (bool) env('GOOGLE_CALENDAR_BLOCK_AVAILABILITY', true),
    ],

];
