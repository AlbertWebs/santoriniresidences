@props(['src' => null, 'alt' => '', 'sizes' => '100vw', 'priority' => false, 'fallback' => 1600])
@php
    $srcset = \App\Support\ResponsiveImage::srcset($src);
    $dimensions = \App\Support\ResponsiveImage::dimensions($src);
@endphp
@if ($src)
    <img
        src="{{ $srcset ? \App\Support\ResponsiveImage::url($src, $fallback) : cms_asset($src) }}"
        @if ($srcset) srcset="{{ $srcset }}" sizes="{{ $sizes }}" @endif
        alt="{{ $alt }}"
        @if ($dimensions) width="{{ $dimensions[0] }}" height="{{ $dimensions[1] }}" @endif
        @if ($priority) fetchpriority="high" @else loading="lazy" decoding="async" @endif
        {{ $attributes }}
    >
@endif
