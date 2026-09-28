{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach (['home', 'residences', 'gallery', 'about', 'enquire', 'visit.book'] as $pageRoute)
    <url><loc>{{ \App\Support\Seo::url(route($pageRoute, [], false)) }}</loc></url>
@endforeach
</urlset>
