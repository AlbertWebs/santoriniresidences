<?php

namespace App\Http\Controllers;

use App\Models\Funnel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FunnelController extends Controller
{
    public function show(Request $request, string $slug): View
    {
        $funnel = Funnel::with(['form', 'media'])->where('slug', $slug)->where('is_active', true)->firstOrFail();
        abort_unless($funnel->form?->is_active, 404);

        if (! $request->session()->has('lead_submitted')) {
            $funnel->increment('visits');
        }

        $request->session()->put('lead_attribution', array_filter([
            'funnel_id' => $funnel->id,
            'utm_source' => Str::limit((string) $request->query('utm_source', ''), 120, ''),
            'utm_medium' => Str::limit((string) $request->query('utm_medium', ''), 120, ''),
            'utm_campaign' => Str::limit((string) $request->query('utm_campaign', ''), 120, ''),
            'referrer' => Str::limit((string) $request->headers->get('referer', ''), 500, ''),
        ]));

        return view('funnel', ['funnel' => $funnel, 'form' => $funnel->form]);
    }
}
