@php
    $selected = collect($selected ?? [])->map(fn ($lead) => [
        'id' => $lead->id,
        'name' => $lead->name,
        'email' => $lead->email,
        'status' => $lead->status,
        'status_label' => $lead->statusLabel(),
    ])->values();
@endphp
<div x-data="customerPicker({ endpoint: @js(route('admin.leads.search')), selected: @js($selected) })" class="relative" @click.outside="open = false">
    <label for="{{ $id ?? 'customer-search' }}" class="adm-label">Customers <small>Optional</small></label>
    <div class="adm-picker" :class="open && 'is-open'" @click="$refs.search.focus()">
        <template x-for="lead in selected" :key="lead.id">
            <span class="adm-chip">
                <input type="hidden" name="lead_ids[]" :value="lead.id">
                <span class="adm-chip__initial" x-text="lead.name.charAt(0).toUpperCase()"></span>
                <span x-text="lead.name"></span>
                <button type="button" @click.stop="remove(lead.id)" :aria-label="'Remove ' + lead.name">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6 6 18" stroke="currentColor" stroke-width="1.8"/></svg>
                </button>
            </span>
        </template>
        <input id="{{ $id ?? 'customer-search' }}" x-ref="search" type="text" x-model="query" autocomplete="off"
            @input="search()" @focus="search()"
            @keydown.arrow-down.prevent="move(1)" @keydown.arrow-up.prevent="move(-1)"
            @keydown.enter.prevent="choose()" @keydown.escape="open = false" @keydown.backspace="backspace()"
            :placeholder="selected.length ? 'Add another customer' : 'Search leads by name, email or telephone'"
            role="combobox" :aria-expanded="open.toString()" aria-autocomplete="list">
        <span x-show="loading" class="adm-picker__spinner" aria-hidden="true"></span>
    </div>
    <ul x-show="open" x-cloak x-transition.opacity.duration.150ms class="adm-picker__menu" role="listbox">
        <template x-for="(lead, index) in suggestions" :key="lead.id">
            <li role="option" :aria-selected="(index === active).toString()">
                <button type="button" class="adm-picker__option" :class="index === active && 'is-active'" @mouseenter="active = index" @mousedown.prevent="add(lead)">
                    <span class="adm-avatar" x-text="lead.name.charAt(0).toUpperCase()"></span>
                    <span class="min-w-0 flex-1">
                        <span class="block truncate text-sm text-[#161311]" x-text="lead.name"></span>
                        <span class="block truncate text-xs text-[#6f675e]" x-text="lead.email"></span>
                    </span>
                    <span class="adm-pill" :class="'adm-pill--' + lead.status" x-text="lead.status_label"></span>
                </button>
            </li>
        </template>
        <li x-show="!loading && !suggestions.length" class="px-4 py-5 text-center text-sm text-[#6f675e]">No matching leads.</li>
    </ul>
    <p class="adm-help">{{ $help ?? 'Linked customers see this document on their lead record and can be sent a private link.' }}</p>
</div>
