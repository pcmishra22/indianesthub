<?php

namespace App\Http\Controllers;

use App\Models\EmailTracking;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class EmailTrackingController extends Controller
{
    /**
     * Serve a 1×1 transparent tracking pixel and log the open event.
     */
    public function pixel(Request $request, string $token): Response
    {
        $tracking = EmailTracking::where('token', $token)->first();

        if ($tracking) {
            $tracking->open_count++;

            if (is_null($tracking->first_opened_at)) {
                $tracking->first_opened_at = now();
            }

            $tracking->last_ip    = $request->ip();
            $tracking->user_agent = $request->userAgent();
            $tracking->save();
        }

        // 1×1 transparent GIF — the smallest valid GIF
        $pixel = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return response($pixel, 200, [
            'Content-Type'  => 'image/gif',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma'        => 'no-cache',
            'Expires'       => '0',
        ]);
    }
}
