<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;

    // PASTIKAN TABLE NAME SESUAI
    protected $table = 'lapangans';

    // PASTIKAN FILLABLE SESUAI
    protected $fillable = [
        'name',
        'type', 
        'price_per_hour',
        'description',
        'is_active'
    ];

    protected $casts = [
        'price_per_hour' => 'integer',
        'is_active' => 'boolean'
    ];

    // ✅ TAMBAHKAN RELASI KE BOOKINGS
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'lapangan_id');
    }
}