<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('action'); // Contoh: REQUEST_PASSWORD_ACCESS
            $table->string('target_type')->nullable(); // Contoh: AssetCredential
            $table->unsignedBigInteger('target_id')->nullable(); // ID dari AssetCredential
            $table->unsignedBigInteger('asset_id')->nullable(); // ID Aset (untuk pencarian cepat)
            $table->text('reason')->nullable(); // Alasan user
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            
            // Index untuk mempercepat pencarian saat audit
            $table->index(['user_id', 'action', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};