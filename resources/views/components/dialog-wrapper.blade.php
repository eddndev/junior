@props(['id', 'maxWidth' => '2xl', 'slideFrom' => 'right'])

@php
$maxWidthClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
    '5xl' => 'max-w-5xl',
    'full' => 'max-w-full',
];

// Slide-in direction classes
$slideClasses = [
    'right' => 'ml-auto data-closed:translate-x-full',
    'left' => 'mr-auto data-closed:-translate-x-full',
    'top' => 'mx-auto data-closed:-translate-y-full',
    'bottom' => 'mx-auto data-closed:translate-y-full',
];

$paddingClasses = [
    'right' => 'pl-10 sm:pl-16',
    'left' => 'pr-10 sm:pr-16',
    'top' => 'pt-10 sm:pt-16',
    'bottom' => 'pb-10 sm:pb-16',
];
@endphp

{{--
    Dialog Wrapper Component

    Este componente envuelve un el-dialog y debe permanecer FUERA del componente Livewire
    para evitar perder el estado del dialog en los re-renders.

    Uso:
    <x-dialog-wrapper id="my-dialog" max-width="2xl">
        @livewire('my-dialog-component')
    </x-dialog-wrapper>
--}}

<el-dialog>
    <dialog {{ $attributes->merge(['id' => $id, 'aria-labelledby' => $id . '-title']) }}
            class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent">

        {{-- Backdrop --}}
        <el-dialog-backdrop
            class="absolute inset-0 bg-neutral-500/75 dark:bg-neutral-950/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0">
        </el-dialog-backdrop>

        {{-- Dialog Panel Container --}}
        <div tabindex="0"
             class="absolute inset-0 {{ $paddingClasses[$slideFrom] }} focus:outline-none">

            {{-- Sliding Panel --}}
            <el-dialog-panel
                class="block size-full {{ $maxWidthClasses[$maxWidth] }} {{ $slideClasses[$slideFrom] }} transform transition duration-500 ease-in-out sm:duration-700">

                {{-- Content Container --}}
                <div class="flex h-full flex-col overflow-y-auto bg-white dark:bg-neutral-900 shadow-xl rounded-lg">
                    {{ $slot }}
                </div>
            </el-dialog-panel>
        </div>
    </dialog>
</el-dialog>
