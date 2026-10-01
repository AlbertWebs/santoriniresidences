<?php

namespace App\Support;

class Seo
{
    public static function indexable(): bool
    {
        return app()->environment('production')
            && request()->getHost() === parse_url(config('app.url'), PHP_URL_HOST)
            && ! str_starts_with(request()->getHost(), 'staging.');
    }

    public static function url(string $path = '/'): string
    {
        return rtrim(config('app.url'), '/').'/'.ltrim($path, '/');
    }

    public static function robots(): string
    {
        $testimonialsArePublic = request()->routeIs('testimonials') && TestimonialContent::published() !== [];

        return self::indexable() && (request()->routeIs('home', 'residences', 'gallery', 'about', 'insights', 'enquire', 'visit.book') || $testimonialsArePublic)
            ? 'index, follow, max-image-preview:large'
            : 'noindex, nofollow';
    }
}
