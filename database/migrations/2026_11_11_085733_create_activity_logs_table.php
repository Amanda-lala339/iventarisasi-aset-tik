<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->string('log_id')->unique(); // Contoh: LOG-0001
        $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        $table->string('user_role'); // Admin, Operator, dll
        $table->string('activity_type'); // tambah, ubah, hapus, login
        $table->string('module'); // Perangkat Keras, Server List, dll
        $table->text('description'); // Keterangan singkat
        $table->json('old_data')->nullable(); // Data sebelum (JSON)
        $table->json('new_data')->nullable(); // Data sesudah (JSON)
        $table->string('ip_address')->nullable();
        $table->timestamps();
        
        $table->index(['user_id', 'activity_type', 'module']); // Untuk performa filter
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
