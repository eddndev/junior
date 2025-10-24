# Guía Rápida: Patrón de Dialogs con Livewire

**Actualizado:** 2025-10-23
**Sprint:** 3.5 - Infraestructura Base

---

## ✅ Infraestructura Implementada

- ✅ Trait `WithDialog` (`app/Livewire/Traits/WithDialog.php`)
- ✅ Componente `dialog-wrapper` (`resources/views/components/dialog-wrapper.blade.php`)
- ✅ Sistema de eventos `DialogSystem` (`resources/js/dialog-system.js`)
- ✅ Componentes `dialog-header` y `dialog-footer`
- ✅ Assets compilados y listos para usar

---

## 📖 Cómo Crear un Dialog Livewire

### 1. Crear el Componente Livewire

```bash
php artisan make:livewire Tasks/TaskDetailDialog
```

**Archivo:** `app/Livewire/Tasks/TaskDetailDialog.php`

```php
<?php

namespace App\Livewire\Tasks;

use App\Models\Task;
use Livewire\Component;

class TaskDetailDialog extends Component
{
    public ?Task $task = null;
    public int $taskId;

    // Form fields
    public string $title = '';
    public string $description = '';

    // Edit mode
    public bool $editingTitle = false;

    protected $listeners = [
        'loadTask' => 'load',
    ];

    public function load(int $taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::findOrFail($taskId);

        $this->title = $this->task->title;
        $this->description = $this->task->description ?? '';
    }

    public function saveTitle()
    {
        $this->validate([
            'title' => 'required|string|min:3|max:255',
        ]);

        $this->task->update(['title' => $this->title]);
        $this->editingTitle = false;

        $this->dispatch('task-updated', taskId: $this->taskId);
        $this->dispatch('show-toast', message: 'Título actualizado', type: 'success');
    }

    public function render()
    {
        return view('livewire.tasks.task-detail-dialog');
    }
}
```

### 2. Crear la Vista Livewire

**Archivo:** `resources/views/livewire/tasks/task-detail-dialog.blade.php`

```blade
<div>
    @if($task)
        {{-- Header --}}
        <x-dialog-header
            :title="$title"
            subtitle="Detalle de la tarea"
            dialog-id="task-detail-dialog" />

        {{-- Content --}}
        <div class="flex-1 overflow-y-auto px-6 py-6">
            {{-- Title Editor --}}
            <div class="mb-6">
                @if($editingTitle)
                    <div class="flex items-center gap-2">
                        <input type="text"
                               wire:model="title"
                               wire:keydown.enter="saveTitle"
                               class="flex-1 rounded-md border-neutral-300">
                        <button wire:click="saveTitle"
                                class="rounded-md bg-primary-600 px-3 py-2 text-sm text-white">
                            Guardar
                        </button>
                    </div>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @else
                    <div class="flex items-center gap-2">
                        <h2 class="text-lg font-semibold">{{ $title }}</h2>
                        <button wire:click="$set('editingTitle', true)"
                                class="text-neutral-400 hover:text-neutral-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                    </div>
                @endif
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <p class="text-neutral-700 dark:text-neutral-300">
                    {{ $description }}
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <x-dialog-footer dialog-id="task-detail-dialog" />
    @else
        <div class="flex items-center justify-center h-full p-12">
            <p class="text-neutral-500">Cargando...</p>
        </div>
    @endif
</div>
```

### 3. Integrar en la Vista Principal

**Archivo:** `resources/views/tasks/kanban.blade.php` (al final)

```blade
{{-- Dialog Wrapper (FUERA de Livewire) --}}
<x-dialog-wrapper id="task-detail-dialog" max-width="2xl">
    @livewire('tasks.task-detail-dialog')
</x-dialog-wrapper>

@push('scripts')
<script>
    // Función para abrir el dialog
    function openTaskDetail(taskId) {
        // Cargar datos en el componente Livewire
        Livewire.dispatch('loadTask', { taskId: taskId });

        // Abrir el dialog
        window.DialogSystem.open('task-detail-dialog');
    }

    // Escuchar eventos de Livewire
    document.addEventListener('livewire:init', () => {
        Livewire.on('task-updated', (data) => {
            console.log('Task updated:', data.taskId);
            // Refrescar la vista si es necesario
        });
    });
</script>
@endpush
```

### 4. Llamar desde HTML

```blade
{{-- Desde un botón --}}
<button onclick="openTaskDetail({{ $task->id }})"
        class="...">
    Ver Detalle
</button>

{{-- Desde un card con Alpine.js --}}
<div @click="openTaskDetail({{ $task->id }})"
     class="cursor-pointer ...">
    <h3>{{ $task->title }}</h3>
</div>
```

---

## 🎯 API del Dialog System

### JavaScript Global

