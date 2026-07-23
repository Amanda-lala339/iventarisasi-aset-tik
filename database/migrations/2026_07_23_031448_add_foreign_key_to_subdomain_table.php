<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subdomains', function (Blueprint $table) {
            if (!Schema::hasColumn('subdomains', 'server_id')) {
                $table->foreignId('server_id')
                    ->nullable()
                    ->after('domain')
                    ->constrained('servers')
                    ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('subdomains', function (Blueprint $table) {
            if (Schema::hasColumn('subdomains', 'server_id')) {
                $table->dropForeign(['server_id']);
                $table->dropColumn('server_id');
            }
        });
    }
};