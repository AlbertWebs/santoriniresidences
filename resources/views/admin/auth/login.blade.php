<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>Sign in | Santorini Residences CMS</title>
    @include('partials.favicons')
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cormorant-garamond:500,600|outfit:300,400,500" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/css/admin.css'])
</head>
<body class="adm adm-login antialiased">
    <div class="grid min-h-screen lg:grid-cols-[1.15fr_1fr]">
        <div class="relative hidden overflow-hidden lg:block">
            <img src="{{ asset('media/hero-night.webp') }}" alt="" class="adm-login__still absolute inset-0 h-full w-full object-cover object-[center_42%]">
            <div class="absolute inset-0 bg-gradient-to-t from-[#07152a] via-[#07152a]/40 to-[#07152a]/30"></div>
            <span class="adm-login__frame" aria-hidden="true"></span>
            <div class="absolute inset-x-0 bottom-0 p-16">
                <div class="flex items-center gap-4">
                    @include('partials.wave-mark')
                    <p class="adm-kicker">Lantana Road, Westlands</p>
                </div>
                <p class="adm-title adm-title--light mt-6 max-w-lg text-5xl">The house <em class="text-[#e3c992]">content studio.</em></p>
                <p class="mt-5 max-w-md text-sm leading-relaxed text-[#e8e9ea]/70">Website copy, imagery, lead forms and social funnels for Santorini Residences.</p>
            </div>
        </div>

        <main class="flex items-center justify-center bg-[#faf8f4] px-6 py-16 sm:px-12">
            <div class="adm-rise w-full max-w-sm">
                <img src="{{ asset('media/logo-santorini-ink.png') }}" alt="Santorini Residences" class="h-auto w-40">
                <span class="adm-rule mt-12"></span>
                <p class="adm-kicker adm-kicker--navy mt-6">Content management</p>
                <h1 class="adm-title mt-4 text-5xl">Welcome back.</h1>

                <form method="POST" action="{{ route('admin.login.attempt') }}" class="mt-12 space-y-8">
                    @csrf
                    <div>
                        <label for="email" class="adm-label">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="adm-login__field">
                    </div>
                    <div>
                        <label for="password" class="adm-label">Password</label>
                        <input id="password" name="password" type="password" required autocomplete="current-password" class="adm-login__field">
                    </div>

                    @error('email')
                        <p class="adm-error" role="alert">{{ $message }}</p>
                    @enderror

                    <label class="flex items-center gap-3 text-sm text-[#6f675e]">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 accent-[#0e1e37]">
                        Keep me signed in on this device
                    </label>

                    <button type="submit" class="adm-btn w-full">
                        Sign in
                        <svg class="h-3 w-6" viewBox="0 0 28 12" fill="none" aria-hidden="true"><path d="M0 6h26M21 1l5 5-5 5" stroke="currentColor" stroke-width="1"/></svg>
                    </button>
                </form>

                <a href="{{ route('home') }}" class="adm-link mt-14 inline-block">Return to the website</a>
            </div>
        </main>
    </div>
</body>
</html>
