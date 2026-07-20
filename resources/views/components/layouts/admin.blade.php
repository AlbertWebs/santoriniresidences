@props(['title' => 'Admin Overview'])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} | {{ config('app.name', 'Santorini Residences') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-neutral-50 text-neutral-900" x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <aside class="fixed inset-y-0 left-0 z-40 flex w-72 flex-col border-r border-neutral-200 bg-white/95 backdrop-blur-lg lg:translate-x-0"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
            <div class="flex items-center gap-3 px-5 py-6">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-neutral-900 text-white">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none">
                        <path d="M4 20V9l8-5 8 5v11h-5v-6H9v6H4Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold tracking-wide text-neutral-900">Santorini Admin</p>
                    <p class="text-xs text-neutral-500">Off-Plan Settlement</p>
                </div>
            </div>

            <nav class="flex-1 space-y-0.5 overflow-y-auto px-3 pb-6">
                <x-admin.nav-section title="Operational Data" />

                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 12h8V3H3v9Zm0 9h8v-7H3v7Zm10 0h8v-9h-8v9Zm0-18v7h8V3h-8Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Dashboard
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.projects.create') }}" :active="request()->routeIs('admin.projects.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-6h6v6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Housing Projects
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.projects.create') }}" :active="request()->routeIs('admin.units.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M5 20h14M7 20v-7h10v7M6 13l6-9 6 9" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Property Units
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.projects.create') }}" :active="request()->routeIs('admin.media.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M4 6h16M4 12h16M4 18h10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="17" cy="18" r="3" stroke="currentColor" stroke-width="1.8"/></svg>
                    </x-slot:icon>
                    Content Media
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.payments.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 10h18M7 15h.01M11 15h6M5 5h14a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </x-slot:icon>
                    Settle & Payments
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.users.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm14 10v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    User Management
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.settings.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </x-slot:icon>
                    Settings
                </x-admin.nav-link>

                <x-admin.nav-section title="Website CMS" />

                <x-admin.nav-link href="{{ route('admin.cms.pages.index') }}" :active="request()->routeIs('admin.cms.pages.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M7 3h10a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8"/><path d="M9 8h6M9 12h6M9 16h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </x-slot:icon>
                    Pages Manager
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.cms.blog.index') }}" :active="request()->routeIs('admin.cms.blog.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M4 5h16M4 12h10M4 19h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M17 8l3 3-6 6H11v-3l6-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Blog / Market Insights
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.cms.testimonials.index') }}" :active="request()->routeIs('admin.cms.testimonials.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M8 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM16 10a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM4 20v-1a4 4 0 0 1 4-4h0M16 15a4 4 0 0 1 4 4v1" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8 14h8l1 6H7l1-6Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Testimonials & Press
                </x-admin.nav-link>

                <x-admin.nav-section title="Lead & Sales Pipeline" />

                <x-admin.nav-link href="{{ route('admin.leads.inquiries') }}" :active="request()->routeIs('admin.leads.inquiries')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 8l9 6 9-6M4 6h16a1 1 0 0 1 1 1v11a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
                    </x-slot:icon>
                    Inquiries & Leads
                </x-admin.nav-link>

                <x-admin.nav-link href="{{ route('admin.leads.site-visits') }}" :active="request()->routeIs('admin.leads.site-visits')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M8 2v3M16 2v3M3 9h18M5 5h14a2 2 0 0 1 2 2v13a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V7a2 2 0 0 1 2-2Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><path d="M8 13h4M8 17h8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </x-slot:icon>
                    Site Visit Scheduler
                </x-admin.nav-link>

                <x-admin.nav-section title="Legal & Documentation" />

                <x-admin.nav-link href="{{ route('admin.legal.documents') }}" :active="request()->routeIs('admin.legal.*')">
                    <x-slot:icon>
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/><path d="M14 3v5h5M9 13h6M9 17h4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                    </x-slot:icon>
                    Document Vault
                </x-admin.nav-link>
            </nav>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col lg:pl-72">
            <header class="sticky top-0 z-30 border-b border-white/50 bg-white/70 px-4 py-3 backdrop-blur-xl lg:px-8">
                <div class="flex items-center justify-between rounded-2xl border border-neutral-200/80 bg-white/70 px-4 py-3 shadow-[0_10px_30px_rgba(12,17,29,0.08)]">
                    <div class="flex items-center gap-3">
                        <button class="rounded-lg border border-neutral-200 p-2 text-neutral-600 lg:hidden" @click="sidebarOpen = !sidebarOpen">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none"><path d="M3 6h18M3 12h18M3 18h18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                        </button>
                        <div>
                            <h1 class="text-base font-semibold text-neutral-900">{{ $title }}</h1>
                            <p class="text-xs text-neutral-500">Luxury off-plan sales operations</p>
                        </div>
                    </div>
                    <div class="relative flex items-center gap-3" x-data="{ open: false }">
                        <button class="rounded-xl border border-neutral-200 bg-white px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50">Export</button>
                        <button
                            class="flex items-center gap-2 rounded-xl border border-neutral-200 bg-white px-2 py-1.5 text-sm font-medium text-neutral-700 transition hover:bg-neutral-50"
                            @click.stop="open = !open"
                            :aria-expanded="open.toString()"
                            aria-haspopup="true"
                        >
                            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-neutral-100 text-xs font-semibold">SR</span>
                            <span class="hidden sm:block">Admin</span>
                            <svg class="h-4 w-4 text-neutral-500 transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>

                        <div
                            x-show="open"
                            x-transition
                            @click.outside="open = false"
                            @click.stop
                            class="absolute right-0 top-[3.25rem] z-[70] w-64 overflow-hidden rounded-xl border border-neutral-200 bg-white py-2 shadow-xl"
                            style="display: none;"
                        >
                            <div class="border-b border-neutral-100 px-4 py-2">
                                <p class="text-sm font-semibold text-neutral-900">Santorini Admin</p>
                                <p class="text-xs text-neutral-500">admin@santoriniresidences.com</p>
                            </div>
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50">
                                <svg class="h-4 w-4 text-neutral-500" viewBox="0 0 24 24" fill="none"><path d="M12 12a5 5 0 1 0 0-10 5 5 0 0 0 0 10ZM3 21a9 9 0 0 1 18 0" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                Profile
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50">
                                <svg class="h-4 w-4 text-neutral-500" viewBox="0 0 24 24" fill="none"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
                                Settings
                            </a>
                            <a href="{{ route('admin.settings.backup') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-neutral-700 transition hover:bg-neutral-50">
                                <svg class="h-4 w-4 text-neutral-500" viewBox="0 0 24 24" fill="none"><path d="M12 16V8m0 0-3 3m3-3 3 3M4 15v2a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3v-2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Data Backup
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-4 py-6 lg:px-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
