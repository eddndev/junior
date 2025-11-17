<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateAvatar extends Component
{
    use WithFileUploads;

    public $avatar;
    public $currentAvatar;

    public function mount()
    {
        $this->currentAvatar = auth()->user()->avatar_url;
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'image|max:1024|mimes:jpg,jpeg,png,gif', // 1MB max
        ]);
    }

    public function save()
    {
        $this->validate([
            'avatar' => 'required|image|max:1024|mimes:jpg,jpeg,png,gif',
        ]);

        $user = auth()->user();

        // Delete old avatar if exists
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Store new avatar
        $path = $this->avatar->store('avatars', 'public');

        // Update user
        $user->update(['avatar_path' => $path]);

        $this->currentAvatar = $user->avatar_url;
        $this->avatar = null;

        session()->flash('avatar-updated', __('Avatar actualizado correctamente.'));

        $this->dispatch('avatar-updated');
    }

    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $user->update(['avatar_path' => null]);
        }

        $this->currentAvatar = $user->avatar_url;

        session()->flash('avatar-updated', __('Avatar eliminado correctamente.'));

        $this->dispatch('avatar-updated');
    }

    public function render()
    {
        return view('livewire.profile.update-avatar');
    }
}
