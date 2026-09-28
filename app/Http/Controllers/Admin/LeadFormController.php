<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeadForm;
use App\Support\LeadFormTypes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LeadFormController extends Controller
{
    public const SYSTEM_FORMS = ['general', 'book-visit'];

    public function index(): View
    {
        return view('admin.forms.index', [
            'forms' => LeadForm::withCount(['leads', 'funnels'])->orderByRaw("case key when 'general' then 0 when 'book-visit' then 1 else 2 end")->orderBy('title')->get(),
            'types' => LeadFormTypes::types(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'type' => ['required', Rule::in(array_keys(LeadFormTypes::types()))],
            'title' => ['required', 'string', 'max:120'],
        ]);

        $preset = LeadFormTypes::types()[$data['type']];
        $key = Str::slug($data['title']) ?: $data['type'];
        $base = $key;
        $i = 2;
        while (LeadForm::where('key', $key)->exists() || in_array($key, ['create'], true)) {
            $key = $base.'-'.$i++;
        }

        $form = LeadForm::create([
            'key' => $key,
            'type' => $data['type'],
            'title' => $data['title'],
            'intro' => $preset['default_intro'],
            'button_label' => $preset['default_button'],
            'success_title' => 'Thank you.',
            'success_message' => 'Your request has been received. A member of the Santorini team will be in touch personally.',
            'fields' => LeadFormTypes::defaultSettings($data['type']),
            'is_active' => true,
        ]);

        return redirect()->route('admin.forms.edit', $form)->with('status', 'Form created from the '.$preset['label'].' template.');
    }

    public function edit(LeadForm $form): View
    {
        $form->loadCount(['leads', 'funnels']);

        return view('admin.forms.edit', [
            'form' => $form,
            'preset' => LeadFormTypes::fields($form->type),
            'settings' => array_replace(LeadFormTypes::defaultSettings($form->type), $form->fields ?? []),
            'typeLabel' => LeadFormTypes::label($form->type),
            'system' => in_array($form->key, self::SYSTEM_FORMS, true),
            'funnels' => $form->funnels()->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, LeadForm $form): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'intro' => ['nullable', 'string', 'max:600'],
            'button_label' => ['required', 'string', 'max:60'],
            'success_title' => ['required', 'string', 'max:80'],
            'success_message' => ['required', 'string', 'max:600'],
            'is_active' => ['nullable', 'boolean'],
            'fields' => ['array'],
            'fields.*.enabled' => ['nullable', 'boolean'],
            'fields.*.required' => ['nullable', 'boolean'],
            'fields.*.label' => ['nullable', 'string', 'max:80'],
        ]);

        $settings = [];
        foreach (LeadFormTypes::fields($form->type) as $name => $definition) {
            $input = $data['fields'][$name] ?? [];
            $locked = $definition['locked'] ?? false;
            $label = trim((string) ($input['label'] ?? ''));

            $settings[$name] = array_filter([
                'enabled' => $locked || ! empty($input['enabled']),
                'required' => $locked ? (bool) ($definition['required'] ?? false) : ! empty($input['required']),
                'label' => $label !== '' && $label !== $definition['label'] ? Str::limit($label, 80, '') : null,
            ], fn ($value) => $value !== null);
        }

        $form->update([
            'title' => $data['title'],
            'intro' => $data['intro'] ?? '',
            'button_label' => $data['button_label'],
            'success_title' => $data['success_title'],
            'success_message' => $data['success_message'],
            'is_active' => in_array($form->key, self::SYSTEM_FORMS, true) ? true : $request->boolean('is_active'),
            'fields' => $settings,
        ]);

        return redirect()->route('admin.forms.edit', $form)->with('status', 'Form saved. The changes are live.');
    }

    public function destroy(LeadForm $form): RedirectResponse
    {
        if (in_array($form->key, self::SYSTEM_FORMS, true)) {
            return back()->with('error', 'The enquiry and site visit forms power core pages and cannot be deleted.');
        }

        $form->delete();

        return redirect()->route('admin.forms.index')->with('status', 'Form deleted. Its leads have been kept.');
    }
}
