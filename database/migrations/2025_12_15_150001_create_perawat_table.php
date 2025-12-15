<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('perawat', function (Blueprint $table) {
            $table->integer('id_perawat')->autoIncrement();
            $table->string('alamat', 100)->nullable();
            $table->string('no_hp', 45)->nullable();
            $table->string('jenis_kelamin', 20)->nullable();
            $table->string('pendidikan', 100)->nullable();
            $table->bigInteger('id_user');
            $table->timestamp('deleted_at')->nullable();
            $table->bigInteger('deleted_by')->nullable();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('deleted_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('perawat');
    }
};
