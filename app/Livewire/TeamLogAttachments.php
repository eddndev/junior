<?php

namespace App\Livewire;

use Livewire\Component;

/**
 * TeamLogAttachments Livewire Component
 *
 * Maneja la gestión de enlaces externos para entradas de bitácora.
 * Los archivos se manejan vía formulario HTML tradicional para mejor compatibilidad.
 */
class TeamLogAttachments extends Component
{
    /**
     * Enlaces externos (videos, imágenes, URLs)
     */
    public $links = [];

    /**
     * Campo temporal para agregar nuevo enlace
     */
    public $newLinkUrl = '';
    public $newLinkType = 'external'; // external, video, image

    /**
     * Agregar enlace a la lista
     */
    public function addLink()
    {
        $this->validate([
            'newLinkUrl' => 'required|url|max:2048',
            'newLinkType' => 'required|in:external,video,image',
        ], [
            'newLinkUrl.required' => 'La URL es obligatoria.',
            'newLinkUrl.url' => 'Debes ingresar una URL válida.',
            'newLinkType.required' => 'Debes seleccionar un tipo de enlace.',
        ]);

        $this->links[] = [
            'url' => $this->newLinkUrl,
            'type' => $this->newLinkType,
            'id' => uniqid(), // ID temporal para poder eliminarlo
        ];

        // Limpiar campos
        $this->newLinkUrl = '';
        $this->newLinkType = 'external';

        $this->dispatch('link-added');
    }

    /**
     * Eliminar enlace de la lista
     */
    public function removeLink($linkId)
    {
        $this->links = array_filter($this->links, function ($link) use ($linkId) {
            return $link['id'] !== $linkId;
        });

        $this->links = array_values($this->links); // Re-indexar array

        // Dispatch evento para actualizar padding del composer
        $this->dispatch('attachments-updated');
    }

    /**
     * Resetear enlaces (llamado después de guardar si es necesario)
     */
    public function resetLinks()
    {
        $this->links = [];
        $this->newLinkUrl = '';
        $this->newLinkType = 'external';
    }

    public function render()
    {
        return view('livewire.team-log-attachments');
    }
}
