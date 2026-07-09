<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ip_address');
            $table->string('os');
            $table->string('type'); // Web server, Database server, App server, File / storage, Backup
            $table->string('kind')->default('Physical'); // Physical, Virtual
            $table->string('os_version')->nullable();
            $table->string('status')->default('Online'); // Online, Offline, Warning
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};