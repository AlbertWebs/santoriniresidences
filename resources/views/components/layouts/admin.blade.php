@props(['title' => 'Content studio', 'kicker' => null])

@php
    $newLeads = \App\Models\Lead::where('status', 'new')->count();
    $upcomingVisits = \App\Models\Lead::whereIn('form_type', ['book-visit', 'schedule-visit'])->whereIn('status', ['new', 'contacted', 'visit_scheduled'])->count();
    $flash = array_values(array_filter([
        session('status') ? ['message' => session('status'), 'type' => 'success'] : null,
        session('error') ? ['message' => session('error'), 'type' => 'error'] : null,
    ]));
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cms-base" content="{{ rtrim(asset(''), '/') }}">
    <title>{{ $title }} | Santorini CMS</title>
    @include('partials.favicons')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600|outfit:300,400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/admin.js'])
    @stack('head')
</head>
<body class="adm min-h-screen antialiased" x-data="{ sidebarOpen: false }" x-init="$store.toasts.flash(@js($flash))">
    <div class="flex min-h-screen">
        <div x-show="sidebarOpen" x-cloak x-transition.opacity class="fixed inset-0 z-30 bg-[#07152a]/50 lg:hidden" @click="sidebarOpen = false"></div>

        <aside class="adm-sidebar fixed inset-y-0 left-0 z-40 flex w-72 flex-col transition-transform duration-500 lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <div class="adm-sidebar__brand px-7 pb-7 pt-8">
                <a href="{{ route('admin.website.index') }}" class="block" aria-label="Santorini CMS home">
                    <img src="{{ asset('media/logo-santorini-enhanced.png') }}" alt="Santorini Residences" class="adm-sidebar__logo">
                </a>
                <div class="mt-5 flex items-center gap-3">
                    @include('partials.wave-mark')
                    <span class="adm-kicker">Content studio</span>
                </div>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 pb-8" aria-label="Administration">
                <x-admin.nav-section title="Website" />
                <x-admin.nav-link href="{{ route('admin.website.index') }}" :active="request()->routeIs('admin.website.index') || (request()->routeIs('admin.website.edit') && request()->route('page') !== 'settings')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.4"/><path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></x-slot:icon>
                    Pages
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.website.edit', 'settings') }}" :active="request()->routeIs('admin.website.edit') && request()->route('page') === 'settings'">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="1.4"/><path d="M12 2.5v3M12 18.5v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2.5 12h3M18.5 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></x-slot:icon>
                    Global settings
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.media.index') }}" :active="request()->routeIs('admin.media.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><rect x="3" y="4" width="18" height="16" stroke="currentColor" stroke-width="1.4"/><path d="m3 16 5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><circle cx="15.5" cy="8.5" r="1.5" stroke="currentColor" stroke-width="1.4"/></svg></x-slot:icon>
                    Media library
                </x-admin.nav-link>

                <x-admin.nav-section title="Leads" />
                <x-admin.nav-link href="{{ route('admin.leads.index') }}" :active="request()->routeIs('admin.leads.index', 'admin.leads.show')" :badge="$newLeads ?: null">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M3 7l9 6 9-6M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></x-slot:icon>
                    All leads
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.leads.visits') }}" :active="request()->routeIs('admin.leads.visits')" :badge="$upcomingVisits ?: null">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M8 2.5v3M16 2.5v3M3.5 9h17M5 4.5h14a1.5 1.5 0 0 1 1.5 1.5v13a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 19V6A1.5 1.5 0 0 1 5 4.5Z" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M8 13h3M8 16.5h7" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></x-slot:icon>
                    Site visits
                </x-admin.nav-link>

                <x-admin.nav-section title="Forms & funnels" />
                <x-admin.nav-link href="{{ route('admin.forms.index') }}" :active="request()->routeIs('admin.forms.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M5 4h14v16H5z" stroke="currentColor" stroke-width="1.4"/><path d="M8 8.5h8M8 12h8M8 15.5h5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></x-slot:icon>
                    Lead forms
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.funnels.index') }}" :active="request()->routeIs('admin.funnels.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M3.5 4.5h17l-6.5 8v6l-4 2v-8z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></x-slot:icon>
                    Social funnels
                </x-admin.nav-link>

                <x-admin.nav-section title="Operations" />
                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M3.5 12.5h7v-9h-7zM3.5 20.5h7v-5h-7zM13.5 20.5h7v-9h-7zM13.5 3.5v5h7v-5z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></x-slot:icon>
                    Dashboard
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.projects.create') }}" :active="request()->routeIs('admin.projects.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg></x-slot:icon>
                    Housing projects
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.cms.blog.index') }}" :active="request()->routeIs('admin.cms.blog.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M4 5h16M4 12h9M4 19h16" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><path d="M17 8.5l3 3-5 5h-3v-3z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></x-slot:icon>
                    Market insights
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.cms.testimonials.index') }}" :active="request()->routeIs('admin.cms.testimonials.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M5 6h14v10H9l-4 3.5z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/></svg></x-slot:icon>
                    Testimonials &amp; press
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.legal.documents') }}" :active="request()->routeIs('admin.legal.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13H7z" stroke="currentColor" stroke-width="1.4" stroke-linejoin="round"/><path d="M14 3v5h5M10 13h6M10 17h4" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/></svg></x-slot:icon>
                    Document vault
                </x-admin.nav-link>
                <x-admin.nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
                    <x-slot:icon><svg viewBox="0 0 24 24" fill="none"><path d="M4 7h10M18 7h2M4 17h2M10 17h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/><circle cx="16" cy="7" r="2" stroke="currentColor" stroke-width="1.4"/><circle cx="8" cy="17" r="2" stroke="currentColor" stroke-width="1.4"/></svg></x-slot:icon>
                    System settings
                </x-admin.nav-link>
            </nav>

            <div class="border-t border-[#bca869]/20 px-7 py-5">
                <div class="flex items-center gap-3">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center border border-[#bca869]/50 font-serif text-lg text-[#e3c992]">{{ \Illuminate\Support\Str::substr(auth()->user()?->name ?? 'S', 0, 1) }}</span>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm text-[#f4f5f6]">{{ auth()->user()?->name }}</p>
                        <p class="truncate text-xs text-[#e8e9ea]/50">{{ auth()->user()?->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="flex h-8 w-8 items-center justify-center text-[#e8e9ea]/60 transition hover:text-[#e3c992]" title="Sign out" aria-label="Sign out">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none"><path d="M15 4h4v16h-4M10 8l-4 4 4 4M6 12h10" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex min-h-screen min-w-0 flex-1 flex-col lg:pl-72">
            <header class="adm-topbar sticky top-0 z-20">
                <div class="flex items-center justify-between gap-4 px-5 py-4 lg:px-10">
                    <div class="flex min-w-0 items-center gap-4">
                        <button class="adm-icon-btn lg:hidden" @click="sidebarOpen = true" aria-label="Open navigation">
                            <svg viewBox="0 0 24 24" fill="none"><path d="M4 7h16M4 17h16" stroke="currentColor" stroke-width="1.4"/></svg>
                        </button>
                        <div class="min-w-0">
                            <p class="adm-kicker">{{ $kicker ?? 'Santorini Residences' }}</p>
                            <h1 class="adm-title mt-1 truncate text-[1.7rem]">{{ $title }}</h1>
                        </div>
                    </div>
                    <div class="flex shrink-0 items-center gap-3">
                        {{ $actions ?? '' }}
                        <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="adm-btn adm-btn--ghost adm-btn--sm hidden sm:inline-flex">
                            View website
                            <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none"><path d="M9 5h10v10M19 5 5 19" stroke="currentColor" stroke-width="1.6"/></svg>
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-5 py-8 lg:px-10 lg:py-10">
                {{ $slot }}
            </main>
        </div>
    </div>

    <div class="adm-toasts" aria-live="polite">
        <template x-for="toast in $store.toasts.items" :key="toast.id">
            <div class="adm-toast" :class="toast.type === 'error' && 'adm-toast--error'" x-transition.opacity.duration.300ms>
                <span class="adm-toast__mark"></span>
                <p class="flex-1" x-text="toast.message"></p>
                <button type="button" class="text-[#e8e9ea]/50 hover:text-[#e8e9ea]" @click="$store.toasts.dismiss(toast.id)" aria-label="Dismiss">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.6"/></svg>
                </button>
            </div>
        </template>
    </div>

    @include('admin.partials.media-picker')
    @stack('scripts')
</body>
</html>
