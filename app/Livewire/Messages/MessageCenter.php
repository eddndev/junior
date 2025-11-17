<?php

namespace App\Livewire\Messages;

use App\Models\Conversation;
use App\Models\DirectMessage;
use App\Models\User;
use Livewire\Component;

class MessageCenter extends Component
{
    public ?Conversation $selectedConversation = null;
    public string $newMessage = '';
    public string $searchUsers = '';
    public ?int $selectedUserId = null;

    protected $listeners = [
        'conversationSelected' => 'selectConversation',
        'refreshMessages' => '$refresh',
    ];

    public function mount(?int $conversationId = null): void
    {
        if ($conversationId) {
            $this->selectConversation($conversationId);
        }
    }

    public function selectConversation(int $conversationId): void
    {
        $user = auth()->user();
        $conversation = Conversation::forUser($user)->find($conversationId);

        if ($conversation) {
            $this->selectedConversation = $conversation;
            $this->selectedConversation->markAsReadFor($user);
            $this->selectedUserId = null;
            $this->searchUsers = '';
        }
    }

    public function startNewConversation(): void
    {
        $this->selectedConversation = null;
        $this->selectedUserId = null;
        $this->searchUsers = '';
        $this->newMessage = '';
    }

    public function selectUser(int $userId): void
    {
        $user = auth()->user();
        $otherUser = User::find($userId);

        if ($otherUser && $otherUser->id !== $user->id) {
            $this->selectedUserId = $userId;
            $this->searchUsers = $otherUser->name;

            // Check if conversation already exists
            $existingConversation = Conversation::forUser($user)
                ->where(function ($q) use ($user, $otherUser) {
                    $q->where(function ($q2) use ($user, $otherUser) {
                        $q2->where('user_one_id', $user->id)
                            ->where('user_two_id', $otherUser->id);
                    })->orWhere(function ($q2) use ($user, $otherUser) {
                        $q2->where('user_one_id', $otherUser->id)
                            ->where('user_two_id', $user->id);
                    });
                })
                ->first();

            if ($existingConversation) {
                $this->selectConversation($existingConversation->id);
            }
        }
    }

    public function sendMessage(): void
    {
        $this->validate([
            'newMessage' => 'required|string|max:5000',
        ]);

        $user = auth()->user();

        // If we have a selected conversation, send to it
        if ($this->selectedConversation) {
            DirectMessage::create([
                'conversation_id' => $this->selectedConversation->id,
                'sender_id' => $user->id,
                'body' => $this->newMessage,
            ]);

            $this->selectedConversation->refresh();
        }
        // If we have a selected user but no conversation, create one
        elseif ($this->selectedUserId) {
            $otherUser = User::find($this->selectedUserId);

            if ($otherUser) {
                $conversation = Conversation::findOrCreateBetween($user, $otherUser);

                DirectMessage::create([
                    'conversation_id' => $conversation->id,
                    'sender_id' => $user->id,
                    'body' => $this->newMessage,
                ]);

                $this->selectedConversation = $conversation;
                $this->selectedUserId = null;
            }
        }

        $this->newMessage = '';
        $this->dispatch('messageSent');
    }

    public function getConversationsProperty()
    {
        return Conversation::forUser(auth()->user())
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->latestMessage()
            ->get();
    }

    public function getSearchResultsProperty()
    {
        if (strlen($this->searchUsers) < 2) {
            return collect();
        }

        return User::where('id', '!=', auth()->id())
            ->where('is_active', true)
            ->where(function ($q) {
                $q->where('name', 'like', '%' . $this->searchUsers . '%')
                    ->orWhere('email', 'like', '%' . $this->searchUsers . '%');
            })
            ->limit(10)
            ->get();
    }

    public function getTotalUnreadProperty(): int
    {
        $user = auth()->user();
        $total = 0;

        foreach ($this->conversations as $conversation) {
            $total += $conversation->unreadCountFor($user);
        }

        return $total;
    }

    public function render()
    {
        return view('livewire.messages.message-center');
    }
}
