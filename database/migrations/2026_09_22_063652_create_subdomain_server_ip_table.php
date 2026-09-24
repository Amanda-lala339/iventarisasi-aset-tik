<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subdomain_server_ip', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdomain_id')->constrained()->onDelete('cascade');
            $table->foreignId('server_ip_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['subdomain_id', 'server_ip_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subdomain_server_ip');
    }
};