<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_one_id',
        'user_two_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the first user in the conversation.
     */
    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    /**
     * Get the second user in the conversation.
     */
    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    /**
     * Get all messages in this conversation.
     */
    public function messages(): HasMany
    {
        return $this->hasMany(DirectMessage::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the last message in the conversation.
     */
    public function lastMessage(): HasOne
    {
        return $this->hasOne(DirectMessage::class)->latestOfMany();
    }

    /**
     * Get the other user in the conversation.
     */
    public function getOtherUser(User $user): User
    {
        return $this->user_one_id === $user->id ? $this->userTwo : $this->userOne;
    }

    /**
     * Get unread messages count for a user.
     */
    public function unreadCountFor(User $user): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Mark all messages as read for a user.
     */
    public function markAsReadFor(User $user): void
    {
        $this->messages()
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Find or create a conversation between two users.
     */
    public static function findOrCreateBetween(User $userOne, User $userTwo): self
    {
        // Always store the lower ID first to avoid duplicates
        $ids = [$userOne->id, $userTwo->id];
        sort($ids);

        return static::firstOrCreate([
            'user_one_id' => $ids[0],
            'user_two_id' => $ids[1],
        ]);
    }

    /**
     * Scope to get conversations for a user.
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_one_id', $user->id)
            ->orWhere('user_two_id', $user->id);
    }

    /**
     * Scope to order by last message.
     */
    public function scopeLatestMessage($query)
    {
        return $query->orderByDesc('last_message_at');
    }
}
