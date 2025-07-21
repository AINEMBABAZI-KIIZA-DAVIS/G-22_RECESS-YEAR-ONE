<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Http\Request;


class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::all();
        return view('admin.worker.index', compact('workers'));
    }

    public function create()
    {
        return view('admin.worker.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers,email',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'supply_center' => 'nullable|string|max:255',
            'current_role' => 'nullable|string|max:255',
        ]);

        try {
            Worker::create($request->all());
            return redirect()->route('admin.workers.index')->with('success', 'Worker created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create worker. Please try again.')->withInput();
        }
    }

    public function edit(Worker $worker)
    {
        return view('admin.worker.edit', compact('worker'));
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:workers,email,' . $worker->id,
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'supply_center' => 'nullable|string|max:255',
            'current_role' => 'nullable|string|max:255',
        ]);

        try {
            $worker->update($request->all());
            return redirect()->route('admin.workers.index')->with('success', 'Worker updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update worker. Please try again.')->withInput();
        }
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return redirect()->route('admin.workers.index')->with('success', 'Worker deleted successfully.');
    }
}