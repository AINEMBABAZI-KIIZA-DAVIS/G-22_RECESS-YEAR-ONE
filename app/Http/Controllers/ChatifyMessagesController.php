<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class ChatifyMessagesController extends Controller
{
    /**
     * Return contacts for Chatify sidebar:
     * - Admin: all suppliers, wholesalers, vendors (grouped by role)
     * - Vendor/Supplier/Wholesaler: all admins
     * Ensure all users are shown even if no chats exist.
     */
    public function getContacts(Request $request)
    {
        $user = Auth::user();
        $contacts = collect();
        if ($user->role === 'admin') {
            $contacts = User::whereIn('role', ['supplier', 'wholesaler', 'vendor'])->get();
        } else if (in_array($user->role, ['vendor', 'supplier', 'wholesaler'])) {
            $contacts = User::where('role', 'admin')->get();
        }
        // Prepare users array for the Blade view
        $users = $contacts->map(function ($contact) {
            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'avatar' => $contact->avatar,
                'role' => $contact->role,
                'email' => $contact->email,
                'group' => ucfirst($contact->role) . 's',
            ];
        })->toArray();
        $html = view('vendor.Chatify.layouts.listItem', ['get' => 'users', 'users' => $users])->render();
        return response()->json(['contacts' => $html]);
    }

    /**
     * New: Return all users for Chatify getUsers endpoint (for sidebar)
     */
    public function getUsers(Request $request)
    {
        return $this->getContacts($request);
    }
} 