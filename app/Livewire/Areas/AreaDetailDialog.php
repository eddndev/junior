<?php

namespace App\Livewire\Areas;

use App\Models\Area;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class AreaDetailDialog extends Component
{
    use AuthorizesRequests;

    public ?int $areaId = null;
    public ?Area $area = null;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadArea' => 'load',
    ];

    /**
     * Load area data
     *
     * @param int $areaId
     * @return void
     */
    public function load(int $areaId): void
    {
        $this->areaId = $areaId;

        try {
            $this->area = Area::with(['users', 'tasks'])
                ->findOrFail($areaId);

            // Authorization check (optional)
            // $this->authorize('view', $this->area);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Área no encontrada', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'area-detail-dialog');
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.areas.area-detail-dialog');
    }
}
