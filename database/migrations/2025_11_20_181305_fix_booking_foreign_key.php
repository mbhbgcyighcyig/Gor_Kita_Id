<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Drop foreign key constraint yang salah
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['lapangan_id']);
        });

        // Add foreign key yang benar
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreign('lapangan_id')
                  ->references('id')
                  ->on('fields') // ✅ PASTIKAN INI 'fields' bukan 'lapangans'
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['lapangan_id']);
        });
    }
};