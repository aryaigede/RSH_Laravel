<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('temu_dokter', function (Blueprint $table) {
            $table->integerIncrements('idreservasi_dokter');
            $table->integer('no_urut')->nullable();
            $table->timestamp('waktu_daftar')->useCurrent();
            $table->char('status', 1)->default('N');
            $table->integer('idpet');
            $table->integer('idrole_user')->nullable();
            $table->integer('idrekam_medis')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->integer('deleted_by')->nullable();

            $table->foreign('idpet')->references('idpet')->on('pet')->onDelete('cascade');
            $table->foreign('idrole_user')->references('idrole_user')->on('role_user');
            $table->foreign('idrekam_medis')->references('idrekam_medis')->on('rekam_medis');

            $table->unique('idrekam_medis');
            $table->index(['waktu_daftar', 'no_urut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temu_dokter');
    }
};
