<?php

namespace App\Livewire\Profile;

use App\Services\NotificationPreferenceService;
use Livewire\Attributes\Computed;
use Livewire\Component;

class NotificationPreferences extends Component
{
    public array $preferences = [];
    public bool $showSuccess = false;

    protected NotificationPreferenceService $preferenceService;

    public function boot(NotificationPreferenceService $preferenceService): void
    {
        $this->preferenceService = $preferenceService;
    }

    public function mount(): void
    {
        $this->loadPreferences();
    }

    protected function loadPreferences(): void
    {
        $user = auth()->user();
        $allPreferences = $this->preferenceService->getAllPreferences($user);

        $this->preferences = [];

        foreach ($allPreferences as $preference) {
            $this->preferences[$preference->notification_type] = [
                'database_enabled' => $preference->database_enabled,
                'email_enabled' => $preference->email_enabled,
                'push_enabled' => $preference->push_enabled,
            ];
        }
    }

    public function togglePreference(string $type, string $channel): void
    {
        if (!isset($this->preferences[$type])) {
            return;
        }

        $channelKey = $channel . '_enabled';
        if (!isset($this->preferences[$type][$channelKey])) {
            return;
        }

        $this->preferences[$type][$channelKey] = !$this->preferences[$type][$channelKey];

        $user = auth()->user();
        $this->preferenceService->updatePreference($user, $type, $this->preferences[$type]);

        $this->showSuccess = true;
        $this->dispatch('preferenceUpdated');
    }

    #[Computed]
    public function groupedPreferences()
    {
        $user = auth()->user();
        $allPreferences = $this->preferenceService->getAllPreferences($user);

        $grouped = [];
        foreach ($allPreferences as $preference) {
            $category = $preference->category;
            if (!isset($grouped[$category])) {
                $grouped[$category] = [];
            }

            // Update with current state from our local preferences
            if (isset($this->preferences[$preference->notification_type])) {
                $preference->database_enabled = $this->preferences[$preference->notification_type]['database_enabled'];
                $preference->email_enabled = $this->preferences[$preference->notification_type]['email_enabled'];
                $preference->push_enabled = $this->preferences[$preference->notification_type]['push_enabled'];
            }

            $grouped[$category][] = $preference;
        }

        return $grouped;
    }

    #[Computed]
    public function categoryLabels()
    {
        return $this->preferenceService->getCategoryLabels();
    }

    public function render()
    {
        return view('livewire.profile.notification-preferences');
    }
}
