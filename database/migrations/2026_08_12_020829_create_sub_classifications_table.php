// database/migrations/xxxx_xx_xx_create_sub_classifications_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sub_classifications', function (Blueprint $table) {
            $table->id();
            $table->string('asset_category_code', 10); // DI, PL, PK, SP, PS
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['asset_category_code', 'is_active', 'order']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_classifications');
    }
};