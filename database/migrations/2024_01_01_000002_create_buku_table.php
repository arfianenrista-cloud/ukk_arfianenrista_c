<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku', function (Blueprint $table) {
            $table->id('BukuID');
            $table->string('Judul', 255);
            $table->string('Penulis', 255);
            $table->string('Penerbit', 255)->nullable();
            $table->year('TahunTerbit')->nullable();
            $table->string('ISBN', 50)->nullable()->unique();
            $table->string('Barcode', 100)->nullable()->unique();
            $table->integer('JumlahTotal')->default(1);
            $table->integer('JumlahTersedia')->default(1);
            $table->enum('Kondisi', ['baik', 'rusak', 'hilang'])->default('baik');
            $table->enum('Status', ['tersedia', 'dipinjam'])->default('tersedia');
            $table->text('Deskripsi')->nullable();
            $table->string('CoverImage', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('kategoribuku', function (Blueprint $table) {
            $table->id('KategoriID');
            $table->string('NamaKategori', 255);
            $table->timestamps();
        });

        Schema::create('kategoribuku_relasi', function (Blueprint $table) {
            $table->id('KategoriBukuID');
            $table->unsignedBigInteger('BukuID');
            $table->unsignedBigInteger('KategoriID');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->foreign('KategoriID')->references('KategoriID')->on('kategoribuku')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategoribuku_relasi');
        Schema::dropIfExists('kategoribuku');
        Schema::dropIfExists('buku');
    }
};
