@extends('layouts.site')

@php($page = cms()->page('visit'))

@section('title', $page['meta']['title'])
@section('description', $page['meta']['description'])

@section('content')
    @include('partials.lead-page', [
        'aside' => $page['aside'],
        'intro' => $page['intro'],
        'form' => $form,
        'preset' => $preset,
    ])
@endsection
