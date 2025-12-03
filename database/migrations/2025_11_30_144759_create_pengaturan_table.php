<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi')->default('SportSpace');
            $table->string('email_admin')->default('admin@sportspace.id');
            $table->time('jam_buka')->default('06:00');
            $table->time('jam_tutup')->default('22:00');
            $table->enum('payment_gateway', ['midtrans', 'xendit'])->default('midtrans');
            $table->string('api_key')->nullable();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        // Insert default data
        DB::table('pengaturan')->insert([
            'nama_aplikasi' => 'SportSpace',
            'email_admin' => 'admin@sportspace.id',
            'jam_buka' => '06:00',
            'jam_tutup' => '22:00',
            'payment_gateway' => 'midtrans',
            'api_key' => 'mock_api_key_12345',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan');
    }
};