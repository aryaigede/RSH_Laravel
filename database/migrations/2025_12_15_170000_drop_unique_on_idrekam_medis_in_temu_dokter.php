<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('temu_dokter', function (Blueprint $table) {
            // Drop FK first because MySQL requires the unique index for it
            $table->dropForeign(['idrekam_medis']);

            // Remove unique constraint to allow multiple appointments per rekam_medis
            $table->dropUnique('temu_dokter_idrekam_medis_unique');

            // Recreate FK without unique constraint
            $table->foreign('idrekam_medis')->references('idrekam_medis')->on('rekam_medis');
        });
    }

    public function down(): void
    {
        Schema::table('temu_dokter', function (Blueprint $table) {
            $table->dropForeign(['idrekam_medis']);
            $table->unique('idrekam_medis', 'temu_dokter_idrekam_medis_unique');
            $table->foreign('idrekam_medis')->references('idrekam_medis')->on('rekam_medis');
        });
    }
};
