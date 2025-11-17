<?php

namespace App\Observers;

use App\Models\DirectMessage;
use App\Notifications\Messages\NewDirectMessageNotification;

/**
 * DirectMessageNotificationObserver
 *
 * Observes DirectMessage model changes and triggers notifications.
 */
class DirectMessageNotificationObserver
{
    /**
     * Handle the DirectMessage "created" event.
     *
     * Sends notification to the recipient of the message.
     */
    public function created(DirectMessage $message): void
    {
        $conversation = $message->conversation;

        if (!$conversation) {
            return;
        }

        // Determine the recipient (the other user in the conversation)
        $senderId = $message->sender_id;
        $recipientId = null;

        if ($conversation->user_one_id === $senderId) {
            $recipientId = $conversation->user_two_id;
        } else {
            $recipientId = $conversation->user_one_id;
        }

        if (!$recipientId) {
            return;
        }

        $recipient = \App\Models\User::find($recipientId);

        if (!$recipient) {
            return;
        }

        // Send the new message notification
        $recipient->notify(new NewDirectMessageNotification($message));
    }
}
