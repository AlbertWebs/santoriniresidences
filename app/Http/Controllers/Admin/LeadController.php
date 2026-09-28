<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Funnel;
use App\Models\Lead;
use App\Support\LeadFormTypes;
use App\Support\SiteContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public const VISIT_TYPES = ['book-visit', 'schedule-visit'];

    public function index(Request $request): View
    {
        $leads = $this->filtered($request)->with(['funnel', 'form'])->latest()->paginate(25)->withQueryString();

        return view('admin.leads.index', [
            'leads' => $leads,
            'filters' => $request->only(['status', 'type', 'funnel', 'q']),
            'statusCounts' => Lead::query()->selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'stats' => [
                'total' => Lead::count(),
                'new' => Lead::where('status', 'new')->count(),
                'week' => Lead::where('created_at', '>=', now()->subDays(7))->count(),
                'funnel' => Lead::whereNotNull('funnel_id')->count(),
            ],
            'funnels' => Funnel::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function visits(): View
    {
        $base = Lead::whereIn('form_type', self::VISIT_TYPES)->with('funnel');

        return view('admin.leads.visits', [
            'upcoming' => (clone $base)->whereDate('preferred_date', '>=', today())->whereNotIn('status', ['lost'])->orderBy('preferred_date')->get()
                ->groupBy(fn (Lead $lead) => $lead->preferred_date->toDateString()),
            'undated' => (clone $base)->whereNull('preferred_date')->whereIn('status', ['new', 'contacted'])->latest()->get(),
            'past' => (clone $base)->whereDate('preferred_date', '<', today())->orderByDesc('preferred_date')->limit(20)->get(),
            'stats' => [
                'requested' => (clone $base)->where('status', 'new')->count(),
                'scheduled' => (clone $base)->where('status', 'visit_scheduled')->count(),
                'week' => (clone $base)->whereBetween('preferred_date', [today(), today()->addDays(7)])->count(),
            ],
        ]);
    }

    public function show(Lead $lead): View
    {
        $lead->load(['funnel', 'form', 'documents' => fn ($q) => $q->latest('document_lead.created_at')]);

        return view('admin.leads.show', [
            'lead' => $lead,
            'details' => $this->details($lead),
            'vault' => Document::whereNotIn('id', $lead->documents->modelKeys())->latest()->limit(200)->get(['id', 'title', 'category']),
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $term = '%'.str_replace(['%', '_'], ['\%', '\_'], (string) $request->query('q', '')).'%';

        $leads = Lead::query()
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($inner) => $inner
                ->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term)))
            ->orderByRaw("case status when 'won' then 0 when 'visit_scheduled' then 1 when 'contacted' then 2 else 3 end")
            ->latest()
            ->limit(8)
            ->get(['id', 'name', 'email', 'status']);

        return response()->json(['data' => $leads->map(fn (Lead $lead) => [
            'id' => $lead->id,
            'name' => $lead->name,
            'email' => $lead->email,
            'status' => $lead->status,
            'status_label' => $lead->statusLabel(),
        ])]);
    }

    public function update(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', array_keys(Lead::STATUSES))],
            'notes' => ['nullable', 'string', 'max:5000'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'in:'.implode(',', LeadFormTypes::TIMES)],
        ]);

        $lead->update($data);

        return redirect()->route('admin.leads.show', $lead)->with('status', 'Lead updated.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()->route('admin.leads.index')->with('status', 'Lead deleted.');
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->filtered($request)->with(['funnel', 'form'])->latest();
        $filename = 'santorini-leads-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Received', 'Status', 'Form', 'Name', 'Email', 'Telephone', 'Interest', 'Residence', 'Preferred date', 'Preferred time', 'Guests', 'Preferred channel', 'Message', 'Funnel', 'UTM source', 'UTM medium', 'UTM campaign', 'Referrer', 'Notes']);

            $query->chunk(200, function ($leads) use ($out) {
                foreach ($leads as $lead) {
                    fputcsv($out, array_map(fn ($value) => $this->csvSafe($value), [
                        $lead->created_at->format('Y-m-d H:i'),
                        $lead->statusLabel(),
                        LeadFormTypes::label($lead->form_type),
                        $lead->name,
                        $lead->email,
                        $lead->phone,
                        SiteContent::interests()[$lead->interest] ?? $lead->interest,
                        LeadFormTypes::RESIDENCES[$lead->residence_type] ?? $lead->residence_type,
                        $lead->preferred_date?->format('Y-m-d'),
                        $lead->preferred_time,
                        $lead->guests,
                        LeadFormTypes::CHANNELS[$lead->contact_channel] ?? $lead->contact_channel,
                        $lead->message,
                        $lead->funnel?->name,
                        $lead->utm_source,
                        $lead->utm_medium,
                        $lead->utm_campaign,
                        $lead->referrer,
                        $lead->notes,
                    ]));
                }
            });

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /**
     * @return array<string, string>
     */
    private function details(Lead $lead): array
    {
        return array_filter([
            'Telephone' => $lead->phone,
            'Interest' => SiteContent::interests()[$lead->interest] ?? $lead->interest,
            'Residence' => LeadFormTypes::RESIDENCES[$lead->residence_type] ?? $lead->residence_type,
            'Preferred date' => $lead->preferred_date?->format('l j F Y'),
            'Preferred time' => $lead->preferred_time,
            'Guests' => $lead->guests,
            'Visit type' => match ($lead->visit_type) {
                'in-person' => 'In person, on site',
                'virtual' => 'Virtual visit or call',
                default => null,
            },
            'Preferred channel' => LeadFormTypes::CHANNELS[$lead->contact_channel] ?? $lead->contact_channel,
        ], fn ($value) => filled($value));
    }

    private function filtered(Request $request): Builder
    {
        return Lead::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('type'), fn ($q) => $q->where('form_type', $request->input('type')))
            ->when($request->input('funnel') === 'website', fn ($q) => $q->whereNull('funnel_id'))
            ->when(is_numeric($request->input('funnel')), fn ($q) => $q->where('funnel_id', $request->input('funnel')))
            ->when($request->filled('q'), function ($q) use ($request) {
                $term = '%'.str_replace(['%', '_'], ['\%', '\_'], $request->input('q')).'%';
                $q->where(fn ($inner) => $inner->where('name', 'like', $term)->orWhere('email', 'like', $term)->orWhere('phone', 'like', $term));
            });
    }

    private function csvSafe(mixed $value): string
    {
        $value = (string) $value;

        return preg_match('/^[=+\-@\t\r]/', $value) ? "'".$value : $value;
    }
}
