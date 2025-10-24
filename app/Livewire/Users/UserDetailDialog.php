<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class UserDetailDialog extends Component
{
    use AuthorizesRequests;

    public ?int $userId = null;
    public ?User $user = null;

    /**
     * Livewire listeners
     */
    protected $listeners = [
        'loadUser' => 'load',
    ];

    /**
     * Load user data
     *
     * @param int $userId
     * @return void
     */
    public function load(int $userId): void
    {
        $this->userId = $userId;

        try {
            $this->user = User::with([
                'roles',
                'areas',
            ])->withTrashed()->findOrFail($userId);

            // Authorization check (optional, can be removed if all users can view other users)
            // $this->authorize('view', $this->user);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $this->dispatch('show-toast', message: 'Usuario no encontrado', type: 'error');
            $this->dispatch('close-dialog', dialogId: 'user-detail-dialog');
        }
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('livewire.users.user-detail-dialog');
    }
}
