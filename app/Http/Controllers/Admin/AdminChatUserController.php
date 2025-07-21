<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminChatUserController extends Controller
{
    // Show chat with a wholesaler
    public function wholesaler($wholesalerId)
    {
        $admin = auth()->user();
        $contacts = \App\Models\User::where('role', 'wholesaler')
            ->select('users.*')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM messages 
                WHERE messages.sender_id = users.id 
                  AND messages.receiver_id = ? 
                  AND messages.is_read = 0
            ) as unread', [$admin->id])
            ->get();

        $selectedUser = \App\Models\User::findOrFail($wholesalerId);

        $messages = \App\Models\Message::where(function ($query) use ($admin, $wholesalerId) {
                $query->where('sender_id', $admin->id)
                      ->where('receiver_id', $wholesalerId);
            })->orWhere(function ($query) use ($admin, $wholesalerId) {
                $query->where('sender_id', $wholesalerId)
                      ->where('receiver_id', $admin->id);
            })->orderBy('created_at', 'asc')->get();

        return view('admin.chat.chat', compact('contacts', 'selectedUser', 'messages'));
    }

    // Show chat with a supplier
    public function supplier($supplierId)
    {
        $admin = auth()->user();
        $contacts = \App\Models\User::where('role', 'supplier')
            ->select('users.*')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM messages 
                WHERE messages.sender_id = users.id 
                  AND messages.receiver_id = ? 
                  AND messages.is_read = 0
            ) as unread', [$admin->id])
            ->get();

        $selectedUser = \App\Models\User::findOrFail($supplierId);

        $messages = \App\Models\Message::where(function ($query) use ($admin, $supplierId) {
                $query->where('sender_id', $admin->id)
                      ->where('receiver_id', $supplierId);
            })->orWhere(function ($query) use ($admin, $supplierId) {
                $query->where('sender_id', $supplierId)
                      ->where('receiver_id', $admin->id);
            })->orderBy('created_at', 'asc')->get();

        return view('admin.chat.chat', compact('contacts', 'selectedUser', 'messages'));
    }

    // Show chat with a vendor
    public function vendor($vendorId)
    {
        $admin = auth()->user();
        $contacts = \App\Models\User::where('role', 'vendor')
            ->select('users.*')
            ->selectRaw('(
                SELECT COUNT(*) 
                FROM messages 
                WHERE messages.sender_id = users.id 
                  AND messages.receiver_id = ? 
                  AND messages.is_read = 0
            ) as unread', [$admin->id])
            ->get();

        $selectedUser = \App\Models\User::findOrFail($vendorId);

        $messages = \App\Models\Message::where(function ($query) use ($admin, $vendorId) {
                $query->where('sender_id', $admin->id)
                      ->where('receiver_id', $vendorId);
            })->orWhere(function ($query) use ($admin, $vendorId) {
                $query->where('sender_id', $vendorId)
                      ->where('receiver_id', $admin->id);
            })->orderBy('created_at', 'asc')->get();

        return view('admin.chat.chat', compact('contacts', 'selectedUser', 'messages'));
    }

    // Send message to a user (wholesaler, supplier, vendor)
    public function sendMessage(Request $request, $userType, $userId)
    {
        // Validate and handle sending message logic here
        // ...
        return response()->json(['status' => 'Message sent', 'userType' => $userType, 'userId' => $userId]);
    }
} 