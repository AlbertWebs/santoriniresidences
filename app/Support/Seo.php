<?php

namespace App\Support;

class Seo
{
    /** @return list<array{route: string, label: string, group: string}> */
    public static function publicPages(): array
    {
        $pages = [
            ['route' => 'home', 'label' => 'Home', 'group' => 'home'],
            ['route' => 'project-facts', 'label' => 'Project facts & FAQs', 'group' => 'explore'],
            ['route' => 'residences', 'label' => 'Residences', 'group' => 'explore'],
            ['route' => 'gallery', 'label' => 'Gallery', 'group' => 'explore'],
            ['route' => 'portfolio', 'label' => 'Portfolio', 'group' => 'explore'],
            ['route' => 'company-profile', 'label' => 'Company profile', 'group' => 'explore'],
            ['route' => 'about', 'label' => 'About Santorini Residences', 'group' => 'explore'],
            ['route' => 'insights', 'label' => 'Insights', 'group' => 'explore'],
            ['route' => 'enquire', 'label' => 'Enquire', 'group' => 'contact'],
            ['route' => 'visit.book', 'label' => 'Book a site visit', 'group' => 'contact'],
        ];

        if (TestimonialContent::published() !== []) {
            $pages[] = ['route' => 'testimonials', 'label' => 'Testimonials', 'group' => 'explore'];
        }

        return $pages;
    }

    public static function indexable(): bool
    {
        return app()->environment('production')
            && request()->getHost() === parse_url(config('app.url'), PHP_URL_HOST);
    }

    public static function url(string $path = '/'): string
    {
        return rtrim(config('app.url'), '/').'/'.ltrim($path, '/');
    }

    public static function robots(): string
    {
        $routeName = request()->route()?->getName();
        $publicRouteNames = array_column(self::publicPages(), 'route');

        return self::indexable() && in_array($routeName, $publicRouteNames, true)
            ? 'index, follow, max-image-preview:large'
            : 'noindex, nofollow';
    }
}
