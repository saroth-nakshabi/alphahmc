<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;

if (!function_exists('convertToUserTimezone')) {
    /**
     * Convert UTC datetime, date, or time to user's timezone
     *
     * @param string $utcValue The UTC datetime, date, or time string
     * @param string $format The desired output format (optional)
     * @return string
     */
    function convertToUserTimezone($utcValue, $format = null)
    {
        // Get the user's timezone from the cookie or default to UTC
        $userTimezone = request()->cookie('userTimezone') ?? 'UTC';

        // Detect if the input is date-only, time-only, or datetime
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $utcValue)) {
            // Handle date-only input (e.g., '2024-09-20')
            $utcDateTime = Carbon::createFromFormat('Y-m-d', $utcValue, 'UTC')
                ->setTimezone($userTimezone);
            // Default format for date-only, if none is provided
            $format = $format ?? 'Y-m-d';
        } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $utcValue)) {
            // Handle time-only input (e.g., '09:00:00'), append the current date
            $utcDateTime = Carbon::createFromFormat('Y-m-d H:i:s', now()->format('Y-m-d') . ' ' . $utcValue, 'UTC')
                ->setTimezone($userTimezone);
            // Default format for time-only, if none is provided
            $format = $format ?? 'h:i A (P)';
        } else {
            // Handle full datetime input (e.g., '2024-09-20 09:00:00')
            $utcDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $utcValue, 'UTC')
                ->setTimezone($userTimezone);
            // Default format for datetime, if none is provided
            $format = $format ?? 'Y-m-d H:i:s (P)';
        }

        // Add the timezone abbreviation (e.g., GMT, IST)
        // $format .= ' (P)'; // 'T' will output timezone abbreviation like 'GMT', 'IST'

        // Return the formatted date or time with the timezone abbreviation
        return $utcDateTime->format($format);
    }

    // function convertToUserTimezone($utcValue, $format = null)
    // {
    //     // Get the user's timezone from the cookie or default to UTC
    //     $userTimezone = request()->cookie('userTimezone') ?? 'UTC';

    //     // dd(request()->cookies->all());

    //     // Detect if the input is date-only, time-only, or datetime
    //     if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $utcValue)) {
    //         // Handle date-only input (e.g., '2024-09-20')
    //         $utcDateTime = Carbon::createFromFormat('Y-m-d', $utcValue, 'UTC')
    //             ->setTimezone($userTimezone);
    //         // Default format for date-only, if none is provided
    //         $format = $format ?? 'Y-m-d';
    //     } elseif (preg_match('/^\d{2}:\d{2}:\d{2}$/', $utcValue)) {
    //         // Handle time-only input (e.g., '09:00:00'), append the current date
    //         $utcDateTime = Carbon::createFromFormat('Y-m-d H:i:s', now()->format('Y-m-d') . ' ' . $utcValue, 'UTC')
    //             ->setTimezone($userTimezone);
    //         // Default format for time-only, if none is provided
    //         $format = $format ?? 'h:i A';
    //     } else {
    //         // Handle full datetime input (e.g., '2024-09-20 09:00:00')
    //         $utcDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $utcValue, 'UTC')
    //             ->setTimezone($userTimezone);
    //         // Default format for datetime, if none is provided
    //         $format = $format ?? 'Y-m-d H:i:s';
    //     }

    //     // Return the formatted date or time
    //     return $utcDateTime->format($format);
    // }
}