<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplierRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupplierRequestController extends Controller
{
    /**
     * Display a listing of supplier requests.
     */
    public function index()
    {
        $supplierRequests = SupplierRequest::with('user')
            ->latest()
            ->paginate(15);
            
        return view('admin.supplier_requests.index', compact('supplierRequests'));
    }

    /**
     * Display the specified supplier request.
     */
    public function show(SupplierRequest $supplierRequest)
    {
        $supplierRequest->load('user');
        return view('admin.supplier_requests.show', compact('supplierRequest'));
    }

    /**
     * Approve the specified supplier request.
     */
    public function approve(SupplierRequest $supplierRequest)
    {
        if ($supplierRequest->status === 'pending') {
            $supplierRequest->update([
                'status' => 'approved',
            ]);

            return redirect()->route('admin.supplier-requests.index')
                ->with('success', 'Supplier request approved successfully.');
        }

        return redirect()->back()
            ->with('error', 'Request cannot be approved. Current status: ' . $supplierRequest->status);
    }

    /**
     * Reject the specified supplier request.
     */
    public function reject(SupplierRequest $supplierRequest)
    {
        if ($supplierRequest->status === 'pending') {
            $supplierRequest->update([
                'status' => 'rejected',
            ]);

            return redirect()->route('admin.supplier-requests.index')
                ->with('success', 'Supplier request rejected successfully.');
        }

        return redirect()->back()
            ->with('error', 'Request cannot be rejected. Current status: ' . $supplierRequest->status);
    }

    /**
     * Mark the specified supplier request as fulfilled.
     */
    public function fulfill(SupplierRequest $supplierRequest)
    {
        if (in_array($supplierRequest->status, ['pending', 'approved'])) {
            $supplierRequest->update([
                'status' => 'fulfilled',
            ]);

            return redirect()->route('admin.supplier-requests.index')
                ->with('success', 'Supplier request marked as fulfilled.');
        }

        return redirect()->back()
            ->with('error', 'Request cannot be fulfilled. Current status: ' . $supplierRequest->status);
    }
} 