```javascript
// Abrir dialog
window.DialogSystem.open('dialog-id');
// o
window.openDialog('dialog-id');

// Cerrar dialog
window.DialogSystem.close('dialog-id');
// o
window.closeDialog('dialog-id');

// Toggle dialog
window.DialogSystem.toggle('dialog-id');

// Verificar si está abierto
window.DialogSystem.isOpen('dialog-id');

// Cerrar todos los dialogs
window.DialogSystem.closeAll();
```

### Desde Livewire (PHP)

```php
// Abrir dialog
$this->dispatch('open-dialog', dialogId: 'my-dialog');

// Cerrar dialog
$this->dispatch('close-dialog', dialogId: 'my-dialog');

// Cerrar todos
$this->dispatch('close-all-dialogs');

// Enviar evento personalizado
$this->dispatch('task-updated', taskId: 123);
```

### Listeners en JavaScript

```javascript
document.addEventListener('livewire:init', () => {
    // Escuchar eventos custom
    Livewire.on('task-updated', (event) => {
        const taskId = event.taskId || event[0]?.taskId;
        console.log('Task updated:', taskId);
    });

    Livewire.on('show-toast', (event) => {
        alert(event.message);
    });
});
```

---

## 🔧 Componentes Disponibles

### `<x-dialog-wrapper>`

```blade
<x-dialog-wrapper
    id="my-dialog"           {{-- Required: ID único del dialog --}}
    max-width="2xl"          {{-- sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, full --}}
    slide-from="right">      {{-- right, left, top, bottom --}}

    @livewire('my-component')

</x-dialog-wrapper>
```

### `<x-dialog-header>`

```blade
<x-dialog-header
    title="Mi Dialog"           {{-- Required: Título --}}
    subtitle="Descripción"      {{-- Optional: Subtítulo --}}
    dialog-id="my-dialog">      {{-- Optional: Para el botón cerrar --}}

    {{-- Optional slot: description --}}
    <x-slot:description>
        <p>Contenido adicional del header</p>
    </x-slot:description>

    {{-- Optional slot: extra --}}
    <x-slot:extra>
        <div>Tabs, filtros, etc.</div>
    </x-slot:extra>
</x-dialog-header>
```

### `<x-dialog-footer>`

```blade
{{-- Botones por defecto --}}
<x-dialog-footer
    dialog-id="my-dialog"
    submit-action="save"
    cancel-text="Cancelar"
    submit-text="Guardar"
    :loading="$isLoading" />

{{-- Botones personalizados --}}
<x-dialog-footer align="between">
    <button wire:click="delete" class="...">Eliminar</button>
    <div class="flex gap-2">
        <button command="close" commandfor="my-dialog">Cancelar</button>
        <button wire:click="save">Guardar</button>
    </div>
</x-dialog-footer>
```

---

## ⚡ Trait WithDialog

Si tu componente necesita gestión de estado del dialog:

```php
use App\Livewire\Traits\WithDialog;

class MyDialog extends Component
{
    use WithDialog;

    public function save()
    {
        // Validar y guardar
        $this->validate();

        // Cerrar y refrescar
        $this->closeAndRefresh();

        // O solo cerrar
        $this->dispatchCloseDialog('my-dialog');
    }

    // Override para reset personalizado
    protected function resetDialog(): void
    {
        $this->reset(['title', 'description', 'errors']);
    }
}
```

---

## ✨ Mejores Prácticas

### ✅ DO

- Mantener `el-dialog` **FUERA** del componente Livewire
- Usar listeners (`protected $listeners`) para cargar datos
- Dispatch eventos para comunicar cambios
- Usar `wire:loading` para estados de carga
- Validar en el servidor (Livewire)

### ❌ DON'T

- No poner `el-dialog` dentro del componente Livewire (se pierde estado)
- No usar AJAX manual (Livewire ya lo maneja)
- No olvidar limpiar el estado al cerrar (`resetDialog()`)
- No hacer queries pesadas en `mount()` (usar listeners)

---

## 🐛 Troubleshooting

### El dialog no se abre

1. Verificar que el ID del dialog coincide
2. Abrir consola del navegador: `DialogSystem.open('id')`
3. Verificar que `dialog-system.js` está cargado: `console.log(window.DialogSystem)`

### El dialog se cierra solo al hacer click

1. Verificar que `el-dialog` está **fuera** del componente Livewire
2. No debe haber re-renders que afecten el wrapper estático

### Los datos no se actualizan

1. Verificar que el listener está registrado: `protected $listeners = ['loadTask' => 'load']`
2. Verificar que el dispatch usa el nombre correcto: `Livewire.dispatch('loadTask', { taskId: 123 })`

---

## 📚 Próximos Pasos

- [ ] Implementar `TaskDetailDialog` (FASE 2)
- [ ] Implementar `CreateTaskDialog` (FASE 2)
- [ ] Extender a Áreas (FASE 3)
- [ ] Extender a Usuarios (FASE 3)

---

**Documentación Completa:** `docs/sprints/03.5-dialog-pattern-refactor.md`