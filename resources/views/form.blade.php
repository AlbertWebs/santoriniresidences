@extends('layouts.site')

@php($visit = cms()->page('visit'))

@section('title', $form->title.' | Santorini Residences')
@section('description', \Illuminate\Support\Str::limit((string) $form->intro, 155))

@section('content')
    @include('partials.lead-page', [
        'aside' => array_merge($visit['aside'], ['kicker' => 'Santorini Residences']),
        'intro' => [
            'kicker' => \App\Support\LeadFormTypes::label($form->type),
            'title' => $form->title,
            'title_accent' => null,
            'body' => $form->intro,
        ],
        'form' => $form,
    ])
@endsection
