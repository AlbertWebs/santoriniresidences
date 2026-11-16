<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\AdminSettings;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(): View
    {
        return view('admin.settings.index', [
            'settings' => AdminSettings::all(),
            'currencies' => AdminSettings::CURRENCIES,
            'timezones' => AdminSettings::TIMEZONES,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'in:'.implode(',', AdminSettings::CURRENCIES)],
            'timezone' => ['required', 'string', 'in:'.implode(',', AdminSettings::TIMEZONES)],
            'notify_new_inquiries' => ['sometimes', 'boolean'],
            'notify_visit_confirmations' => ['sometimes', 'boolean'],
            'daily_summary' => ['sometimes', 'boolean'],
        ]);

        $settings = [
            'currency' => $validated['currency'],
            'timezone' => $validated['timezone'],
            'notify_new_inquiries' => $request->boolean('notify_new_inquiries'),
            'notify_visit_confirmations' => $request->boolean('notify_visit_confirmations'),
            'daily_summary' => $request->boolean('daily_summary'),
        ];

        AdminSettings::save($settings);
        config(['site.timezone' => $settings['timezone']]);

        return redirect()->route('admin.settings.index')->with('status', 'Settings saved.');
    }
}
