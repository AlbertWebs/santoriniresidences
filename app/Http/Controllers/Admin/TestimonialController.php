<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\TestimonialContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        return view('admin.cms.testimonials.index', ['items' => TestimonialContent::all()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'items' => ['nullable', 'array', 'max:50'],
            'items.*.id' => ['required', 'uuid'],
            'items.*.type' => ['required', 'in:Testimonial,Press Mention,Success Story'],
            'items.*.name' => ['required', 'string', 'max:160'],
            'items.*.headline' => ['nullable', 'string', 'max:200'],
            'items.*.content' => ['required', 'string', 'max:3000'],
            'items.*.is_public' => ['sometimes', 'boolean'],
        ]);

        $items = collect($data['items'] ?? [])->map(function (array $item, int|string $index) use ($request) {
            return [
                'id' => $item['id'],
                'type' => $item['type'],
                'name' => trim($item['name']),
                'headline' => trim($item['headline'] ?? ''),
                'content' => trim($item['content']),
                'is_public' => $request->boolean('items.'.$index.'.is_public'),
            ];
        })->values()->all();

        TestimonialContent::save($items);

        return redirect()->route('admin.cms.testimonials.index')->with('status', 'Testimonials saved.');
    }
}
