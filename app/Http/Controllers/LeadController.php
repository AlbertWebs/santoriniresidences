<?php

namespace App\Http\Controllers;

use App\Models\Funnel;
use App\Models\Lead;
use App\Models\LeadForm;
use App\Support\LeadFormTypes;
use App\Support\SiteContent;
use App\Mail\LeadAcknowledgement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function enquire(Request $request): View
    {
        $interest = (string) $request->query('interest', 'private-viewing');

        return view('enquire', [
            'form' => $this->form('general'),
            'preset' => ['interest' => array_key_exists($interest, SiteContent::interests()) ? $interest : 'private-viewing'],
        ]);
    }

    public function bookVisit(Request $request): View
    {
        $residence = (string) $request->query('residence', '');

        return view('visit', [
            'form' => $this->form('book-visit'),
            'preset' => array_filter(['residence_type' => array_key_exists($residence, LeadFormTypes::RESIDENCES) ? $residence : null]),
        ]);
    }

    public function show(LeadForm $form): View|RedirectResponse
    {
        abort_unless($form->is_active, 404);

        return match ($form->key) {
            'general' => redirect()->route('enquire'),
            'book-visit' => redirect()->route('visit.book'),
            default => view('form', ['form' => $form]),
        };
    }

    public function storeGeneral(Request $request): RedirectResponse
    {
        return $this->store($request, $this->form('general'));
    }

    public function store(Request $request, LeadForm $form): RedirectResponse
    {
        abort_unless($form->is_active, 404);

        if (filled($request->input('company'))) {
            return $this->received($form);
        }

        $fields = $form->activeFields();
        $rules = [];
        $attributes = [];

        foreach ($fields as $name => $field) {
            $rules[$name] = array_merge([$field['required'] ? 'required' : 'nullable'], $field['rules']);
            $attributes[$name] = Str::lower($field['label']);
        }

        $data = $request->validate($rules, [], $attributes);

        $attribution = $request->session()->get('lead_attribution', []);
        $funnel = $request->filled('funnel')
            ? Funnel::where('slug', $request->input('funnel'))->first()
            : (isset($attribution['funnel_id']) ? Funnel::find($attribution['funnel_id']) : null);

        $utm = fn (string $key) => Str::limit((string) ($request->input($key) ?: ($attribution[$key] ?? '')), 120, '') ?: null;

        $lead = Lead::create(array_merge(array_fill_keys(array_keys($fields), null), $data, [
            'form_type' => $form->type,
            'lead_form_id' => $form->id,
            'funnel_id' => $funnel?->id,
            'visit_type' => match ($form->type) {
                'book-visit' => 'in-person',
                'schedule-visit' => 'virtual',
                default => null,
            },
            'source' => $funnel ? 'funnel' : 'website',
            'utm_source' => $utm('utm_source') ?? $funnel?->utm_source ?? $funnel?->channel,
            'utm_medium' => $utm('utm_medium') ?? ($funnel ? ($funnel->utm_medium ?: 'social') : null),
            'utm_campaign' => $utm('utm_campaign') ?? ($funnel ? ($funnel->utm_campaign ?: $funnel->slug) : null),
            'referrer' => Str::limit((string) ($attribution['referrer'] ?? $request->headers->get('referer', '')), 500, '') ?: null,
            'status' => 'new',
            'meta' => array_filter([
                'page' => Str::limit((string) $request->input('page', ''), 300, ''),
                'ip' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 300, ''),
            ]),
        ]));

        try {
            Mail::to($lead->email)->send(new LeadAcknowledgement($lead));
        } catch (\Throwable $exception) {
            Log::error('Lead acknowledgement email could not be sent.', [
                'lead_id' => $lead->id,
                'exception' => $exception::class,
            ]);
        }

        return $this->received($form);
    }

    private function received(LeadForm $form): RedirectResponse
    {
        return redirect()->to(url()->previous().'#lead-'.$form->key)->with('lead_submitted', $form->key);
    }

    private function form(string $key): LeadForm
    {
        return LeadForm::where('key', $key)->firstOr(function () use ($key) {
            $preset = LeadFormTypes::types()[$key];

            return LeadForm::create([
                'key' => $key,
                'type' => $key,
                'title' => $preset['default_title'],
                'intro' => $preset['default_intro'],
                'button_label' => $preset['default_button'],
                'success_title' => 'Thank you.',
                'success_message' => 'Your request has been received. A member of the Santorini team will be in touch personally.',
                'fields' => LeadFormTypes::defaultSettings($key),
                'is_active' => true,
            ]);
        });
    }
}
