<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 
        'jumlah_bayar', 
        'metode_pembayaran', 
        'tanggal_bayar',
        'status_pembayaran', 
        'bukti_pembayaran',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
