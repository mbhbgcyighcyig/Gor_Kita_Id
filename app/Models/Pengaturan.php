<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    use HasFactory;

    protected $table = 'pengaturan';
    
    protected $fillable = [
        'nama_aplikasi',
        'email_admin',
        'jam_buka', 
        'jam_tutup',
        'payment_gateway',
        'api_key',
        'logo'
    ];

    protected $casts = [
        'jam_buka' => 'datetime:H:i',
        'jam_tutup' => 'datetime:H:i',
    ];
}