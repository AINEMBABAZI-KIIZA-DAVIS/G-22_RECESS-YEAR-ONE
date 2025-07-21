<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class FetchChatifyUsers
{
    /**
     * Handle the event.
     *
     * @param  mixed  $event
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function handle($event)
    {
        $user = Auth::user();
        if ($user && $user->role !== 'admin') {
            // Return all admins for non-admins
            return User::where('role', 'admin')->get();
        }
        // For admin, return all non-admins
        return User::whereIn('role', ['wholesaler', 'supplier', 'vendor'])->get();
    }
} 