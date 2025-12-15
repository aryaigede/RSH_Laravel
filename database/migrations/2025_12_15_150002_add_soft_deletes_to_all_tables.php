<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add soft delete columns to pet
        Schema::table('pet', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to pemilik
        Schema::table('pemilik', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to jenis_hewan
        Schema::table('jenis_hewan', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to ras_hewan
        Schema::table('ras_hewan', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to kategori
        Schema::table('kategori', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to kategori_klinis
        Schema::table('kategori_klinis', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to kode_tindakan_terapi
        Schema::table('kode_tindakan_terapi', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to rekam_medis
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to detail_rekam_medis
        Schema::table('detail_rekam_medis', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to role
        Schema::table('role', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to users
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });

        // Add soft delete columns to role_user
        Schema::table('role_user', function (Blueprint $table) {
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        $tables = ['pet', 'pemilik', 'jenis_hewan', 'ras_hewan', 'kategori', 'kategori_klinis', 
                   'kode_tindakan_terapi', 'rekam_medis', 'detail_rekam_medis', 'role', 'users', 'role_user'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $t) use ($table) {
                $t->dropForeign([$table . '_deleted_by_foreign']);
                $t->dropColumn(['deleted_at', 'deleted_by']);
            });
        }
    }
};
