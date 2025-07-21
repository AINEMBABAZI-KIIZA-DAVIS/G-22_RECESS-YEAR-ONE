<?php

namespace App\Http\Controllers\Supplier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierRequest;
use Illuminate\Support\Facades\Auth;

class RequestController extends Controller
{
    public function index()
    {
        $requests = Auth::user()->supplierRequests()
            ->latest()
            ->paginate(10);
            
        return view('supplier.requests.index', compact('requests'));
    }

    public function pending()
    {
        $requests = Auth::user()->supplierRequests()
            ->where('status', 'pending')
            ->latest()
            ->paginate(10);
            
        return view('supplier.requests.pending', compact('requests'));
    }

    public function confirmed()
    {
        $requests = Auth::user()->supplierRequests()
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);
            
        return view('supplier.requests.confirmed', compact('requests'));
    }

    public function fulfilled()
    {
        $requests = Auth::user()->supplierRequests()
            ->where('status', 'fulfilled')
            ->latest()
            ->paginate(10);
            
        return view('supplier.requests.fulfilled', compact('requests'));
    }

    public function create()
    {
        return view('supplier.requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        Auth::user()->supplierRequests()->create($validated);

        return redirect()->route('supplier.requests.index')
            ->with('success', 'Request created successfully!');
    }

    public function show(SupplierRequest $request)
    {
        $this->authorize('view', $request);
        
        return view('supplier.requests.show', compact('request'));
    }
}