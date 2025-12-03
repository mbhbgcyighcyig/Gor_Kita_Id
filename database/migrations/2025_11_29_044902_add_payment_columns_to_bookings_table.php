<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Tambah kolom untuk sistem pembayaran dengan PIN
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // ========== KOLOM PAYMENT PIN ==========
            if (!Schema::hasColumn('bookings', 'payment_pin')) {
                $table->string('payment_pin', 255)->nullable()->after('notes')
                      ->comment('PIN untuk konfirmasi pembayaran (hashed)');
            }
            
            if (!Schema::hasColumn('bookings', 'pin_verified')) {
                $table->boolean('pin_verified')->default(false)->after('payment_pin')
                      ->comment('Status verifikasi PIN');
            }
            
            if (!Schema::hasColumn('bookings', 'pin_attempts')) {
                $table->tinyInteger('pin_attempts')->default(0)->after('pin_verified')
                      ->comment('Jumlah percobaan PIN salah (max 3)');
            }
            
            // ========== KOLOM BANK & VIRTUAL ACCOUNT ==========
            if (!Schema::hasColumn('bookings', 'bank_name')) {
                $table->string('bank_name', 50)->nullable()->after('pin_attempts')
                      ->comment('Nama bank: BCA, BRI, Mandiri, BNI, CIMB');
            }
            
            if (!Schema::hasColumn('bookings', 'virtual_account')) {
                $table->string('virtual_account', 20)->nullable()->after('bank_name')
                      ->comment('Nomor virtual account');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->default('virtual_account')->after('virtual_account')
                      ->comment('Metode pembayaran');
            }
            
            // ========== KOLOM STATUS PEMBAYARAN ==========
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 30)->default('pending')->after('payment_method')
                      ->comment('Status pembayaran');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_proof')) {
                $table->string('payment_proof')->nullable()->after('payment_status')
                      ->comment('File bukti pembayaran');
            }
            
            // ========== KOLOM TIMESTAMPS PEMBAYARAN ==========
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_proof')
                      ->comment('Waktu pembayaran dilakukan');
            }
            
            if (!Schema::hasColumn('bookings', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('paid_at')
                      ->comment('Waktu diverifikasi admin');
            }
            
            if (!Schema::hasColumn('bookings', 'verified_by')) {
                $table->unsignedBigInteger('verified_by')->nullable()->after('verified_at')
                      ->comment('ID admin yang verifikasi');
            }
            
            if (!Schema::hasColumn('bookings', 'rejected_at')) {
                $table->timestamp('rejected_at')->nullable()->after('verified_by')
                      ->comment('Waktu ditolak admin');
            }
            
            if (!Schema::hasColumn('bookings', 'rejected_by')) {
                $table->unsignedBigInteger('rejected_by')->nullable()->after('rejected_at')
                      ->comment('ID admin yang menolak');
            }
            
            if (!Schema::hasColumn('bookings', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('rejected_by')
                      ->comment('Alasan penolakan');
            }
            
            // ========== KOLOM TAMBAHAN ==========
            if (!Schema::hasColumn('bookings', 'cancelled_at')) {
                $table->timestamp('cancelled_at')->nullable()->after('rejection_reason')
                      ->comment('Waktu dibatalkan');
            }
            
            if (!Schema::hasColumn('bookings', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('cancelled_at')
                      ->comment('Waktu selesai');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_expiry')) {
                $table->timestamp('payment_expiry')->nullable()->after('completed_at')
                      ->comment('Waktu kadaluarsa pembayaran');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_notes')) {
                $table->text('payment_notes')->nullable()->after('payment_expiry')
                      ->comment('Catatan pembayaran');
            }
        });
    }

    /**
     * Reverse the migrations.
     * 
     * Hapus semua kolom yang ditambahkan
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // List semua kolom yang akan dihapus
            $columns = [
                'payment_notes',
                'payment_expiry',
                'completed_at',
                'cancelled_at',
                'rejection_reason',
                'rejected_by',
                'rejected_at',
                'verified_by',
                'verified_at',
                'paid_at',
                'payment_proof',
                'payment_status',
                'payment_method',
                'virtual_account',
                'bank_name',
                'pin_attempts',
                'pin_verified',
                'payment_pin'
            ];
            
            // Hapus kolom jika ada
            foreach ($columns as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};