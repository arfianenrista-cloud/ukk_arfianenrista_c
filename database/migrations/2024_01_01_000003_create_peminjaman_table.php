<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('PeminjamanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->unsignedBigInteger('PetugasID')->nullable()->comment('Petugas yg memproses');
            $table->date('TanggalPeminjaman');
            $table->date('TanggalJatuhTempo');
            $table->date('TanggalPengembalian')->nullable();
            $table->enum('StatusPeminjaman', ['dipinjam', 'dikembalikan', 'terlambat', 'hilang', 'rusak'])->default('dipinjam');
            $table->decimal('Denda', 10, 2)->default(0)->comment('Denda dalam rupiah');
            $table->enum('StatusDenda', ['belum_bayar', 'sudah_bayar', 'bebas'])->default('bebas');
            $table->text('Catatan')->nullable();
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('ulasanbuku', function (Blueprint $table) {
            $table->id('UlasanID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->text('Ulasan')->nullable();
            $table->integer('Rating')->default(0);
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('koleksipribadi', function (Blueprint $table) {
            $table->id('KoleksiID');
            $table->unsignedBigInteger('UserID');
            $table->unsignedBigInteger('BukuID');
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('cascade');
            $table->foreign('BukuID')->references('BukuID')->on('buku')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('UserID')->nullable();
            $table->string('NamaPelaku', 255)->nullable();
            $table->string('RolePelaku', 50)->nullable();
            $table->string('Kegiatan', 255);
            $table->text('Keterangan')->nullable();
            $table->string('IPAddress', 50)->nullable();
            $table->foreign('UserID')->references('UserID')->on('users')->onDelete('set null');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('koleksipribadi');
        Schema::dropIfExists('ulasanbuku');
        Schema::dropIfExists('peminjaman');
    }
};
