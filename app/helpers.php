<?php

use App\Support\Cms;

if (! function_exists('cms')) {
    /**
     * The CMS content service, or a value by "page.section.field" path.
     */
    function cms(?string $path = null, mixed $default = null): mixed
    {
        $cms = app(Cms::class);

        return $path === null ? $cms : $cms->get($path, $default);
    }
}

if (! function_exists('cms_asset')) {
    function cms_asset(?string $path): string
    {
        return app(Cms::class)->asset($path);
    }
}

if (! function_exists('cms_href')) {
    function cms_href(?string $url): string
    {
        return app(Cms::class)->href($url);
    }
}
