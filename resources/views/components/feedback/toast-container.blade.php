{{-- Global notification live region, render this permanently at the end of the document --}}
<div
    aria-live="assertive"
    class="pointer-events-none fixed inset-0 z-50 flex items-end px-4 py-6 sm:items-start sm:p-6"
    x-data="toastManager()"
    @toast.window="addToast($event.detail)"
>
    <div class="flex w-full flex-col items-center space-y-4 sm:items-end">
        {{-- Dynamic toasts will be rendered here --}}
        <template x-for="toast in toasts" :key="toast.id">
            <div
                x-show="toast.show"
                x-transition:enter="transform transition duration-300 ease-out"
                x-transition:enter-start="translate-y-2 opacity-0 sm:translate-x-2 sm:translate-y-0"
                x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
                x-transition:leave="transition duration-100 ease-in"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                @click.away="if (toast.dismissOnClickAway) removeToast(toast.id)"
                class="pointer-events-auto w-full max-w-sm rounded-lg bg-white shadow-lg outline-1 outline-black/5 dark:bg-neutral-800 dark:-outline-offset-1 dark:outline-white/10"
            >
                <div class="p-4">
                    <div class="flex items-start">
                        {{-- Icon --}}
                        <template x-if="toast.showIcon">
                            <div class="shrink-0">
                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.5"
                                    data-slot="icon"
                                    aria-hidden="true"
                                    class="size-6"
                                    :class="{
                                        'text-green-400': toast.type === 'success',
                                        'text-red-400': toast.type === 'error',
                                        'text-yellow-400 dark:text-yellow-300': toast.type === 'warning',
                                        'text-neutral-400': toast.type === 'info'
                                    }"
                                >
                                    <path x-html="toast.iconPath" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </div>
                        </template>

                        <div :class="toast.showIcon ? 'ml-3' : ''" class="w-0 flex-1 pt-0.5">
                            {{-- Title --}}
                            <template x-if="toast.title">
                                <p class="text-sm font-medium text-neutral-900 dark:text-white" x-text="toast.title"></p>
                            </template>

                            {{-- Description --}}
                            <template x-if="toast.description">
                                <p
                                    :class="toast.title ? 'mt-1' : ''"
                                    class="text-sm text-neutral-500 dark:text-neutral-400"
                                    x-text="toast.description"
                                ></p>
                            </template>

                            {{-- Actions --}}
                            <template x-if="toast.actions && toast.actions.length > 0">
                                <div class="mt-3 flex space-x-7">
                                    <template x-for="action in toast.actions" :key="action.label">
                                        <button
                                            type="button"
                                            @click="action.onClick(); if (action.dismiss) removeToast(toast.id)"
                                            class="rounded-md text-sm font-medium focus:outline-2 focus:outline-offset-2"
                                            :class="{
                                                'text-green-600 hover:text-green-500 focus:outline-green-500 dark:text-green-400 dark:hover:text-green-300 dark:focus:outline-green-400': toast.type === 'success' && action.primary,
                                                'text-red-600 hover:text-red-500 focus:outline-red-500 dark:text-red-400 dark:hover:text-red-300 dark:focus:outline-red-400': toast.type === 'error' && action.primary,
                                                'text-yellow-600 hover:text-yellow-500 focus:outline-yellow-500 dark:text-yellow-400 dark:hover:text-yellow-300 dark:focus:outline-yellow-400': toast.type === 'warning' && action.primary,
                                                'text-primary-600 hover:text-primary-500 focus:outline-primary-500 dark:text-primary-400 dark:hover:text-primary-300 dark:focus:outline-primary-400': toast.type === 'info' && action.primary,
                                                'text-neutral-700 hover:text-neutral-500 focus:outline-primary-500 dark:text-neutral-300 dark:hover:text-white dark:focus:outline-primary-400': !action.primary
                                            }"
                                            x-text="action.label"
                                        ></button>
                                    </template>
                                </div>
                            </template>
                        </div>

                        {{-- Close button --}}
                        <div class="ml-4 flex shrink-0">
                            <button
                                type="button"
                                @click="removeToast(toast.id)"
                                class="inline-flex rounded-md text-neutral-400 hover:text-neutral-500 focus:outline-2 focus:outline-offset-2 focus:outline-primary-600 dark:hover:text-white dark:focus:outline-primary-500"
                            >
                                <span class="sr-only">Close</span>
                                <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="size-5">
                                    <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastManager', () => ({
        toasts: [],
        nextId: 1,

        iconPaths: {
            success: 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            error: 'm9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z',
            warning: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z',
            info: 'M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z',
        },

        addToast(options) {
            const toast = {
                id: this.nextId++,
                type: options.type || 'info',
                title: options.title || '',
                description: options.description || '',
                showIcon: options.showIcon !== false,
                actions: options.actions || [],
                autoHide: options.autoHide !== false,
                duration: options.duration || 5000,
                dismissOnClickAway: options.dismissOnClickAway || false,
                show: true,
                iconPath: this.iconPaths[options.type || 'info']
            };

            this.toasts.push(toast);

            if (toast.autoHide) {
                setTimeout(() => {
                    this.removeToast(toast.id);
                }, toast.duration);
            }
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].show = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 100);
            }
        }
    }));
});

// Helper function to show toasts from anywhere
window.showToast = function(options) {
    window.dispatchEvent(new CustomEvent('toast', { detail: options }));
};
</script>