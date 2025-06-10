@extends('layouts.admin_app') {{-- Or your main app layout --}}

@section('content')
<div class="px-6 py-6 max-w-7xl mx-auto">
    <h2 class="mb-6 text-2xl font-bold text-red-500">Application Settings</h2>

    @if (session('success'))
        <div class="mb-4 rounded-md bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative" role="alert">
            {{ session('success') }}
            <button type="button" class="absolute top-2 right-2 text-green-700 hover:text-green-900" 
                onclick="this.parentElement.style.display='none';" aria-label="Close">&times;</button>
        </div>
    @endif

    <div class="bg-white shadow-sm rounded-lg">
        <div class="border-b border-gray-200 px-6 py-4">
            <h5 class="text-lg font-semibold text-blue-500">General Settings</h5>
        </div>
        <div class="px-6 py-6">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT') {{-- Or POST if you prefer for settings --}}

                <div class="mb-6 p-4 rounded-md bg-blue-50 text-blue-700 flex items-center space-x-2">
                    <i class="fas fa-info-circle"></i>
                    <span>This is a placeholder for application settings.
                    Actual implementation will require database storage or configuration file management.</span>
                </div>

                {{-- Example Setting Field --}}
                <div class="mb-5">
                    <label for="site_name" class="block mb-1 font-medium text-gray-700">Site Name</label>
                    <input type="text" id="site_name" name="site_name" 
                        value="{{ config('app.name', 'Supply System') }}"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p class="mt-1 text-sm text-gray-500">The name of your application.</p>
                </div>

                <div class="mb-5">
                    <label for="admin_email" class="block mb-1 font-medium text-gray-700">Administrator Email</label>
                    <input type="email" id="admin_email" name="admin_email" value="admin@example.com"
                        class="w-full rounded-md border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                    <p class="mt-1 text-sm text-gray-500">Email address for administrative notifications.</p>
                </div>

                <div class="mb-6 flex items-center">
                    <input type="checkbox" id="maintenance_mode" name="maintenance_mode" 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" />
                    <label for="maintenance_mode" class="ml-2 block text-gray-700">Enable Maintenance Mode</label>
                </div>

                <button type="submit" class="btn-primary-gradient">Save Settings (Placeholder)</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .btn-primary-gradient {
        background: linear-gradient(90deg, #357aff 60%, #5eead4 100%);
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 0.5rem;
        padding: 0.6rem 1.2rem;
        box-shadow: 0 2px 8px rgba(53, 122, 255, 0.10);
        transition: background 0.3s, box-shadow 0.3s;
        cursor: pointer;
    }
    .btn-primary-gradient:hover {
        background: linear-gradient(90deg, #2563eb 60%, #2dd4bf 100%);
        color: #fff;
    }
</style>
@endpush
