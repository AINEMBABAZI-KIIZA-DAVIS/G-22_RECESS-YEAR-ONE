<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\VendorApplication;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class VendorValidationController extends Controller
{
    // Vendor dashboard
    public function dashboard()
    {
        $application = VendorApplication::where('user_id', Auth::id())->latest()->first();
        return view('vendor.dashboard', compact('application'));
    }

    // Show application form
    public function showApplicationForm()
    {
        return view('vendor.apply');
    }

    // Handle application submission
    public function submitApplication(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'annual_revenue_pdf' => 'required|file|mimes:pdf',
            'regulatory_pdf' => 'required|file|mimes:pdf',
            'reputation_pdf' => 'required|file|mimes:pdf',
        ]);
        $user = Auth::user();
        $annual = $request->file('annual_revenue_pdf')->store('vendor_docs');
        $reg = $request->file('regulatory_pdf')->store('vendor_docs');
        $rep = $request->file('reputation_pdf')->store('vendor_docs');
        $app = VendorApplication::create([
            'user_id' => $user->id,
            'company_name' => $request->company_name,
            'contact_email' => $request->contact_email,
            'annual_revenue_pdf' => $annual,
            'regulatory_pdf' => $reg,
            'reputation_pdf' => $rep,
            'status' => 'under_review',
        ]);

        // Send to Java server for validation
        try {
            Log::info('Attempting to call Java validation service at: ' . config('services.java_validation.url'));
            
            $client = new Client();
            $response = $client->request('POST', config('services.java_validation.url'), [
                'multipart' => [
                    [
                        'name' => 'company_name',
                        'contents' => $request->company_name
                    ],
                    [
                        'name' => 'contact_email',
                        'contents' => $request->contact_email
                    ],
                    [
                        'name' => 'annual_revenue_pdf',
                        'contents' => Storage::get($annual),
                        'filename' => $request->file('annual_revenue_pdf')->getClientOriginalName(),
                        'headers' => ['Content-Type' => 'application/pdf']
                    ],
                    [
                        'name' => 'regulatory_pdf',
                        'contents' => Storage::get($reg),
                        'filename' => $request->file('regulatory_pdf')->getClientOriginalName(),
                        'headers' => ['Content-Type' => 'application/pdf']
                    ],
                    [
                        'name' => 'reputation_pdf',
                        'contents' => Storage::get($rep),
                        'filename' => $request->file('reputation_pdf')->getClientOriginalName(),
                        'headers' => ['Content-Type' => 'application/pdf']
                    ],
                ]
            ]);
            
            $result = json_decode($response->getBody(), true);
            Log::info('Java validation service response: ' . json_encode($result));
            
            if (isset($result['status'])) {
                // Map Java service status to database enum values
                $statusMapping = [
                    'approved' => 'approved',
                    'rejected' => 'rejected',
                    'requires_visit' => 'visit_scheduled'
                ];
                
                $dbStatus = $statusMapping[$result['status']] ?? 'under_review';
                $app->status = $dbStatus;
                $app->validation_results = json_encode($result);
                
                // Handle scheduled visit date if provided
                if (isset($result['scheduled_visit_at']) && $dbStatus === 'visit_scheduled') {
                    try {
                        $visitDate = \Carbon\Carbon::parse($result['scheduled_visit_at']);
                        $app->scheduled_visit_at = $visitDate;
                        Log::info('Scheduled visit date set to: ' . $visitDate->format('Y-m-d H:i:s'));
                    } catch (\Exception $e) {
                        Log::error('Failed to parse scheduled visit date: ' . $result['scheduled_visit_at']);
                        // Set a default visit date (7 days from now)
                        $app->scheduled_visit_at = now()->addDays(7);
                    }
                }
                
                $app->save();
                Log::info('Mapped Java status "' . $result['status'] . '" to database status "' . $dbStatus . '"');
            } else {
                Log::warning('Java service response missing status field: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Java validation service error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            // Keep status as 'under_review' if validation fails
        }

        return redirect()->route('vendor.status');
    }

    // Show application status
    public function applicationStatus()
    {
        $app = VendorApplication::where('user_id', Auth::id())->latest()->first();
        return view('vendor.status', compact('app'));
    }

    // Admin: list all applications
    public function adminIndex()
    {
        $applications = VendorApplication::latest()->paginate(20);
        return view('admin.vendor-validation.index', compact('applications'));
    }

    // Admin: show single application
    public function adminShow($id)
    {
        $app = VendorApplication::findOrFail($id);
        return view('admin.vendor-validation.show', compact('app'));
    }



    // Admin: approve vendor application
    public function approveApplication($id)
    {
        $app = VendorApplication::findOrFail($id);
        $app->status = 'approved';
        $app->save();

        Log::info('Admin approved application #' . $id . ' for ' . $app->company_name);

        return redirect()->back()->with('success', 'Application approved for ' . $app->company_name);
    }

    // Admin: reject vendor application
    public function rejectApplication($id)
    {
        $app = VendorApplication::findOrFail($id);
        $app->status = 'rejected';
        $app->save();

        Log::info('Admin rejected application #' . $id . ' for ' . $app->company_name);

        return redirect()->back()->with('success', 'Application rejected for ' . $app->company_name);
    }
}