<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kita gunakan pengecekan 'hasColumn' agar tidak terjadi error Duplicate Column
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['customer', 'admin'])
                    ->default('customer')
                    ->after('password');
            }

            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')
                    ->nullable()
                    ->after('role');
            }

            // TAMBAHKAN KOLOM BANNER DI SINI
            if (!Schema::hasColumn('users', 'banner')) {
                $table->string('banner')
                    ->nullable()
                    ->after('avatar');
            }

            if (!Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')
                    ->nullable()
                    ->unique()
                    ->after('banner');
            }

            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)
                    ->nullable()
                    ->after('google_id');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->text('address')
                    ->nullable()
                    ->after('phone');
            }
        });
    }

    /**
     * Mundurkan migration.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus semua kolom yang ditambahkan jika ada
            $columns = ['role', 'avatar', 'banner', 'google_id', 'phone', 'address'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};