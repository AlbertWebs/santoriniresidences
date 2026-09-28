<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Funnel;
use App\Models\LeadForm;
use App\Models\Media;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FunnelController extends Controller
{
    public function index(): View
    {
        $funnels = Funnel::with('form')->withCount('leads')->orderByDesc('is_active')->orderBy('name')->get();

        return view('admin.funnels.index', [
            'funnels' => $funnels,
            'totals' => [
                'visits' => $funnels->sum('visits'),
                'leads' => $funnels->sum('leads_count'),
                'live' => $funnels->where('is_active', true)->count(),
            ],
            'byChannel' => $funnels->groupBy('channel')->map(fn ($group) => [
                'visits' => $group->sum('visits'),
                'leads' => $group->sum('leads_count'),
            ]),
        ]);
    }

    public function create(): View
    {
        return view('admin.funnels.form', [
            'funnel' => new Funnel(['channel' => 'instagram', 'is_active' => true, 'utm_medium' => 'social']),
            'forms' => LeadForm::where('is_active', true)->orderBy('title')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $funnel = Funnel::create($this->validated($request));

        return redirect()->route('admin.funnels.edit', $funnel)->with('status', 'Funnel created. Copy its link into your social profile or campaign.');
    }

    public function edit(Funnel $funnel): View
    {
        $funnel->load(['form', 'media'])->loadCount('leads');

        return view('admin.funnels.form', [
            'funnel' => $funnel,
            'forms' => LeadForm::where('is_active', true)->orWhere('id', $funnel->lead_form_id)->orderBy('title')->get(),
            'recentLeads' => $funnel->leads()->latest()->limit(6)->get(),
        ]);
    }

    public function update(Request $request, Funnel $funnel): RedirectResponse
    {
        $funnel->update($this->validated($request, $funnel));

        return redirect()->route('admin.funnels.edit', $funnel)->with('status', 'Funnel saved.');
    }

    public function destroy(Funnel $funnel): RedirectResponse
    {
        $funnel->delete();

        return redirect()->route('admin.funnels.index')->with('status', 'Funnel deleted. Its leads have been kept.');
    }

    private function validated(Request $request, ?Funnel $funnel = null): array
    {
        $request->merge(['slug' => Str::slug((string) ($request->input('slug') ?: $request->input('name')))]);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'slug' => ['required', 'string', 'max:80', 'alpha_dash', Rule::unique('funnels', 'slug')->ignore($funnel?->id)],
            'channel' => ['required', Rule::in(array_keys(Funnel::CHANNELS))],
            'lead_form_id' => ['required', 'exists:lead_forms,id'],
            'headline' => ['nullable', 'string', 'max:120'],
            'headline_accent' => ['nullable', 'string', 'max:80'],
            'subline' => ['nullable', 'string', 'max:300'],
            'media_path' => ['nullable', 'string', 'max:500'],
            'utm_source' => ['nullable', 'string', 'max:80'],
            'utm_medium' => ['nullable', 'string', 'max:80'],
            'utm_campaign' => ['nullable', 'string', 'max:80'],
        ]);

        $data['media_id'] = filled($data['media_path'] ?? null) ? Media::where('path', $data['media_path'])->value('id') : null;
        $data['is_active'] = $request->boolean('is_active');
        unset($data['media_path']);

        return $data;
    }
}
