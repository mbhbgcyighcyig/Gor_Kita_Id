<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    // Kolom-kolom yang boleh diisi
    protected $fillable = [
        'booking_id', 
        'jumlah_bayar', 
        'metode_pembayaran', // misal: Transfer Bank, E-Wallet, Cash
        'tanggal_bayar',
        'status_pembayaran', // lunas, gagal, menunggu
        'bukti_pembayaran', // path ke file gambar/bukti
    ];

    // --- RELASI ---

    /**
     * Pembayaran adalah milik satu Booking.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
