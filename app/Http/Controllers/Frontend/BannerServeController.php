<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerServeController extends Controller
{
    public function impression($id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            return response()->json(['success' => false], 404);
        }

        $banner->increment('impressions');

        return response()->json(['success' => true]);
    }

    public function click($id, Request $request)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            abort(404);
        }

        $banner->increment('clicks');

        $url = $banner->target_url ?: null;
        if (!$url) {
            return redirect()->back();
        }

        return redirect()->away($url);
    }
}

