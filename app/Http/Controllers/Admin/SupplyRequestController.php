<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupplyRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SupplyRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $supplyRequests = SupplyRequest::with('user')->latest()->paginate(15);
        return view('admin.supply_requests.index', compact('supplyRequests'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SupplyRequest $supplyRequest)
    {
        $supplyRequest->load('user'); // Eager load the supplier
        return view('admin.supply_requests.show', compact('supplyRequest'));
    }

    /**
     * Confirm the specified supply request.
     */
    public function confirm(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status === 'pending') {
            $supplyRequest->status = 'confirmed_by_manufacturer';
            $supplyRequest->confirmed_at = Carbon::now();
            $supplyRequest->manufacturer_notes = $supplyRequest->manufacturer_notes ?? 'Request confirmed.';
            $supplyRequest->save();
            return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('success', 'Supply request confirmed.');
        }
        return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('info', 'Request is not in a confirmable state.');
    }

    /**
     * Reject the specified supply request.
     */
    public function reject(Request $request, SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status === 'pending' || $supplyRequest->status === 'confirmed_by_manufacturer') {
            $request->validate(['manufacturer_notes' => 'required_if:status,rejected|string|max:1000']);
            
            $supplyRequest->status = 'rejected';
            $supplyRequest->manufacturer_notes = $request->input('manufacturer_notes', 'Request rejected.');
            $supplyRequest->save();
            return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('success', 'Supply request rejected.');
        }
        return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('info', 'Request is not in a rejectable state.');
    }

    /**
     * Mark the specified supply request as fulfilled.
     */
    public function fulfill(SupplyRequest $supplyRequest)
    {
        if ($supplyRequest->status === 'confirmed_by_manufacturer') {
            $supplyRequest->status = 'fulfilled';
            $supplyRequest->fulfilled_at = Carbon::now();
            $supplyRequest->manufacturer_notes = $supplyRequest->manufacturer_notes ?? 'Request fulfilled.';
            $supplyRequest->save();
            return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('success', 'Supply request marked as fulfilled.');
        }
        return redirect()->route('admin.supply-requests.show', $supplyRequest)->with('info', 'Request cannot be fulfilled at this stage.');
    }
}