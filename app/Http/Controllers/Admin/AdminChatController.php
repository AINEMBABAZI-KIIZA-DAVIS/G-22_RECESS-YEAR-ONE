<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminChatController extends Controller
{
    // Display the admin chat dashboard
    public function index()
    {
        $user = auth()->user();
        // Get contacts for admin: all wholesalers, suppliers, vendors, with unread count
        $contacts = \App\Models\User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
            ->select('users.*')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM messages 
                WHERE messages.sender_id = users.id 
                  AND messages.receiver_id = ? 
                  AND messages.is_read = 0
            ) as unread', [$user->id])
            ->get();
        return view('admin.chat.index', compact('contacts'));
    }

    // Send a message as admin
    public function sendMessage(Request $request)
    {
        // Validate and handle sending message logic here
        // ...
        return response()->json(['status' => 'Message sent']);
    }
} 