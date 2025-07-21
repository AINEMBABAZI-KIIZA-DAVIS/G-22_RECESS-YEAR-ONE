<?php

namespace App\Http\Controllers;

use App\Models\SupplierRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierDashboardController extends Controller
{
    /**
     * Display the main supplier dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get counts for the dashboard cards
        $pendingRequestsCount = SupplierRequest::where('user_id', $user->id)
                                           ->where('status', 'pending')
                                           ->count();
                                           
        $confirmedRequestsCount = SupplierRequest::where('user_id', $user->id)
                                             ->where('status', 'approved')
                                             ->count();
                                             
        $fulfilledRequestsCount = SupplierRequest::where('user_id', $user->id)
                                             ->where('status', 'fulfilled')
                                             ->count();
        
        // Get the latest requests for the activity feed
        $recentRequests = SupplierRequest::where('user_id', $user->id)
                                     ->latest()
                                     ->take(5)
                                     ->get();

        return view('supplier.dashboard', [
            'pendingRequestsCount' => $pendingRequestsCount,
            'confirmedRequestsCount' => $confirmedRequestsCount,
            'fulfilledRequestsCount' => $fulfilledRequestsCount,
            'recentRequests' => $recentRequests,
            'user' => $user
        ]);
    }
    
    /**
     * Display a listing of the supplier's requests.
     */
    public function indexRequests(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status');
        
        $query = SupplierRequest::where('user_id', $user->id);
        
        if ($status && in_array($status, ['pending', 'approved', 'fulfilled', 'rejected'])) {
            $query->where('status', $status);
        }
        
        $requests = $query->latest()->paginate(10);
        
        return view('supplier.requests.index', [
            'requests' => $requests,
            'status' => $status,
            'statusCounts' => [
                'all' => SupplierRequest::where('user_id', $user->id)->count(),
                'pending' => SupplierRequest::where('user_id', $user->id)->where('status', 'pending')->count(),
                'confirmed' => SupplierRequest::where('user_id', $user->id)->where('status', 'approved')->count(),
                'fulfilled' => SupplierRequest::where('user_id', $user->id)->where('status', 'fulfilled')->count(),
                'rejected' => SupplierRequest::where('user_id', $user->id)->where('status', 'rejected')->count(),
            ],
            'user' => $user
        ]);
    }

    /**
     * Show the form for creating a new supply request.
     */
    public function createRequestForm()
    {
        return view('supplier.requests.create');
    }

    /**
     * Store a newly created supply request in storage.
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ]);

        SupplierRequest::create([
            'user_id' => Auth::id(),
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('supplier.requests.index')
                         ->with('success', 'Supply request submitted successfully.');
    }

    /**
     * Display a listing of all requests made by the supplier.
     */
    public function listRequests()
    {
        $requests = SupplierRequest::where('user_id', Auth::id())
                                 ->latest()
                                 ->paginate(10);
        return view('supplier.requests.index', compact('requests'));
    }

    /**
     * Display a listing of confirmed requests for the supplier.
     */
    public function listConfirmedRequests()
    {
        $requests = SupplierRequest::where('user_id', Auth::id())
                                 ->where('status', 'approved')
                                 ->latest()
                                 ->paginate(10);
        return view('supplier.requests.confirmed', compact('requests'));
    }
     /**
     * Display the specified supply request.
     */
    public function showRequest(SupplierRequest $supplyRequest)
    {
        // Ensure the authenticated user is the one who created the request
        if ($supplyRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('supplier.requests.show', compact('supplyRequest'));
    }
}