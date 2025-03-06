<?php

use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

// Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
//     return (int) $user->id === (int) $id;
// });

// Broadcast::channel('notifications', function ($user) {
//     return Auth::check();
// });

// // Broadcast::channel('conversation.{conversationId}', function ($user, $conversationId) {
// //     // Check if the user is part of the conversation
// //     $conversation = \App\Models\Conversation::find($conversationId);

// //     if (!$conversation) {
// //         return false; // Conversation doesn't exist
// //     }

// //     // Check if the user is part of the conversation (admin or participant)
// //     return $conversation->participants->contains('user_id', $user->id) || $user->is_admin;
// // });

// // Add this new channel for tickets using UUID instead of ID for better security
// Broadcast::channel('ticket.{uuid}', function ($user, $uuid) {
//     // Remove the experimental code and use a clean approach
//     $ticket = Ticket::where('uuid', $uuid)->first();
    
//     if (!$ticket) {
//         return false;
//     }
    
//     // For admin users
//     if (Auth::guard('admin')->check()) {
//         return true;
//     }
    
//     // For regular users - check if they're the creator or participant
//     return $ticket->created_by == $user->id || 
//            $ticket->participants->contains('user_id', $user->id);
// });

// // Keep the existing 'tickets' channel for general ticket notifications
// Broadcast::channel('tickets', function ($user) {
//     // Only admins can listen to all tickets
//     return Auth::guard('admin')->check();
// });

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return [
        'id' => $user->id ?? rand(1000, 9999),
        'name' => $user->name ?? 'Guest User'
    ];
    // return (int) $user->id === (int) $id;
});

Broadcast::channel('my-channel', function ($data) {
    return true;
});
Broadcast::channel('channel_for_everyone', function ($user) {
    return true;
});
Broadcast::channel('notifications', function ($user) {
    return Auth::check();
});

Broadcast::channel('ticket.{uuid}', function ($user, $uuid) {
    $ticket = Ticket::where('uuid', $uuid)->first();
    
    if (!$ticket) {
        return false;
    }
    
    // Check if regular user owns the ticket
    if ($user && $ticket->created_by == $user->id) {
        return true;
    }
    
    // Check if admin user
    if (Auth::guard('admin')->check()) {
        return true;
    }
    
    return false;
});

// // Admin-only channel
Broadcast::channel('tickets', function ($user) {
    return [
        'id' => $user->id ?? rand(1000, 9999),
        'name' => $user->name ?? 'Guest User'
    ];
    // return Auth::guard('admin')->check();
});
