<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ========================================
        // 1. Tambah kolom ke tabel 'servers'
        // ========================================
        if (Schema::hasTable('servers')) {
            Schema::table('servers', function (Blueprint $table) {
                if (!Schema::hasColumn('servers', 'ip_address')) {
                    $table->string('ip_address')->nullable()->after('name');
                }
            });
        }

        // ========================================
        // 2. Tambah kolom ke tabel 'subdomains'
        // ========================================
        if (Schema::hasTable('subdomains')) {
            Schema::table('subdomains', function (Blueprint $table) {
                if (!Schema::hasColumn('subdomains', 'domain')) {
                    $table->string('domain')->nullable()->after('subdomain');
                }
                if (!Schema::hasColumn('subdomains', 'server_id')) {
                    $table->foreignId('server_id')->nullable()->after('domain')->constrained('servers')->nullOnDelete();
                }
                if (!Schema::hasColumn('subdomains', 'ssl_expiry')) {
                    $table->date('ssl_expiry')->nullable()->after('server_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('subdomains')) {
            Schema::table('subdomains', function (Blueprint $table) {
                if (Schema::hasColumn('subdomains', 'server_id')) {
                    $table->dropForeign(['server_id']);
                    $table->dropColumn('server_id');
                }
                if (Schema::hasColumn('subdomains', 'ssl_expiry')) {
                    $table->dropColumn('ssl_expiry');
                }
                if (Schema::hasColumn('subdomains', 'domain')) {
                    $table->dropColumn('domain');
                }
            });
        }

        if (Schema::hasTable('servers')) {
            Schema::table('servers', function (Blueprint $table) {
                if (Schema::hasColumn('servers', 'ip_address')) {
                    $table->dropColumn('ip_address');
                }
            });
        }
    }
};