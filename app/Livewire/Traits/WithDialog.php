<?php

namespace App\Livewire\Traits;

trait WithDialog
{
    /**
     * Indicates if the dialog is open.
     *
     * @var bool
     */
    public bool $isOpen = false;

    /**
     * Open the dialog.
     *
     * @return void
     */
    public function openDialog(): void
    {
        $this->isOpen = true;
    }

    /**
     * Close the dialog.
     *
     * @return void
     */
    public function closeDialog(): void
    {
        $this->isOpen = false;
        $this->resetDialog();
    }

    /**
     * Close the dialog and dispatch a refresh event.
     *
     * @return void
     */
    public function closeAndRefresh(): void
    {
        $this->closeDialog();
        $this->dispatch('refresh-list');
    }

    /**
     * Reset the dialog state.
     * Override this method in your component to reset specific properties.
     *
     * @return void
     */
    protected function resetDialog(): void
    {
        // Default implementation - override in component if needed
        // Example: $this->reset(['title', 'description']);
    }

    /**
     * Dispatch an event to close the dialog from JavaScript.
     *
     * @param string $dialogId
     * @return void
     */
    public function dispatchCloseDialog(string $dialogId): void
    {
        $this->dispatch('close-dialog', dialogId: $dialogId);
    }

    /**
     * Dispatch an event to open the dialog from JavaScript.
     *
     * @param string $dialogId
     * @return void
     */
    public function dispatchOpenDialog(string $dialogId): void
    {
        $this->dispatch('open-dialog', dialogId: $dialogId);
    }
}
