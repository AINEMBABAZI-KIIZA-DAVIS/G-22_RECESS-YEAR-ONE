<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get contacts based on user role
        if ($user->role == 'admin') {
            $contacts = User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        } else {
            $contacts = User::where('role', 'admin')
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        }

        return view('admin.chat.index', compact('contacts'));
    }

    public function chatWithUser($userId)
    {
        return redirect('/chatify');
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Broadcast the message event (optional for real-time features)
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Exception $e) {
            // Log error but don't break the chat functionality
            \Log::error('Failed to broadcast message: ' . $e->getMessage());
        }

        // Redirect back to the chat with the same user
        return redirect()->route('admin.chat.with', $request->receiver_id)
            ->with('success', 'Message sent successfully!');
    }

    public function chatWithSupplier()
    {
        return redirect('/chatify');
    }

    public function chatWithWholesaler()
    {
        return redirect('/chatify');
    }

    public function chatWithVendor()
    {
        return redirect('/chatify');
    }

    public function chatWithAdmin()
    {
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        
        if ($user->role == 'admin') {
            $contacts = User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        } else {
            $contacts = User::where('role', 'admin')
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        }

        // Check if user is wholesaler and return wholesaler-specific view
        if ($user->role == 'wholesaler') {
            return view('wholesaler.chat.admin', compact('admins', 'contacts'));
        }

        return view('admin.chat.admin', compact('admins', 'contacts'));
    }

    public function wholesalerChatWithAdmin()
    {
        return redirect('/chatify');
    }

    public function supplierChatWithAdmin()
    {
        return redirect('/chatify');
    }

    public function vendorChatWithAdmin()
    {
        return redirect('/chatify');
    }

    public function chatWithSupplierAdmin()
    {
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        
        if ($user->role == 'admin') {
            $contacts = User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        } else {
            $contacts = User::where('role', 'admin')
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        }

        return view('admin.chat.supplier-admin', compact('admins', 'contacts'));
    }

    public function chatWithVendorAdmin()
    {
        $user = Auth::user();
        $admins = User::where('role', 'admin')->get();
        
        if ($user->role == 'admin') {
            $contacts = User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        } else {
            $contacts = User::where('role', 'admin')
                ->select('users.*')
                ->selectRaw('(
                    SELECT COUNT(*) 
                    FROM messages 
                    WHERE messages.sender_id = users.id 
                      AND messages.receiver_id = ? 
                      AND messages.is_read = 0
                ) as unread', [$user->id])
                ->get();
        }

        return view('admin.chat.vendor-admin', compact('admins', 'contacts'));
    }

    public function sentMessages()
    {
        $user = Auth::user();
        $sentMessages = Message::where('sender_id', $user->id)
            ->with('receiver')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.chat.sent', compact('sentMessages'));
    }

    // API methods for AJAX (if needed later)
    public function getMessages($userId)
    {
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender_id', $userId)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json(['messages' => $messages]);
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id'
        ]);

        Message::where('sender_id', $request->sender_id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['status' => 'success']);
    }

    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->count();
        return response()->json(['count' => $count]);
    }

    public function getContacts()
    {
        $user = Auth::user();
        
        if ($user->role == 'admin') {
            $contacts = User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])
                ->select('id', 'name', 'role', 'avatar')
                ->get();
        } else {
            $contacts = User::where('role', 'admin')
                ->select('id', 'name', 'role', 'avatar')
                ->get();
        }

        return response()->json(['contacts' => $contacts]);
    }
}
