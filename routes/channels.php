<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('notifications', function ($user) {
    return Auth::check();
});

Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
    // Check if the user is part of the conversation
    $conversation = \App\Models\Conversation::find($conversationId);

    if (!$conversation) {
        return false; // Conversation doesn't exist
    }

    // Check if the user is part of the conversation (admin or participant)
    return $conversation->participants->contains('user_id', $user->id) || $user->is_admin;
});

