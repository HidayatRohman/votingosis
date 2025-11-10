<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandingController extends Controller
{
    /**
     * Update the application logo.
     */
    public function updateLogo(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'logo' => ['required', 'image', 'mimes:png,jpg,jpeg,svg,webp', 'max:2048'],
        ]);

        // Delete the previous logo file if exists
        $previous = Setting::get('app_logo');
        if ($previous && Storage::disk('public')->exists($previous)) {
            Storage::disk('public')->delete($previous);
        }

        // Store new logo under public disk
        $path = $request->file('logo')->store('branding', 'public');

        // Save path to settings
        Setting::set('app_logo', $path);

        return to_route('profile.edit')->with('status', 'logo-updated');
    }

    /**
     * Update the application favicon.
     */
    public function updateFavicon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Favicon dapat berupa .ico, .png, .svg, .webp
            'favicon' => ['required', 'mimes:ico,png,svg,webp', 'max:2048'],
        ]);

        // Hapus favicon sebelumnya jika ada
        $previous = Setting::get('app_favicon');
        if ($previous && Storage::disk('public')->exists($previous)) {
            Storage::disk('public')->delete($previous);
        }

        // Simpan favicon baru di public disk
        $path = $request->file('favicon')->store('branding', 'public');

        // Simpan path ke settings
        Setting::set('app_favicon', $path);

        return to_route('favicon.edit')->with('status', 'favicon-updated');
    }
}