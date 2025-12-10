<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PengaturanController extends Controller
{
    public function pengaturan()
    {
        // Ambil atau buat data pengaturan
        $pengaturan = Pengaturan::first();
        
        if (!$pengaturan) {
            $pengaturan = Pengaturan::create([
                'nama_aplikasi' => 'SportSpace',
                'email_admin' => 'admin@sportspace.id',
                'jam_buka' => '06:00',
                'jam_tutup' => '22:00',
                'payment_gateway' => 'midtrans',
                'api_key' => 'mock_api_key_12345',
            ]);
        }

        return view('admin.pengaturan', compact('pengaturan'));
    }

    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'nama_aplikasi' => 'required|string|max:255',
            'email_admin' => 'required|email',
            'jam_buka' => 'required|date_format:H:i',
            'jam_tutup' => 'required|date_format:H:i',
            'payment_gateway' => 'required|in:midtrans,xendit',
            'api_key' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $pengaturan = Pengaturan::first();

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($pengaturan->logo && Storage::exists('public/' . $pengaturan->logo)) {
                Storage::delete('public/' . $pengaturan->logo);
            }
            
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        }

        $pengaturan->update($validated);

        return redirect()->route('admin.pengaturan')
            ->with('success', 'Pengaturan berhasil diperbarui!');
    }
}