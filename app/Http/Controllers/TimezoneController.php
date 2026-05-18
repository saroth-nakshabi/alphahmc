<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TimezoneController extends Controller
{
    public function setTimezone(Request $request)
    {
        // Get the timezone from the request and set it in a cookie
        $timezone = $request->input('timezone');

        // Set the cookie for 1 year
        cookie()->queue(cookie('userTimezone', $timezone, 60 * 24 * 7)); // 1 week in minutes

        return response()->json(['status' => 'Timezone set']);
    }
}
