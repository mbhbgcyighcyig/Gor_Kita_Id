<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    // ✅ SESUAIKAN DENGAN KOLOM YANG ADA DI TABEL
    protected $fillable = [
        'user_id', 
        'lapangan_id',
        'tanggal_booking',
        'jam_mulai',
        'jam_selesai',
        'total_price',
        'status',
        'payment_status',
        'notes',
        'duration',
        'payment_pin',
        'pin_verified',
        'bank_name',
        'virtual_account',
        'paid_at',
        'payment_method',
        'payment_expiry'
    ];

    protected $casts = [
        'tanggal_booking' => 'date',
        'paid_at' => 'datetime',
        'payment_expiry' => 'datetime',
        'pin_verified' => 'boolean',
        'total_price' => 'decimal:2',
        'duration' => 'integer'
    ];

    protected $appends = [
        'formatted_date',
        'formatted_time',
        'formatted_price',
        'duration_hours',
        'is_expired',
        'can_be_confirmed',
        'can_be_rejected',
        'status_badge_class',
        'payment_status_badge_class',
        'booking_datetime',
        'remaining_payment_time'
    ];

    // ==================== CONSTANTS ====================
    
    const STATUS_PENDING = 'pending';
    const STATUS_PENDING_VERIFICATION = 'pending_verification';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';
    
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PENDING_VERIFICATION = 'pending_verification';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';

    // ==================== RELASI ====================
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lapangan()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }
    
    public function field()
    {
        return $this->belongsTo(Lapangan::class, 'lapangan_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class, 'booking_id');
    }

    // ==================== SCOPES ====================
    
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->orWhere('status', self::STATUS_PENDING_VERIFICATION);
    }
    
    public function scopeConfirmed($query)
    {
        return $query->where('status', self::STATUS_CONFIRMED);
    }
    
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED, self::STATUS_COMPLETED]);
    }
    
    public function scopePaymentPending($query)
    {
        return $query->whereIn('payment_status', [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION]);
    }
    
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('status', self::STATUS_EXPIRED)
              ->orWhere(function($sub) {
                  $sub->whereNotNull('payment_expiry')
                      ->where('payment_expiry', '<', now());
              });
        });
    }
    
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_booking', today());
    }
    
    public function scopeUpcoming($query)
    {
        return $query->whereDate('tanggal_booking', '>=', today())
                    ->where('status', self::STATUS_CONFIRMED);
    }

    // ==================== ACCESSORS ====================
    
    public function getFormattedDateAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking)->translatedFormat('l, d F Y');
        } catch (\Exception $e) {
            return date('d M Y', strtotime($this->tanggal_booking));
        }
    }

    public function getFormattedTimeAttribute()
    {
        try {
            $start = Carbon::parse($this->jam_mulai)->format('H:i');
            $end = Carbon::parse($this->jam_selesai)->format('H:i');
            return $start . ' - ' . $end;
        } catch (\Exception $e) {
            return $this->jam_mulai . ' - ' . $this->jam_selesai;
        }
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->total_price ?? 0, 0, ',', '.');
    }
    
    public function getDurationHoursAttribute()
    {
        return ($this->duration ?? 1) . ' jam';
    }
    
    public function getIsExpiredAttribute()
    {
        // Cek berdasarkan payment_expiry
        if ($this->payment_expiry && $this->payment_expiry < now()) {
            return true;
        }
        
        // Cek apakah status sudah expired
        if ($this->status === self::STATUS_EXPIRED) {
            return true;
        }
        
        // Cek berdasarkan tanggal booking sudah lewat untuk pending payments
        try {
            $bookingDateTime = Carbon::parse($this->tanggal_booking . ' ' . $this->jam_selesai);
            $isBookingPast = $bookingDateTime < now();
            
            // Jika booking sudah lewat dan masih pending, consider expired
            if ($isBookingPast && in_array($this->payment_status, [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION])) {
                return true;
            }
            
            return $isBookingPast && in_array($this->status, [self::STATUS_PENDING, self::STATUS_PENDING_VERIFICATION]);
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getCanBeConfirmedAttribute()
    {
        // Status payment yang bisa dikonfirmasi
        $validPaymentStatuses = [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION];
        
        // Status booking yang bisa dikonfirmasi
        $validBookingStatuses = [self::STATUS_PENDING, self::STATUS_PENDING_VERIFICATION];
        
        return in_array($this->payment_status, $validPaymentStatuses) 
               && in_array($this->status, $validBookingStatuses)
               && !$this->is_expired;
    }
    
    public function getCanBeRejectedAttribute()
    {
        // Hanya pending payments yang bisa ditolak
        $validPaymentStatuses = [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION];
        
        return in_array($this->payment_status, $validPaymentStatuses) 
               && !in_array($this->status, [self::STATUS_CANCELLED, self::STATUS_EXPIRED, self::STATUS_COMPLETED]);
    }
    
    public function getStatusBadgeClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'badge bg-warning text-dark',
            self::STATUS_PENDING_VERIFICATION => 'badge bg-info text-white',
            self::STATUS_CONFIRMED => 'badge bg-primary text-white',
            self::STATUS_COMPLETED => 'badge bg-success text-white',
            self::STATUS_CANCELLED => 'badge bg-secondary text-white',
            self::STATUS_EXPIRED => 'badge bg-danger text-white'
        ];
        
        return $classes[$this->status] ?? 'badge bg-secondary text-white';
    }
    
    public function getPaymentStatusBadgeClassAttribute()
    {
        $classes = [
            self::PAYMENT_STATUS_PENDING => 'badge bg-warning text-dark',
            self::PAYMENT_STATUS_PENDING_VERIFICATION => 'badge bg-info text-white',
            self::PAYMENT_STATUS_PAID => 'badge bg-success text-white',
            self::PAYMENT_STATUS_FAILED => 'badge bg-danger text-white'
        ];
        
        return $classes[$this->payment_status] ?? 'badge bg-secondary text-white';
    }
    
    public function getBookingDatetimeAttribute()
    {
        try {
            return Carbon::parse($this->tanggal_booking . ' ' . $this->jam_mulai);
        } catch (\Exception $e) {
            return null;
        }
    }
    
    public function getRemainingPaymentTimeAttribute()
    {
        if (!$this->payment_expiry || $this->payment_status !== self::PAYMENT_STATUS_PENDING_VERIFICATION) {
            return null;
        }
        
        $now = now();
        if ($this->payment_expiry < $now) {
            return 'Expired';
        }
        
        $diffInMinutes = $now->diffInMinutes($this->payment_expiry);
        
        if ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit';
        } elseif ($diffInMinutes < 1440) {
            $hours = floor($diffInMinutes / 60);
            $minutes = $diffInMinutes % 60;
            return $hours . ' jam ' . $minutes . ' menit';
        } else {
            $days = floor($diffInMinutes / 1440);
            return $days . ' hari';
        }
    }
    
    public function getIsActiveAttribute()
    {
        return $this->status === self::STATUS_CONFIRMED 
               && $this->payment_status === self::PAYMENT_STATUS_PAID
               && !$this->is_expired;
    }
    
    public function getIsUpcomingAttribute()
    {
        try {
            $bookingDateTime = Carbon::parse($this->tanggal_booking . ' ' . $this->jam_mulai);
            return $bookingDateTime > now() 
                   && $this->status === self::STATUS_CONFIRMED 
                   && $this->payment_status === self::PAYMENT_STATUS_PAID;
        } catch (\Exception $e) {
            return false;
        }
    }
    
    public function getIsPastAttribute()
    {
        try {
            $bookingDateTime = Carbon::parse($this->tanggal_booking . ' ' . $this->jam_selesai);
            return $bookingDateTime < now();
        } catch (\Exception $e) {
            return false;
        }
    }

    // ==================== METHODS ====================
    
    public function calculatePrice()
    {
        try {
            $start = Carbon::parse($this->jam_mulai);
            $end = Carbon::parse($this->jam_selesai);
            $duration = $start->diffInHours($end);
            
            if ($this->lapangan && $this->lapangan->price_per_hour) {
                $this->total_price = $duration * $this->lapangan->price_per_hour;
                $this->duration = $duration;
            } else {
                $defaultPrice = Lapangan::first()->price_per_hour ?? 40000;
                $this->total_price = $duration * $defaultPrice;
                $this->duration = $duration;
            }
            
            return $this->total_price;
        } catch (\Exception $e) {
            $this->total_price = $this->lapangan->price_per_hour ?? 40000;
            $this->duration = 1;
            return $this->total_price;
        }
    }
    
    public function checkForConflicts()
    {
        return Booking::where('lapangan_id', $this->lapangan_id)
            ->where('tanggal_booking', $this->tanggal_booking)
            ->where('status', self::STATUS_CONFIRMED)
            ->where('id', '!=', $this->id)
            ->where(function($query) {
                $query->where(function($q) {
                    $q->where('jam_mulai', '>=', $this->jam_mulai)
                      ->where('jam_mulai', '<', $this->jam_selesai);
                })->orWhere(function($q) {
                    $q->where('jam_selesai', '>', $this->jam_mulai)
                      ->where('jam_selesai', '<=', $this->jam_selesai);
                })->orWhere(function($q) {
                    $q->where('jam_mulai', '<=', $this->jam_mulai)
                      ->where('jam_selesai', '>=', $this->jam_selesai);
                });
            })
            ->exists();
    }
    
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => self::PAYMENT_STATUS_PAID,
            'status' => self::STATUS_CONFIRMED,
            'paid_at' => now()
        ]);
        
        return $this;
    }
    
    public function markAsFailed()
    {
        $this->update([
            'payment_status' => self::PAYMENT_STATUS_FAILED,
            'status' => self::STATUS_CANCELLED
        ]);
        
        return $this;
    }
    
    public function markAsExpired()
    {
        $this->update([
            'payment_status' => self::PAYMENT_STATUS_FAILED,
            'status' => self::STATUS_EXPIRED
        ]);
        
        return $this;
    }
    
    public function cancel()
    {
        $this->update([
            'status' => self::STATUS_CANCELLED
        ]);
        
        return $this;
    }
    
    public function complete()
    {
        $this->update([
            'status' => self::STATUS_COMPLETED
        ]);
        
        return $this;
    }
    
    public function setPaymentExpiry($hours = 24)
    {
        $this->update([
            'payment_expiry' => now()->addHours($hours)
        ]);
        
        return $this;
    }

    // ==================== EVENT HANDLERS ====================
    
    protected static function boot()
    {
        parent::boot();
        
        // Auto set payment_expiry ketika booking dibuat dengan payment pending
        static::creating(function ($model) {
            if (in_array($model->payment_status, [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION]) && !$model->payment_expiry) {
                $model->payment_expiry = now()->addHours(24);
            }
            
            // Auto calculate price jika tidak ada
            if (!$model->total_price || $model->total_price == 0) {
                $model->calculatePrice();
            }
        });
        
        // Auto expire booking ketika payment_expiry lewat
        static::saving(function ($model) {
            if ($model->payment_expiry && $model->payment_expiry < now() 
                && in_array($model->payment_status, [self::PAYMENT_STATUS_PENDING, self::PAYMENT_STATUS_PENDING_VERIFICATION])) {
                $model->payment_status = self::PAYMENT_STATUS_FAILED;
                $model->status = self::STATUS_EXPIRED;
            }
        });
    }
}