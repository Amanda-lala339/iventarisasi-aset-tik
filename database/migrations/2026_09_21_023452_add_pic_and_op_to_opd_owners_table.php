<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('opd_owners', function (Blueprint $table) {
            $table->string('pic')->nullable()->after('name');
        $table->string('op')->nullable()->after('pic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('opd_owners', function (Blueprint $table) {
            $table->dropColumn(['pic', 'op']);
        });
    }
};
