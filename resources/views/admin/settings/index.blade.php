<x-layouts.admin title="Settings">
    <div class="space-y-6">
        <section class="admin-card p-5">
            <h2 class="text-lg font-semibold text-neutral-900">Platform Settings</h2>
            <p class="mt-1 text-sm text-neutral-500">Set the default currency, dashboard timezone, and notification preferences.</p>
        </section>

        <section class="admin-card p-5">
            <form action="{{ route('admin.settings.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="currency" class="admin-label">Default Currency</label>
                        <select id="currency" name="currency" class="admin-input">
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency }}" @selected(old('currency', $settings['currency']) === $currency)>{{ $currency === 'KES' ? 'KES — Kenyan Shilling' : $currency }}</option>
                            @endforeach
                        </select>
                        @error('currency')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="timezone" class="admin-label">Default Timezone</label>
                        <select id="timezone" name="timezone" class="admin-input">
                            @foreach ($timezones as $timezone)
                                <option value="{{ $timezone }}" @selected(old('timezone', $settings['timezone']) === $timezone)>{{ $timezone }}</option>
                            @endforeach
                        </select>
                        @error('timezone')<p class="mt-1 text-sm text-red-700">{{ $message }}</p>@enderror
                    </div>
                </div>

                <fieldset class="space-y-3">
                    <legend class="admin-label">Notification Preferences</legend>
                    @foreach ([
                        'notify_new_inquiries' => 'Email alerts for new inquiries',
                        'notify_visit_confirmations' => 'Alerts for site visit confirmations',
                        'daily_summary' => 'Daily summary report',
                    ] as $field => $label)
                        <label for="{{ $field }}" class="flex items-center gap-2 text-sm text-neutral-700">
                            <input id="{{ $field }}" name="{{ $field }}" type="checkbox" value="1" class="rounded border-neutral-300" @checked((bool) old($field, $settings[$field]))>
                            {{ $label }}
                        </label>
                    @endforeach
                </fieldset>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.settings.backup') }}" class="btn-secondary">Open Data Backup</a>
                    <button type="submit" class="btn-primary">Save Settings</button>
                </div>
            </form>
        </section>
    </div>
</x-layouts.admin>
