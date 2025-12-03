<?php

namespace App\Http\Controllers;

use App\Models\Lapangan;
use Illuminate\Http\Request;

class LapanganController extends Controller
{
    // Tampilkan semua lapangan
    public function index()
    {
        $fields = Lapangan::latest()->paginate(10);
        return view('admin.lapangan.index', compact('fields'));
    }

    // Form tambah
    public function create()
    {
        return view('admin.lapangan.create');
    }

    // Proses tambah
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:futsal,badminton,minisoccer',
            'price_per_hour' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Lapangan::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'price_per_hour' => $validated['price_per_hour'],
            'description' => $validated['description'] ?? null,
            'is_active' => true
        ]);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil ditambahkan');
    }

    // Form edit
    public function edit($id)
    {
        $field = Lapangan::findOrFail($id);
        return view('admin.lapangan.edit', compact('field'));
    }

    // Proses update
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:futsal,badminton,minisoccer',
            'price_per_hour' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        $lapangan = Lapangan::findOrFail($id);
        $lapangan->update($validated);

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil diupdate!');
    }

    // Delete
    public function destroy($id)
    {
        $lapangan = Lapangan::findOrFail($id);
        $lapangan->delete();

        return redirect()->route('admin.lapangan.index')
            ->with('success', 'Lapangan berhasil dihapus!');
    }
}