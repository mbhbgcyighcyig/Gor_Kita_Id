<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lapangan extends Model
{
    use HasFactory;

    protected $table = 'fields';

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

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'lapangan_id');
    }
  
    public function getNamaLapanganAttribute()
    {
        return $this->name ?? "Lapangan {$this->type}";
    }
}