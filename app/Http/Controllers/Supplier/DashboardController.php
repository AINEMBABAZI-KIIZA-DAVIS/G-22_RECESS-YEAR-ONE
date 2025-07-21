<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $pendingRequestsCount = SupplierRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
            
        $confirmedRequestsCount = SupplierRequest::where('user_id', $user->id)
            ->where('status', 'confirmed_by_manufacturer')
            ->count();
            
        $fulfilledRequestsCount = SupplierRequest::where('user_id', $user->id)
            ->where('status', 'fulfilled')
            ->count();
            
        $recentRequests = SupplierRequest::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('supplier.dashboard', [
            'pendingRequestsCount' => $pendingRequestsCount,
            'confirmedRequestsCount' => $confirmedRequestsCount,
            'fulfilledRequestsCount' => $fulfilledRequestsCount,
            'recentRequests' => $recentRequests,
        ]);
    }
}