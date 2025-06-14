<?php

namespace App\Http\Controllers;

use App\Models\SupplyRequest;
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
        $pendingRequestsCount = SupplyRequest::where('user_id', $user->id)
                                           ->where('status', 'pending')
                                           ->count();
        $confirmedRequestsCount = SupplyRequest::where('user_id', $user->id)
                                             ->where('status', 'confirmed_by_manufacturer')
                                             ->count();
        $fulfilledRequestsCount = SupplyRequest::where('user_id', $user->id)
                                             ->where('status', 'fulfilled')
                                             ->count();

        return view('supplier.dashboard', compact(
            'pendingRequestsCount',
            'confirmedRequestsCount',
            'fulfilledRequestsCount'
        ));
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

        SupplyRequest::create([
            'user_id' => Auth::id(),
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'notes' => $request->notes,
            'status' => 'pending', // Default status
        ]);

        return redirect()->route('supplier.requests.list')
                         ->with('success', 'Supply request submitted successfully.');
    }

    /**
     * Display a listing of all requests made by the supplier.
     */
    public function listRequests()
    {
        $requests = SupplyRequest::where('user_id', Auth::id())
                                 ->latest()
                                 ->paginate(10);
        return view('supplier.requests.index', compact('requests'));
    }

    /**
     * Display a listing of confirmed requests for the supplier.
     */
    public function listConfirmedRequests()
    {
        $requests = SupplyRequest::where('user_id', Auth::id())
                                 ->where('status', 'confirmed_by_manufacturer')
                                 ->latest()
                                 ->paginate(10);
        return view('supplier.requests.confirmed', compact('requests'));
    }
     /**
     * Display the specified supply request.
     */
    public function showRequest(SupplyRequest $supplyRequest)
    {
        // Ensure the authenticated user is the one who created the request
        if ($supplyRequest->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('supplier.requests.show', compact('supplyRequest'));
    }
}