<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use App\Models\VendorApplicationRequirement;

class ValidationRequirementsController extends Controller
{
    protected $client;
    protected $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = config('services.java_validation.base_url');
    }

    public function index()
    {
        $requirements = VendorApplicationRequirement::all();
        return view('admin.vendor-validation.requirements.index', compact('requirements'));
    }

    public function create()
    {
        return view('admin.vendor-validation.requirements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:vendor_applications_requirements,name',
            'category' => 'required|string',
            'type' => 'required|string',
            'weight' => 'required|numeric|min:1|max:100',
            'required' => 'nullable|boolean',
        ]);
        VendorApplicationRequirement::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'type' => $validated['type'],
            'weight' => $validated['weight'],
            'required' => $request->has('required'),
            'status' => 'active',
        ]);
        return redirect()->route('admin.validation-requirements.index')->with('success', 'Requirement created successfully.');
    }

    public function edit($name)
    {
        $requirement = VendorApplicationRequirement::where('name', $name)->firstOrFail();
        return view('admin.vendor-validation.requirements.edit', [
            'requirement' => $requirement,
            'name' => $name,
        ]);
    }

    public function update(Request $request, $name)
    {
        $requirement = VendorApplicationRequirement::where('name', $name)->firstOrFail();
        $validated = $request->validate([
            'category' => 'required|string',
            'type' => 'required|string',
            'weight' => 'required|numeric|min:1|max:100',
            'required' => 'nullable|boolean',
        ]);
        $requirement->update([
            'category' => $validated['category'],
            'type' => $validated['type'],
            'weight' => $validated['weight'],
            'required' => $request->has('required'),
        ]);
        return redirect()->route('admin.validation-requirements.index')->with('success', 'Requirement updated successfully.');
    }

    public function destroy($name)
    {
        $requirement = VendorApplicationRequirement::where('name', $name)->firstOrFail();
        $requirement->delete();
        return redirect()->route('admin.validation-requirements.index')->with('success', 'Requirement deleted successfully.');
    }

    public function toggle($name, Request $request)
    {
        try {
            $response = $this->client->patch($this->baseUrl . '/requirements/' . $name . '/toggle', [
                'form_params' => [
                    'active' => $request->boolean('active')
                ]
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Failed to toggle validation requirement: ' . $e->getMessage());
            return response()->json(['success' => true, 'warning' => 'Java service is currently unavailable']);
        }
    }
} 