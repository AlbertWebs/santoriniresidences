<x-layouts.admin title="Website pages" kicker="Website">
    <div class="grid gap-10 xl:grid-cols-[1fr_22rem]">
        <div>
            <div class="flex flex-wrap items-end justify-between gap-6">
                <div class="max-w-2xl">
                    <span class="adm-rule"></span>
                    <p class="adm-title mt-6 text-4xl md:text-5xl">Every word and image, <em class="text-[#0e1e37]">in one place.</em></p>
                    <p class="mt-4 text-sm leading-relaxed text-[#6f675e]">Choose a page to edit its copy, imagery and calls to action. Saved changes go live immediately, and any section can be restored to its original copy.</p>
                </div>
            </div>

            <div class="mt-10 grid gap-5 md:grid-cols-2">
                @foreach ($pages as $page)
                    <article class="adm-card group relative flex flex-col p-7 transition hover:border-[#d3c4a6]">
                        <div class="flex items-start justify-between gap-4">
                            <span class="adm-section__index">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            @if ($page['customised'])
                                <span class="adm-pill adm-pill--on">{{ $page['customised'] }} edited</span>
                            @else
                                <span class="adm-pill adm-pill--off">Original copy</span>
                            @endif
                        </div>
                        <h2 class="adm-title mt-5 text-3xl">
                            <a href="{{ route('admin.website.edit', $page['key']) }}" class="after:absolute after:inset-0">{{ $page['label'] }}</a>
                        </h2>
                        <p class="mt-3 flex-1 text-sm leading-relaxed text-[#6f675e]">{{ $page['description'] }}</p>
                        <div class="mt-7 flex items-center justify-between border-t border-[#efe9df] pt-5 text-xs text-[#6f675e]">
                            <span>{{ $page['sections'] }} sections @if ($page['updated_at'])· updated {{ $page['updated_at']->diffForHumans() }}@endif</span>
                            <span class="adm-link">Edit</span>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <aside class="space-y-5">
            <div class="adm-card p-7">
                <p class="adm-kicker">Quick links</p>
                <ul class="mt-5 space-y-4 text-sm">
                    <li class="flex items-center justify-between gap-4"><span>Media library</span><a class="adm-link" href="{{ route('admin.media.index') }}">Open</a></li>
                    <li class="flex items-center justify-between gap-4"><span>Lead forms</span><a class="adm-link" href="{{ route('admin.forms.index') }}">Open</a></li>
                    <li class="flex items-center justify-between gap-4"><span>Social funnels</span><a class="adm-link" href="{{ route('admin.funnels.index') }}">Open</a></li>
                    <li class="flex items-center justify-between gap-4"><span>Book a site visit page</span><a class="adm-link" href="{{ route('visit.book') }}" target="_blank" rel="noopener noreferrer">View</a></li>
                </ul>
            </div>
            <div class="adm-card bg-[#07152a] p-7 text-[#e8e9ea]" style="background: linear-gradient(180deg, #0e1e37, #07152a); border-color: rgba(188,168,105,.35)">
                <p class="adm-kicker">House style</p>
                <ul class="mt-5 space-y-3 text-sm leading-relaxed text-[#e8e9ea]/75">
                    <li>Keep headlines short. The accent line is set in italic.</li>
                    <li>Describe every image for visitors using screen readers.</li>
                    <li>Only publish confirmed facts, figures and prices.</li>
                    <li>Use commas or full stops rather than long dashes.</li>
                </ul>
            </div>
        </aside>
    </div>
</x-layouts.admin>
