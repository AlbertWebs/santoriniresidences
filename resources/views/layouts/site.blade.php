<!DOCTYPE html>
@php($seo = cms('settings.seo'))
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', $seo['title'])</title>
    <meta name="description" content="@yield('description', $seo['description'])">
    <meta name="theme-color" content="#161311">
    <meta name="robots" content="{{ \App\Support\Seo::robots() }}">
    <meta property="og:site_name" content="Santorini Residences">
    <meta property="og:locale" content="en_KE">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('title', $seo['title'])">
    <meta name="twitter:description" content="@yield('description', $seo['description'])">
    <meta name="twitter:image" content="@yield('og_image', cms_asset($seo['og_image']))">
    <link rel="canonical" href="@yield('canonical', \App\Support\Seo::url(request()->path()))">
    <meta property="og:title" content="@yield('title', $seo['title'])">
    <meta property="og:description" content="@yield('description', $seo['description'])">
    <meta property="og:type" content="website">
    <meta property="og:url" content="@yield('canonical', \App\Support\Seo::url(request()->path()))">
    <meta property="og:image" content="@yield('og_image', cms_asset($seo['og_image']))">
    @include('partials.favicons')
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600|outfit:300,400,500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
@php($navLocked = request()->routeIs('gallery', 'enquire', 'visit.book', 'funnel.show', 'forms.show'))
<body id="top" class="site antialiased" x-data="{ open: false, solid: {{ $navLocked ? 'true' : 'false' }} }" x-init="solid = {{ $navLocked ? 'true' : 'false' }} || window.scrollY > 20" @scroll.window="if (! {{ $navLocked ? 'true' : 'false' }}) solid = window.scrollY > 20" :class="open ? 'overflow-hidden' : ''">
    <a href="#content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-[80] focus:bg-ivory focus:px-4 focus:py-2">Skip to content</a>

    @include('partials.site-nav', ['navLocked' => $navLocked])

    <main id="content">
        @yield('content')
    </main>

    @include('partials.site-footer')
</body>
</html>
