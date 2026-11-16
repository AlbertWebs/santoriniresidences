{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
@foreach (\App\Support\Seo::publicPages() as $page)
    <url><loc>{{ \App\Support\Seo::url(route($page['route'], [], false)) }}</loc></url>
@endforeach
</urlset>
