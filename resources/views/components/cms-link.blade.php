@props(['link' => null])
@php($link = is_array($link) ? $link : [])
@if (filled($link['label'] ?? null))
    <a href="{{ cms_href($link['url'] ?? '') }}" @if (cms()->isExternal($link['url'] ?? '')) target="_blank" rel="noopener noreferrer" @endif {{ $attributes }}>{{ $slot }}{{ $link['label'] }}</a>
@endif
