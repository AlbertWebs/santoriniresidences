@extends('layouts.site')

@php
    $visit = cms()->page('visit');
    $image = $funnel->media?->path ?: $visit['aside']['image'];
    $headline = $funnel->headline ?: $form->title;
@endphp

@section('title', $headline.' | Santorini Residences')
@section('description', \Illuminate\Support\Str::limit($funnel->subline ?: (string) $form->intro, 155))
@section('og_image', cms_asset($image))

@push('head')
    <meta name="robots" content="noindex, follow">
@endpush

@section('content')
    @include('partials.lead-page', [
        'aside' => array_merge($visit['aside'], [
            'image' => $image,
            'image_alt' => $funnel->media?->alt ?: $visit['aside']['image_alt'],
            'kicker' => 'Santorini Residences',
        ]),
        'intro' => [
            'kicker' => $form->title,
            'title' => $headline,
            'title_accent' => $funnel->headline ? $funnel->headline_accent : null,
            'body' => $funnel->subline ?: $form->intro,
        ],
        'form' => $form,
        'funnel' => $funnel,
    ])
@endsection
