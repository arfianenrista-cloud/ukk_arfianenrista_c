<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('UserID');
            $table->string('Username', 255)->unique();
            $table->string('Password', 255);
            $table->string('Email', 255)->unique()->nullable();
            $table->string('NamaLengkap', 255)->nullable();
            $table->text('Alamat')->nullable();
            $table->enum('Role', ['admin', 'petugas', 'siswa'])->default('siswa');
            $table->string('NIS', 20)->nullable()->comment('Nomor Induk Siswa');
            $table->string('Rayon', 100)->nullable();
            $table->string('Rombel', 100)->nullable();
            $table->string('Barcode', 100)->nullable()->unique();
            $table->enum('Status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
