@props([
    'value' => null,
])

<el-option
    value="{{ $value }}"
    {{ $attributes->merge([
        'class' => 'group/option relative block cursor-default py-2 pr-9 pl-3 text-neutral-900 select-none focus:bg-primary-600 focus:text-white focus:outline-hidden dark:text-white dark:focus:bg-primary-500'
    ]) }}
>
    {{-- Option content (flexible via slot) --}}
    <div class="flex items-center">
        {{ $slot }}
    </div>

    {{-- Checkmark icon (shown when selected) --}}
    <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600 group-not-aria-selected/option:hidden group-focus/option:text-white in-[el-selectedcontent]:hidden dark:text-primary-400">
        <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
            <path d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" fill-rule="evenodd" />
        </svg>
    </span>
</el-option>
