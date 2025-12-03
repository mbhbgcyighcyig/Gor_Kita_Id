<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_id',
        'field_id',
        'rating',
        'review'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // ==================== RELASI ====================
    
    /**
     * User yang memberikan rating
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Lapangan yang di-rating
     * Menggunakan field_id sebagai foreign key
     */
    public function lapangan(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'field_id');
    }
    
    /**
     * Alias untuk lapangan (alternatif)
     */
    public function field(): BelongsTo
    {
        return $this->belongsTo(Lapangan::class, 'field_id');
    }

    /**
     * Booking terkait
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    // ==================== SCOPES ====================
    
    /**
     * Scope untuk rating dengan bintang tertentu
     */
    public function scopeWithRating($query, $stars)
    {
        return $query->where('rating', $stars);
    }
    
    /**
     * Scope untuk rating dengan review
     */
    public function scopeWithReview($query)
    {
        return $query->whereNotNull('review')->where('review', '!=', '');
    }
    
    /**
     * Scope untuk rating terbaru
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
    
    /**
     * Scope untuk rating terbaik (rating tertinggi)
     */
    public function scopeHighestRating($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    // ==================== ACCESSORS ====================
    
    /**
     * Rating dalam bentuk bintang (★★★★☆)
     */
    public function getStarRatingAttribute(): string
    {
        $fullStars = str_repeat('★', $this->rating);
        $emptyStars = str_repeat('☆', 5 - $this->rating);
        return $fullStars . $emptyStars;
    }
    
    /**
     * Rating dengan format "4/5"
     */
    public function getFormattedRatingAttribute(): string
    {
        return $this->rating . '/5';
    }
    
    /**
     * Tanggal rating dalam format Indonesia
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->translatedFormat('d F Y');
    }
    
    /**
     * Tanggal dan waktu lengkap
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->created_at->translatedFormat('d F Y H:i');
    }
    
    /**
     * Review singkat (max 100 karakter)
     */
    public function getShortReviewAttribute(): string
    {
        if (empty($this->review)) {
            return '';
        }
        
        if (strlen($this->review) > 100) {
            return substr($this->review, 0, 97) . '...';
        }
        
        return $this->review;
    }
    
    /**
     * Review dengan line breaks
     */
    public function getFormattedReviewAttribute(): string
    {
        if (empty($this->review)) {
            return '';
        }
        
        return nl2br(e($this->review));
    }
    
    /**
     * Warna bintang berdasarkan rating
     */
    public function getRatingColorAttribute(): string
    {
        return match($this->rating) {
            1 => 'text-red-500',
            2 => 'text-orange-500',
            3 => 'text-yellow-500',
            4 => 'text-lime-500',
            5 => 'text-green-500',
            default => 'text-gray-500',
        };
    }
    
    /**
     * Label rating (Poor, Good, Excellent, etc)
     */
    public function getRatingLabelAttribute(): string
    {
        return match($this->rating) {
            1 => 'Sangat Buruk',
            2 => 'Buruk',
            3 => 'Cukup',
            4 => 'Bagus',
            5 => 'Sangat Bagus',
            default => 'Tidak Dikenal',
        };
    }
    
    /**
     * Apakah rating bisa di-edit (dalam 7 hari)
     */
    public function getCanEditAttribute(): bool
    {
        return $this->created_at->diffInDays(now()) <= 7;
    }
    
    /**
     * Waktu yang lalu (2 days ago, 1 hour ago, etc)
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // ==================== MUTATORS ====================
    
    /**
     * Membersihkan review sebelum disimpan
     */
    public function setReviewAttribute($value): void
    {
        if ($value === null) {
            $this->attributes['review'] = null;
            return;
        }
        
        // Trim whitespace
        $value = trim($value);
        
        // Jika kosong setelah trim, set null
        if ($value === '') {
            $this->attributes['review'] = null;
            return;
        }
        
        $this->attributes['review'] = $value;
    }

    // ==================== METHODS ====================
    
    /**
     * Cek apakah user bisa mengedit rating ini
     */
    public function canEdit(): bool
    {
        // 1. Cek apakah rating dibuat kurang dari 7 hari yang lalu
        $isRecent = $this->created_at->diffInDays(now()) <= 7;
        
        // 2. Atau tambahkan logic lain sesuai kebutuhan
        return $isRecent;
    }
    
    /**
     * Cek apakah user bisa menghapus rating ini
     */
    public function canDelete(): bool
    {
        // Rating bisa dihapus jika dibuat kurang dari 24 jam
        return $this->created_at->diffInHours(now()) <= 24;
    }
    
    /**
     * Generate excerpt dari review
     */
    public function excerpt(int $length = 150): string
    {
        if (empty($this->review)) {
            return '';
        }
        
        $review = strip_tags($this->review);
        
        if (strlen($review) <= $length) {
            return $review;
        }
        
        $excerpt = substr($review, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');
        
        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }
        
        return $excerpt . '...';
    }
    
    /**
     * Apakah rating ini memiliki review
     */
    public function hasReview(): bool
    {
        return !empty($this->review);
    }
    
    /**
     * Validasi apakah rating ini valid
     */
    public function isValid(): bool
    {
        return $this->rating >= 1 && $this->rating <= 5;
    }
}