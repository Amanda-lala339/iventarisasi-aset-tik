// database/migrations/xxxx_xx_xx_create_opd_owners_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opd_owners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama OPD/Dinas
            $table->string('code')->nullable()->unique(); // Kode OPD
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['is_active', 'name']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('opd_owners');
    }
};