<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Tambah semua kolom payment yang belum ada
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
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Drop semua kolom yang ditambahkan
            $table->dropColumn([
                'notes',
                'payment_expiry',
                'payment_method',
                'paid_at',
                'bank_name',
                'virtual_account',
                'payment_pin',
                'pin_verified'
            ]);
        });
    }
};