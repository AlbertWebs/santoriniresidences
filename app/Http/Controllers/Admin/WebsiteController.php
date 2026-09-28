<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentBlock;
use App\Support\ContentSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebsiteController extends Controller
{
    public function index(): View
    {
        $blocks = ContentBlock::query()->get(['key', 'updated_at']);

        $pages = collect(ContentSchema::pages())->map(function (array $page, string $key) use ($blocks) {
            $saved = $blocks->filter(fn ($block) => str_starts_with($block->key, $key.'.'));

            return [
                'key' => $key,
                'label' => $page['label'],
                'description' => $page['description'],
                'url' => $page['route'] ? route($page['route']) : null,
                'sections' => count($page['sections']),
                'customised' => $saved->count(),
                'updated_at' => $saved->max('updated_at'),
            ];
        });

        return view('admin.website.index', ['pages' => $pages]);
    }

    public function edit(string $page): View
    {
        $schema = ContentSchema::page($page) ?? abort(404);
        $sections = $schema['sections'];

        if (isset($sections['meta'])) {
            $sections = array_merge(array_diff_key($sections, ['meta' => true]), ['meta' => $sections['meta']]);
        }

        return view('admin.website.edit', [
            'pageKey' => $page,
            'schema' => $schema,
            'sections' => $sections,
            'values' => cms()->page($page),
            'customised' => cms()->customisedSections($page),
            'siteUrl' => $schema['route'] ? route($schema['route']) : url('/'),
        ]);
    }

    public function update(Request $request, string $page): RedirectResponse
    {
        $schema = ContentSchema::page($page) ?? abort(404);

        if ($section = $request->input('reset')) {
            abort_unless(isset($schema['sections'][$section]), 404);
            cms()->resetSection($page, $section);

            return redirect()
                ->route('admin.website.edit', $page)
                ->with('status', $schema['sections'][$section]['label'].' has been restored to the original copy.');
        }

        $request->validate(['content' => ['required', 'array']]);
        cms()->save($page, $request->input('content'));

        return redirect()
            ->route('admin.website.edit', $page)
            ->with('status', $schema['label'].' saved. The changes are live on the website.');
    }
}
