<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Lead;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'category' => ['nullable', 'string', 'in:'.implode(',', array_keys(Document::CATEGORIES))],
            'q' => ['nullable', 'string', 'max:120'],
            'linked' => ['nullable', 'string', 'in:linked,unlinked'],
        ]);

        $documents = Document::query()
            ->with('leads:id,name,email,status')
            ->when($filters['category'] ?? null, fn ($q, $category) => $q->where('category', $category))
            ->when(($filters['linked'] ?? null) === 'linked', fn ($q) => $q->has('leads'))
            ->when(($filters['linked'] ?? null) === 'unlinked', fn ($q) => $q->doesntHave('leads'))
            ->when(filled($filters['q'] ?? null), function ($q) use ($filters) {
                $term = '%'.str_replace(['%', '_'], ['\%', '\_'], $filters['q']).'%';
                $q->where(fn ($inner) => $inner
                    ->where('title', 'like', $term)
                    ->orWhere('original_name', 'like', $term)
                    ->orWhereHas('leads', fn ($lead) => $lead->where('name', 'like', $term)->orWhere('email', 'like', $term)));
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.legal.documents', [
            'documents' => $documents,
            'filters' => $filters,
            'categoryCounts' => Document::query()->selectRaw('category, count(*) as total')->groupBy('category')->pluck('total', 'category'),
            'stats' => [
                'total' => Document::count(),
                'customers' => DB::table('document_lead')->distinct()->count('lead_id'),
                'unlinked' => Document::doesntHave('leads')->count(),
                'size' => (int) Document::sum('size'),
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->rules(fileRequired: true));

        $document = Document::create(array_merge($this->storeFile($request->file('file')), [
            'title' => ($data['title'] ?? null) ?: $this->titleFrom($request->file('file')),
            'category' => $data['category'],
            'notes' => $data['notes'] ?? null,
            'uploaded_by' => $request->user()?->id,
        ]));
        $document->leads()->sync($data['lead_ids'] ?? []);

        return back()->with('status', '"'.$document->title.'" added to the vault.');
    }

    public function show(Document $document): View
    {
        $document->load(['leads', 'uploader']);

        return view('admin.documents.show', ['document' => $document]);
    }

    public function update(Request $request, Document $document): RedirectResponse
    {
        $data = $request->validate($this->rules(fileRequired: false));

        $attributes = [
            'title' => ($data['title'] ?? null) ?: $document->title,
            'category' => $data['category'],
            'notes' => $data['notes'] ?? null,
        ];

        if ($request->hasFile('file')) {
            $previous = $document->path;
            $attributes = array_merge($attributes, $this->storeFile($request->file('file')));
        }

        $document->update($attributes);
        $document->leads()->sync($data['lead_ids'] ?? []);

        if (isset($previous)) {
            Storage::disk('local')->delete($previous);
        }

        return redirect()->route('admin.documents.show', $document)->with('status', 'Document saved.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        Storage::disk('local')->delete($document->path);
        $document->delete();

        return redirect()->route('admin.legal.documents')->with('status', 'Document deleted.');
    }

    public function download(Request $request, Document $document): StreamedResponse
    {
        return $this->stream($document, $request->boolean('preview') && $document->previewable());
    }

    public function attach(Request $request, Lead $lead): RedirectResponse
    {
        $data = $request->validate(['document_id' => ['required', 'integer', 'exists:documents,id']]);
        $lead->documents()->syncWithoutDetaching([$data['document_id']]);

        return back()->with('status', 'Document linked to '.$lead->name.'.');
    }

    public function detach(Lead $lead, Document $document): RedirectResponse
    {
        $lead->documents()->detach($document->id);

        return back()->with('status', 'Document unlinked from '.$lead->name.'.');
    }

    /**
     * Opened by the customer from a signed link; the route is protected by the `signed` middleware.
     */
    public function shared(Document $document, Lead $lead): StreamedResponse
    {
        abort_unless($document->leads()->whereKey($lead->id)->exists(), 404);

        $document->leads()->updateExistingPivot($lead->id, [
            'downloads' => DB::raw('downloads + 1'),
            'last_downloaded_at' => now(),
        ]);

        return $this->stream($document, $document->previewable());
    }

    private function stream(Document $document, bool $inline): StreamedResponse
    {
        abort_unless(Storage::disk('local')->exists($document->path), 404);

        return Storage::disk('local')->response(
            $document->path,
            $document->original_name,
            ['Content-Type' => $document->mime, 'X-Content-Type-Options' => 'nosniff', 'Cache-Control' => 'private, no-store'],
            $inline ? 'inline' : 'attachment',
        );
    }

    private function rules(bool $fileRequired): array
    {
        return [
            'file' => [$fileRequired ? 'required' : 'nullable', 'file', 'max:'.Document::MAX_KILOBYTES,
                'extensions:'.implode(',', Document::EXTENSIONS), 'mimes:'.implode(',', Document::EXTENSIONS)],
            'title' => ['nullable', 'string', 'max:160'],
            'category' => ['required', 'in:'.implode(',', array_keys(Document::CATEGORIES))],
            'notes' => ['nullable', 'string', 'max:2000'],
            'lead_ids' => ['nullable', 'array', 'max:50'],
            'lead_ids.*' => ['integer', 'distinct', 'exists:leads,id'],
        ];
    }

    /**
     * @return array{path: string, original_name: string, mime: string, size: int}
     */
    private function storeFile(UploadedFile $file): array
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return [
            'path' => $file->storeAs(Document::DIRECTORY, Str::random(40).'.'.$extension, 'local'),
            'original_name' => Str::limit($file->getClientOriginalName(), 200, ''),
            'mime' => $file->getMimeType() ?: 'application/octet-stream',
            'size' => $file->getSize(),
        ];
    }

    private function titleFrom(UploadedFile $file): string
    {
        return Str::of(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
            ->replace(['_', '-'], ' ')
            ->squish()
            ->limit(160, '')
            ->ucfirst()
            ->toString() ?: 'Untitled document';
    }
}
