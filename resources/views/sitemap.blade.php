{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@php($publicRoutes = ['home', 'residences', 'gallery', 'portfolio', 'about', 'insights', 'enquire', 'visit.book'])
@if (\App\Support\TestimonialContent::published()) @php($publicRoutes[] = 'testimonials') @endif
@foreach ($publicRoutes as $pageRoute)
    <url><loc>{{ \App\Support\Seo::url(route($pageRoute, [], false)) }}</loc></url>
@endforeach
</urlset>
