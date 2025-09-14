<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use Illuminate\Http\Request;

class ServiceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $service_types = ServiceType::paginate(10);
        return view('service_types.index', compact('service_types'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('service_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ServiceType::create($request->all());
        return redirect()->route('service_types.index')->with('success', 'Service Type created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ServiceType $service_type)
    {
        return view('service_types.edit', compact('service_type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ServiceType $service_type)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $service_type->update($request->all());
        return redirect()->route('service_types.index')->with('success', 'Service Type updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServiceType $service_type)
    {
        $service_type->delete();
        return redirect()->route('service_types.index')->with('success', 'Service Type deleted successfully.');
    }
}