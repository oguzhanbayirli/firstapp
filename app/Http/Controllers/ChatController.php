<?php

namespace App\Http\Controllers;

use App\Events\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Send a chat message to all connected users
     */
    public function sendMessage(Request $request)
    {
        $formFields = $request->validate([
            'message' => 'required|string|max:1000',
        ]);
        
        // Ignore empty messages (whitespace only)
        if (!trim($formFields['message'])) {
            return response()->noContent();
        }
        
        // Broadcast the message to all users except the sender
        broadcast(new ChatMessage(
            Auth::user()->username,
            strip_tags($formFields['message']),
            Auth::user()->avatar
        ))->toOthers();
        
        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully'
        ]);
    }
}
