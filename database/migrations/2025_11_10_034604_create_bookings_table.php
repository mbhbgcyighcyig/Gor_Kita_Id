<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // 1. FIX TYPO KOLOM JAM_SELESAI (jika typo di database)
            if (Schema::hasColumn('bookings', 'jam_seleasi')) {
                $table->renameColumn('jam_seleasi', 'jam_selesai');
            }
            
            // 2. TAMBAH KOLOM YANG TIDAK ADA (notes, payment_expiry, dll)
            if (!Schema::hasColumn('bookings', 'notes')) {
                $table->text('notes')->nullable()->after('duration');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_expiry')) {
                $table->timestamp('payment_expiry')->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_method')) {
                $table->string('payment_method', 50)->nullable()->after('payment_status');
            }
            
            if (!Schema::hasColumn('bookings', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('bookings', 'bank_name')) {
                $table->string('bank_name', 100)->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('bookings', 'virtual_account')) {
                $table->string('virtual_account', 50)->nullable()->after('bank_name');
            }
            
            if (!Schema::hasColumn('bookings', 'payment_pin')) {
                $table->string('payment_pin', 6)->nullable()->after('payment_method');
            }
            
            if (!Schema::hasColumn('bookings', 'pin_verified')) {
                $table->boolean('pin_verified')->default(false)->after('payment_pin');
            }
            
            // 3. TAMBAH CONSTRAINT UNIQUE (optional, hati-hati jika sudah ada data duplikat)
            // $table->unique(['lapangan_id', 'tanggal_booking', 'jam_mulai'], 'unique_booking_slot');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop unique constraint jika ditambahkan
            // $table->dropUnique('unique_booking_slot');
            
            // Drop kolom yang ditambahkan
            $columnsToDrop = [
                'notes', 'payment_expiry', 'payment_method', 
                'paid_at', 'bank_name', 'virtual_account',
                'payment_pin', 'pin_verified'
            ];
            
            foreach ($columnsToDrop as $column) {
                if (Schema::hasColumn('bookings', $column)) {
                    $table->dropColumn($column);
                }
            }
            
            // Rename back typo (jika di-rename)
            if (Schema::hasColumn('bookings', 'jam_selesai')) {
                $table->renameColumn('jam_selesai', 'jam_seleasi');
            }
        });
    }
};