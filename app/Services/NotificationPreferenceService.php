<?php

namespace App\Services;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationPreferenceService
{
    /**
     * Default notification types with their categories.
     */
    protected array $defaultTypes = [
        'tasks' => [
            'task_assigned' => [
                'label' => 'Tarea asignada',
                'description' => 'Cuando te asignan una nueva tarea',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'task_completed' => [
                'label' => 'Tarea completada',
                'description' => 'Cuando una tarea que creaste es completada',
                'database_enabled' => true,
                'email_enabled' => false,
            ],
            'task_due_reminder' => [
                'label' => 'Recordatorio de fecha de vencimiento',
                'description' => 'Recordatorio antes de que venza una tarea',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'task_overdue' => [
                'label' => 'Tarea vencida',
                'description' => 'Cuando una tarea pasa de su fecha de vencimiento',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'task_comment' => [
                'label' => 'Comentario en tarea',
                'description' => 'Cuando alguien comenta en tu tarea',
                'database_enabled' => true,
                'email_enabled' => false,
            ],
        ],
        'calendar' => [
            'event_invitation' => [
                'label' => 'Invitacion a evento',
                'description' => 'Cuando te invitan a un evento',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'event_reminder' => [
                'label' => 'Recordatorio de evento',
                'description' => 'Recordatorio antes de un evento',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'event_updated' => [
                'label' => 'Evento actualizado',
                'description' => 'Cuando un evento al que asistes es modificado',
                'database_enabled' => true,
                'email_enabled' => false,
            ],
            'event_cancelled' => [
                'label' => 'Evento cancelado',
                'description' => 'Cuando se cancela un evento al que asistes',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
        ],
        'messages' => [
            'new_message' => [
                'label' => 'Nuevo mensaje',
                'description' => 'Cuando recibes un mensaje directo',
                'database_enabled' => true,
                'email_enabled' => false,
            ],
        ],
        'system' => [
            'system_announcement' => [
                'label' => 'Anuncio del sistema',
                'description' => 'Anuncios importantes del sistema',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
            'account_security' => [
                'label' => 'Seguridad de la cuenta',
                'description' => 'Alertas de seguridad de tu cuenta',
                'database_enabled' => true,
                'email_enabled' => true,
            ],
        ],
    ];

    /**
     * Get all notification type definitions.
     */
    public function getAllTypes(): array
    {
        return $this->defaultTypes;
    }

    /**
     * Get preferences for a specific notification type.
     */
    public function getPreferencesFor(User $user, string $type): ?NotificationPreference
    {
        $preference = NotificationPreference::where('user_id', $user->id)
            ->forType($type)
            ->first();

        if (!$preference) {
            // Return default settings from our configuration
            $defaults = $this->getDefaultForType($type);
            if ($defaults) {
                return new NotificationPreference([
                    'user_id' => $user->id,
                    'notification_type' => $type,
                    'database_enabled' => $defaults['database_enabled'] ?? true,
                    'email_enabled' => $defaults['email_enabled'] ?? true,
                    'push_enabled' => $defaults['push_enabled'] ?? false,
                    'settings' => [],
                ]);
            }
        }

        return $preference;
    }

    /**
     * Update a user's preference for a notification type.
     */
    public function updatePreference(User $user, string $type, array $settings): NotificationPreference
    {
        return NotificationPreference::updateOrCreate(
            [
                'user_id' => $user->id,
                'notification_type' => $type,
            ],
            [
                'database_enabled' => $settings['database_enabled'] ?? true,
                'email_enabled' => $settings['email_enabled'] ?? true,
                'push_enabled' => $settings['push_enabled'] ?? false,
                'settings' => $settings['settings'] ?? [],
            ]
        );
    }

    /**
     * Get all preferences for a user (with defaults for missing ones).
     */
    public function getAllPreferences(User $user): Collection
    {
        $userPreferences = NotificationPreference::where('user_id', $user->id)->get()
            ->keyBy('notification_type');

        $allPreferences = collect();

        foreach ($this->defaultTypes as $category => $types) {
            foreach ($types as $typeKey => $typeConfig) {
                if ($userPreferences->has($typeKey)) {
                    $preference = $userPreferences->get($typeKey);
                    $preference->category = $category;
                    $preference->label = $typeConfig['label'];
                    $preference->description = $typeConfig['description'];
                } else {
                    $preference = new NotificationPreference([
                        'user_id' => $user->id,
                        'notification_type' => $typeKey,
                        'database_enabled' => $typeConfig['database_enabled'] ?? true,
                        'email_enabled' => $typeConfig['email_enabled'] ?? true,
                        'push_enabled' => $typeConfig['push_enabled'] ?? false,
                        'settings' => [],
                    ]);
                    $preference->category = $category;
                    $preference->label = $typeConfig['label'];
                    $preference->description = $typeConfig['description'];
                }

                $allPreferences->push($preference);
            }
        }

        return $allPreferences;
    }

    /**
     * Get default settings for a notification type.
     */
    protected function getDefaultForType(string $type): ?array
    {
        foreach ($this->defaultTypes as $category => $types) {
            if (isset($types[$type])) {
                return $types[$type];
            }
        }

        return null;
    }

    /**
     * Check if database notifications are enabled for a user and type.
     */
    public function isDatabaseEnabled(User $user, string $type): bool
    {
        $preference = $this->getPreferencesFor($user, $type);
        return $preference ? $preference->database_enabled : true;
    }

    /**
     * Check if email notifications are enabled for a user and type.
     */
    public function isEmailEnabled(User $user, string $type): bool
    {
        $preference = $this->getPreferencesFor($user, $type);
        return $preference ? $preference->email_enabled : true;
    }

    /**
     * Check if push notifications are enabled for a user and type.
     */
    public function isPushEnabled(User $user, string $type): bool
    {
        $preference = $this->getPreferencesFor($user, $type);
        return $preference ? $preference->push_enabled : false;
    }

    /**
     * Get category labels in Spanish.
     */
    public function getCategoryLabels(): array
    {
        return [
            'tasks' => 'Tareas',
            'calendar' => 'Calendario',
            'messages' => 'Mensajes',
            'system' => 'Sistema',
        ];
    }
}
