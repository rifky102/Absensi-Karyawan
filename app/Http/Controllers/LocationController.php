<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Unit;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display all locations
     */
    public function index()
    {
        $locations = Location::with('unit')->paginate(15);
        return view('locations.index', compact('locations'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $units = Unit::where('is_active', true)->get();
        return view('locations.create', compact('units'));
    }

    /**
     * Store new location
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:5|max:100',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil ditambahkan!');
    }

    /**
     * Show edit form
     */
    public function edit(Location $location)
    {
        $units = Unit::where('is_active', true)->get();
        return view('locations.edit', compact('location', 'units'));
    }

    /**
     * Update location
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:5|max:100',
            'is_active' => 'boolean',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil diperbarui!');
    }

    /**
     * Delete location
     */
    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('locations.index')->with('success', 'Lokasi berhasil dihapus!');
    }

    /**
     * Get location details via API
     */
    public function getDetails($id)
    {
        $location = Location::findOrFail($id);
        return response()->json($location);
    }
}
