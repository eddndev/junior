/**
 * Dialog System
 *
 * Sistema global para manejar la apertura y cierre de dialogs desde Livewire.
 * Este sistema mantiene el estado de los dialogs fuera del ciclo de re-render de Livewire,
 * evitando problemas de sincronización entre el DOM y el estado del componente.
 */

window.DialogSystem = {
    /**
     * Abre un dialog por su ID.
     *
     * @param {string} dialogId - El ID del elemento dialog
     */
    open(dialogId) {
        const dialog = document.getElementById(dialogId);

        if (!dialog) {
            console.error(`[DialogSystem] Dialog with id "${dialogId}" not found`);
            return;
        }

        // Verificar si el dialog ya está abierto
        if (dialog.open) {
            console.log(`[DialogSystem] Dialog "${dialogId}" is already open`);
            return;
        }

        if (typeof dialog.showModal === 'function') {
            try {
                dialog.showModal();
                console.log(`[DialogSystem] Opened dialog: ${dialogId}`);
            } catch (error) {
                console.error(`[DialogSystem] Error opening dialog "${dialogId}":`, error);
            }
        } else {
            console.error(`[DialogSystem] Dialog "${dialogId}" does not support showModal()`);
        }
    },

    /**
     * Cierra un dialog por su ID.
     *
     * @param {string} dialogId - El ID del elemento dialog
     */
    close(dialogId) {
        const dialog = document.getElementById(dialogId);

        if (!dialog) {
            console.error(`[DialogSystem] Dialog with id "${dialogId}" not found`);
            return;
        }

        if (typeof dialog.close === 'function') {
            try {
                dialog.close();
                console.log(`[DialogSystem] Closed dialog: ${dialogId}`);

                // Emit custom event for other listeners
                const event = new CustomEvent('dialog-closed', {
                    detail: { dialogId: dialogId },
                    bubbles: true,
                    cancelable: false
                });
                document.dispatchEvent(event);
            } catch (error) {
                console.error(`[DialogSystem] Error closing dialog "${dialogId}":`, error);
            }
        } else {
            console.error(`[DialogSystem] Dialog "${dialogId}" does not support close()`);
        }
    },

    /**
     * Verifica si un dialog está abierto.
     *
     * @param {string} dialogId - El ID del elemento dialog
     * @returns {boolean}
     */
    isOpen(dialogId) {
        const dialog = document.getElementById(dialogId);
        return dialog ? dialog.open : false;
    },

    /**
     * Toggle de un dialog (abrir si está cerrado, cerrar si está abierto).
     *
     * @param {string} dialogId - El ID del elemento dialog
     */
    toggle(dialogId) {
        if (this.isOpen(dialogId)) {
            this.close(dialogId);
        } else {
            this.open(dialogId);
        }
    },

    /**
     * Cierra todos los dialogs abiertos.
     */
    closeAll() {
        const dialogs = document.querySelectorAll('dialog[open]');
        dialogs.forEach(dialog => {
            if (dialog.id) {
                this.close(dialog.id);
            }
        });
    }
};

/**
 * Inicialización del sistema de eventos Livewire
 */
document.addEventListener('livewire:init', () => {
    console.log('[DialogSystem] Initializing Livewire event listeners');

    /**
     * Evento: open-dialog
     * Escucha cuando Livewire dispara un evento para abrir un dialog
     *
     * Uso desde Livewire:
     * $this->dispatch('open-dialog', dialogId: 'my-dialog');
     */
    Livewire.on('open-dialog', (event) => {
        const dialogId = event.dialogId || event[0]?.dialogId;

        if (dialogId) {
            window.DialogSystem.open(dialogId);
        } else {
            console.error('[DialogSystem] open-dialog event received without dialogId');
        }
    });

    /**
     * Evento: close-dialog
     * Escucha cuando Livewire dispara un evento para cerrar un dialog
     *
     * Uso desde Livewire:
     * $this->dispatch('close-dialog', dialogId: 'my-dialog');
     */
    Livewire.on('close-dialog', (event) => {
        const dialogId = event.dialogId || event[0]?.dialogId;

        if (dialogId) {
            window.DialogSystem.close(dialogId);
        } else {
            console.error('[DialogSystem] close-dialog event received without dialogId');
        }
    });

    /**
     * Evento: close-all-dialogs
     * Cierra todos los dialogs abiertos
     *
     * Uso desde Livewire:
     * $this->dispatch('close-all-dialogs');
     */
    Livewire.on('close-all-dialogs', () => {
        window.DialogSystem.closeAll();
    });

    console.log('[DialogSystem] Event listeners registered successfully');
});

/**
 * Manejo de la tecla Escape para cerrar dialogs
 */
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        const openDialogs = document.querySelectorAll('dialog[open]');

        // Cerrar solo el último dialog abierto (el que está más arriba en el z-index)
        if (openDialogs.length > 0) {
            const lastDialog = openDialogs[openDialogs.length - 1];
            if (lastDialog.id) {
                window.DialogSystem.close(lastDialog.id);
            }
        }
    }
});

/**
 * Funciones helper globales para facilitar el uso desde HTML
 */
window.openDialog = (dialogId) => window.DialogSystem.open(dialogId);
window.closeDialog = (dialogId) => window.DialogSystem.close(dialogId);
window.toggleDialog = (dialogId) => window.DialogSystem.toggle(dialogId);

console.log('[DialogSystem] Global helpers registered: openDialog(), closeDialog(), toggleDialog()');

export default window.DialogSystem;
