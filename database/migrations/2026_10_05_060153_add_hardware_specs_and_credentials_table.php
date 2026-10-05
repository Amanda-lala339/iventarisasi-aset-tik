<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->string('cpu_type')->nullable()->after('specification');
            $table->integer('cpu_cores')->nullable()->after('cpu_type');
            $table->integer('ram_gb')->nullable()->after('cpu_cores');
            $table->integer('storage_gb')->nullable()->after('ram_gb');
        });

        Schema::create('asset_credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained('assets')->onDelete('cascade');
            $table->string('username');
            $table->text('password');
            $table->string('role')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('asset_credentials');
        Schema::table('assets', function (Blueprint $table) {
            $table->dropColumn(['cpu_type', 'cpu_cores', 'ram_gb', 'storage_gb']);
        });
    }
};