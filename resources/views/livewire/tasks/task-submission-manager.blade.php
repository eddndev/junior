<div class="space-y-6">
    @if($task)
        {{-- Header with status --}}
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Entrega
            </h3>
            @if($submission)
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                    {{ match($submission->status) {
                        'draft' => 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300',
                        'submitted' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
                        'revision_requested' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
                        'approved' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
                        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                        default => 'bg-gray-100 text-gray-800',
                    } }}">
                    {{ $submission->status_label }}
                </span>
            @else
                <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300">
                    Sin entrega
                </span>
            @endif
        </div>

        {{-- Feedback from reviewer (if any) --}}
        @if($submission && $submission->feedback)
            <div class="rounded-lg border {{ match($submission->status) {
                'revision_requested' => 'border-yellow-200 bg-yellow-50 dark:border-yellow-800 dark:bg-yellow-900/20',
                'approved' => 'border-green-200 bg-green-50 dark:border-green-800 dark:bg-green-900/20',
                'rejected' => 'border-red-200 bg-red-50 dark:border-red-800 dark:bg-red-900/20',
                default => 'border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-800',
            } }} p-4">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 {{ match($submission->status) {
                        'revision_requested' => 'text-yellow-500',
                        'approved' => 'text-green-500',
                        'rejected' => 'text-red-500',
                        default => 'text-gray-500',
                    } }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            Retroalimentación
                            @if($submission->reviewer)
                                <span class="font-normal text-gray-500 dark:text-gray-400">
                                    de {{ $submission->reviewer->name }}
                                </span>
                            @endif
                        </p>
                        <p class="mt-1 text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $submission->feedback }}</p>
                        @if($submission->reviewed_at)
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                {{ $submission->reviewed_at->diffForHumans() }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- Files section --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Archivos adjuntos
            </label>

            {{-- File list --}}
            @if($submission && $submission->files->count() > 0)
                <ul class="divide-y divide-gray-200 dark:divide-white/10 rounded-lg border border-gray-200 dark:border-white/10 mb-3">
                    @foreach($submission->files as $file)
                        <li class="flex items-center justify-between p-3 hover:bg-gray-50 dark:hover:bg-white/5">
                            <div class="flex items-center gap-3 min-w-0">
                                <svg class="h-5 w-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @switch($file->icon)
                                        @case('photo')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            @break
                                        @case('document')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            @break
                                        @default
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                    @endswitch
                                </svg>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $file->filename }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $file->human_size }} &middot; {{ $file->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button wire:click="downloadFile({{ $file->id }})" type="button"
                                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                </button>
                                @if($this->canEdit() && $submission->canBeEdited())
                                    <button wire:click="removeFile({{ $file->id }})" type="button"
                                        wire:confirm="¿Eliminar este archivo?"
                                        class="text-red-400 hover:text-red-600 dark:hover:text-red-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 p-6 text-center mb-3">
                    <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        No hay archivos adjuntos
                    </p>
                </div>
            @endif

            {{-- Upload button (only if can edit) --}}
            @if($this->canEdit() && (!$submission || $submission->canBeEdited()))
                <div class="flex items-center justify-center">
                    <label class="cursor-pointer inline-flex items-center gap-2 rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Agregar archivos
                        <input type="file" wire:model="uploads" multiple class="sr-only" />
                    </label>
                </div>

                {{-- Upload progress --}}
                <div wire:loading wire:target="uploads" class="mt-2">
                    <div class="flex items-center justify-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Subiendo archivos...
                    </div>
                </div>

                @error('uploads.*')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            @endif
        </div>

        {{-- Notes section --}}
        <div>
            <label for="submission-notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Notas de entrega
            </label>
            @if($this->canEdit() && (!$submission || $submission->canBeEdited()))
                <textarea
                    id="submission-notes"
                    wire:model.blur="notes"
                    wire:change="saveNotes"
                    rows="3"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 sm:text-sm sm:leading-6 bg-white dark:bg-white/5"
                    placeholder="Agrega notas o comentarios sobre tu entrega..."
                ></textarea>
            @else
                <div class="rounded-md bg-gray-50 dark:bg-gray-800 p-3 text-sm text-gray-700 dark:text-gray-300">
                    {{ $notes ?: 'Sin notas' }}
                </div>
            @endif
        </div>

        {{-- Submission info --}}
        @if($submission && $submission->submitted_at)
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Entregado {{ $submission->submitted_at->diffForHumans() }}
                @if($submission->submitter)
                    por {{ $submission->submitter->name }}
                @endif
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="flex flex-wrap gap-3">
            {{-- For assignees --}}
            @if($this->canEdit())
                @if(!$submission || $submission->isDraft() || $submission->needsRevision())
                    <button wire:click="submit" type="button"
                        @if(!$submission || $submission->files->count() === 0) disabled @endif
                        class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Entregar
                    </button>
                @elseif($submission && $submission->isSubmitted())
                    <button wire:click="unsubmit" type="button"
                        wire:confirm="¿Cancelar la entrega? Podrás volver a entregarla después."
                        class="inline-flex items-center gap-2 rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                        </svg>
                        Cancelar entrega
                    </button>
                @endif
            @endif

            {{-- For reviewers --}}
            @if($this->canReview() && $submission && $submission->canBeReviewed())
                <button wire:click="showApproveForm" type="button"
                    class="inline-flex items-center gap-2 rounded-md bg-green-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Aprobar
                </button>
                <button wire:click="showRevisionForm" type="button"
                    class="inline-flex items-center gap-2 rounded-md bg-yellow-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-yellow-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Solicitar revisión
                </button>
                <button wire:click="showRejectForm" type="button"
                    class="inline-flex items-center gap-2 rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Rechazar
                </button>
            @endif
        </div>

        {{-- Feedback form (for reviewers) --}}
        @if($showFeedbackForm)
            <div class="rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-gray-800 p-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">
                    {{ match($feedbackAction) {
                        'approve' => 'Aprobar entrega',
                        'revision' => 'Solicitar revisión',
                        'reject' => 'Rechazar entrega',
                        default => 'Retroalimentación',
                    } }}
                </h4>
                <textarea
                    wire:model="feedback"
                    rows="4"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 dark:focus:ring-indigo-500 sm:text-sm sm:leading-6 bg-white dark:bg-white/5"
                    placeholder="{{ in_array($feedbackAction, ['revision', 'reject']) ? 'Explica qué necesita corregirse...' : 'Comentarios opcionales...' }}"
                ></textarea>
                <div class="mt-3 flex gap-2">
                    <button wire:click="submitFeedback" type="button"
                        class="inline-flex items-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm
                        {{ match($feedbackAction) {
                            'approve' => 'bg-green-600 hover:bg-green-500',
                            'revision' => 'bg-yellow-600 hover:bg-yellow-500',
                            'reject' => 'bg-red-600 hover:bg-red-500',
                            default => 'bg-gray-600 hover:bg-gray-500',
                        } }}">
                        Confirmar
                    </button>
                    <button wire:click="cancelFeedback" type="button"
                        class="inline-flex items-center rounded-md bg-white dark:bg-white/10 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-white/10 hover:bg-gray-50 dark:hover:bg-white/20">
                        Cancelar
                    </button>
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Selecciona una tarea para ver las entregas
            </p>
        </div>
    @endif
</div>
