{{--
    Componente de Tabla Empresarial - Sistema de Diseño Junior

    Uso:
    <x-ui-table id="users-table" :selectable="true">
        <x-slot:header>
            <th>Nombre</th>
            <th>Email</th>
            <th>Role</th>
        </x-slot:header>

        <tr>
            <td>...</td>
        </tr>
    </x-ui-table>
--}}

@props([
    'id' => 'data-table',
    'selectable' => false,
    'title' => null,
    'description' => null,
    'actionButton' => null,
    'bulkActions' => false,
])

<div>
    @if($title || $description || $actionButton)
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            @if($title)
            <h1 class="text-base font-semibold text-neutral-900 dark:text-white">{{ $title }}</h1>
            @endif
            @if($description)
            <p class="mt-2 text-sm text-neutral-700 dark:text-neutral-300">{{ $description }}</p>
            @endif
        </div>
        @if($actionButton)
        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
            {{ $actionButton }}
        </div>
        @endif
    </div>
    @endif

    <div class="{{ $title || $description ? 'mt-8' : '' }} flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="group/table relative">
                    @if($selectable && $bulkActions)
                    <div class="absolute top-0 left-14 z-10 hidden h-12 items-center space-x-3 bg-white group-has-checked/table:flex sm:left-12 dark:bg-neutral-900">
                        {{ $bulkActions }}
                    </div>
                    @endif

                    <table id="{{ $id }}" class="relative min-w-full table-fixed divide-y divide-neutral-300 dark:divide-white/15">
                        <thead>
                            <tr>
                                @if($selectable)
                                <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                                    <div class="group absolute top-1/2 left-4 -mt-2 grid size-4 grid-cols-1">
                                        <input
                                            type="checkbox"
                                            class="col-start-1 row-start-1 appearance-none rounded-sm border border-neutral-300 bg-white checked:border-primary-600 checked:bg-primary-600 indeterminate:border-primary-600 indeterminate:bg-primary-600 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 disabled:border-neutral-300 disabled:bg-neutral-100 disabled:checked:bg-neutral-100 dark:border-white/20 dark:bg-neutral-800/50 dark:checked:border-primary-500 dark:checked:bg-primary-500 dark:indeterminate:border-primary-500 dark:indeterminate:bg-primary-500 dark:focus-visible:outline-primary-500 dark:disabled:border-white/10 dark:disabled:bg-neutral-800 dark:disabled:checked:bg-neutral-800 forced-colors:appearance-auto"
                                        />
                                        <svg viewBox="0 0 14 14" fill="none" class="pointer-events-none col-start-1 row-start-1 size-3.5 self-center justify-self-center stroke-white group-has-disabled:stroke-neutral-950/25 dark:group-has-disabled:stroke-white/25">
                                            <path d="M3 8L6 11L11 3.5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-checked:opacity-100" />
                                            <path d="M3 7H11" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-0 group-has-indeterminate:opacity-100" />
                                        </svg>
                                    </div>
                                </th>
                                @endif

                                {{ $header ?? '' }}
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-200 bg-white dark:divide-white/10 dark:bg-neutral-900">
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if($selectable)
<script type="module">
(function() {
    const usersTable = document.getElementById('{{ $id }}');
    if (!usersTable) return;

    const toggleAllCheckbox = usersTable.querySelector("thead input[type='checkbox']");
    const checkboxes = [...usersTable.querySelectorAll("tbody input[type='checkbox']")];

    if (!toggleAllCheckbox || checkboxes.length === 0) return;

    toggleAllCheckbox.addEventListener('change', (event) => {
        checkboxes.forEach((checkbox) => {
            checkbox.checked = event.target.checked;
        });
    });

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener('change', () => {
            const allChecked = checkboxes.every((checkbox) => checkbox.checked);
            const someChecked = checkboxes.some((checkbox) => checkbox.checked);
            toggleAllCheckbox.checked = someChecked;
            toggleAllCheckbox.indeterminate = someChecked && !allChecked;
        });
    });
})();
</script>
@endif
