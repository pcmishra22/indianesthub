<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $groups   = [
            'general'    => ['icon' => 'settings',       'label' => 'General'],
            'contact'    => ['icon' => 'phone',           'label' => 'Contact & Support'],
            'social'     => ['icon' => 'share-2',         'label' => 'Social Media'],
            'seo'        => ['icon' => 'search',          'label' => 'SEO & Meta'],
            'property'   => ['icon' => 'home',            'label' => 'Property Display'],
            'appearance' => ['icon' => 'layout',          'label' => 'Appearance & Branding'],
        ];

        return view('backend.settings.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $input = $request->except(['_token', '_method']);

        foreach ($input as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Handle boolean checkboxes — unchecked = not sent, so set to 0
        $booleanKeys = Setting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $bKey) {
            if (!array_key_exists($bKey, $input)) {
                Setting::where('key', $bKey)->update(['value' => '0']);
            }
        }

        return redirect()->route('admin.settings.index')
                         ->with('success', '✅ Settings saved successfully.');
    }
}
