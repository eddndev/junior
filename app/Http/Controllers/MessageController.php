<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display the messaging center.
     */
    public function index(Request $request): View
    {
        $conversationId = $request->get('conversation');

        return view('messages.index', [
            'conversationId' => $conversationId,
        ]);
    }

    /**
     * Start a conversation with a specific user.
     */
    public function startWith(int $userId)
    {
        $user = auth()->user();
        $otherUser = \App\Models\User::findOrFail($userId);

        // Find or create conversation
        $conversation = Conversation::findOrCreateBetween($user, $otherUser);

        return redirect()->route('messages.index', ['conversation' => $conversation->id]);
    }
}
