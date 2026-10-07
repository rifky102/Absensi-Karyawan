<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Show settings form
     */
    public function index()
    {
        $favicon = Setting::get('favicon');
        $logo = Setting::get('logo');
        $login_background = Setting::get('login_background');

        return view('settings.index', compact('favicon', 'logo', 'login_background'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'favicon' => 'nullable|image|mimes:jpeg,png,jpg,gif,ico|max:2048',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'login_background' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->hasFile('favicon')) {
            $faviconPath = $request->file('favicon')->store('settings/favicon', 'public');
            Setting::set('favicon', $faviconPath);
        }

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings/logo', 'public');
            Setting::set('logo', $logoPath);
        }

        if ($request->hasFile('login_background')) {
            $bgPath = $request->file('login_background')->store('settings/login_bg', 'public');
            Setting::set('login_background', $bgPath);
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }

    /**
     * Delete a setting file
     */
    public function deleteFavicon()
    {
        Setting::where('key', 'favicon')->delete();
        return redirect()->route('settings.index')->with('success', 'Favicon berhasil dihapus!');
    }

    public function deleteLogo()
    {
        Setting::where('key', 'logo')->delete();
        return redirect()->route('settings.index')->with('success', 'Logo berhasil dihapus!');
    }

    public function deleteBackground()
    {
        Setting::where('key', 'login_background')->delete();
        return redirect()->route('settings.index')->with('success', 'Background berhasil dihapus!');
    }
}

