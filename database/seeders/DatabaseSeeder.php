<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Users
      DB::table('users')->insert([
    [
        'Username' => 'admin',
        'Password' => Hash::make('admin123'),
        'Email' => 'admin@perpus.sch.id',
        'NamaLengkap' => 'Administrator',
        'Alamat' => null,
        'Role' => 'admin',
        'NIS' => null,
        'Rayon' => null,
        'Rombel' => null,
        'Barcode' => null,
        'Status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'Username' => 'petugas1',
        'Password' => Hash::make('petugas123'),
        'Email' => 'petugas1@perpus.sch.id',
        'NamaLengkap' => 'Arie Petugas',
        'Alamat' => null,
        'Role' => 'petugas',
        'NIS' => null,
        'Rayon' => null,
        'Rombel' => null,
        'Barcode' => null,
        'Status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'Username' => 'siswa001',
        'Password' => Hash::make('siswa123'),
        'Email' => 'siswa001@school.id',
        'NamaLengkap' => 'Budi Santoso',
        'Alamat' => null,
        'Role' => 'siswa',
        'NIS' => '2024001',
        'Rayon' => 'A',
        'Rombel' => 'XII RPL 1',
        'Barcode' => 'STD-2024001',
        'Status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'Username' => 'siswa002',
        'Password' => Hash::make('siswa123'),
        'Email' => 'siswa002@school.id',
        'NamaLengkap' => 'Sari Dewi',
        'Alamat' => null,
        'Role' => 'siswa',
        'NIS' => '2024002',
        'Rayon' => 'B',
        'Rombel' => 'XII RPL 2',
        'Barcode' => 'STD-2024002',
        'Status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'Username' => 'siswa003',
        'Password' => Hash::make('siswa123'),
        'Email' => 'siswa003@school.id',
        'NamaLengkap' => 'Ahmad Fauzi',
        'Alamat' => null,
        'Role' => 'siswa',
        'NIS' => '2024003',
        'Rayon' => 'A',
        'Rombel' => 'XI RPL 1',
        'Barcode' => 'STD-2024003',
        'Status' => 'aktif',
        'created_at' => now(),
        'updated_at' => now()
    ],
]);

        // Kategori
        DB::table('kategoribuku')->insert([
            ['NamaKategori' => 'Fiksi', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Non-Fiksi', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Sains', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Sejarah', 'created_at' => now(), 'updated_at' => now()],
            ['NamaKategori' => 'Teknologi', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Buku
        $buku = [
            ['Judul' => 'Laskar Pelangi', 'Penulis' => 'Andrea Hirata', 'Penerbit' => 'Bentang Pustaka', 'TahunTerbit' => 2005, 'ISBN' => '978-979-1227-00-1', 'Barcode' => 'BK-0001', 'JumlahTotal' => 5, 'JumlahTersedia' => 4, 'Status' => 'tersedia'],
            ['Judul' => 'Bumi Manusia', 'Penulis' => 'Pramoedya Ananta Toer', 'Penerbit' => 'Lentera Dipantara', 'TahunTerbit' => 1980, 'ISBN' => '978-979-97312-0-1', 'Barcode' => 'BK-0002', 'JumlahTotal' => 3, 'JumlahTersedia' => 2, 'Status' => 'dipinjam'],
            ['Judul' => 'Perahu Kertas', 'Penulis' => 'Dee Lestari', 'Penerbit' => 'Bentang', 'TahunTerbit' => 2009, 'ISBN' => '978-979-1227-25-4', 'Barcode' => 'BK-0003', 'JumlahTotal' => 4, 'JumlahTersedia' => 4, 'Status' => 'tersedia'],
            ['Judul' => 'Harry Potter and the Sorcerer\'s Stone', 'Penulis' => 'J.K. Rowling', 'Penerbit' => 'Gramedia', 'TahunTerbit' => 1997, 'ISBN' => '978-0-439-70818-8', 'Barcode' => 'BK-0004', 'JumlahTotal' => 6, 'JumlahTersedia' => 5, 'Status' => 'tersedia'],
            ['Judul' => 'The Hobbit', 'Penulis' => 'J.R.R. Tolkien', 'Penerbit' => 'HarperCollins', 'TahunTerbit' => 1937, 'ISBN' => '978-0-261-10221-7', 'Barcode' => 'BK-0005', 'JumlahTotal' => 2, 'JumlahTersedia' => 1, 'Status' => 'dipinjam'],
            ['Judul' => 'Sapiens', 'Penulis' => 'Yuval Noah Harari', 'Penerbit' => 'Gramedia', 'TahunTerbit' => 2011, 'ISBN' => '978-0-06-231609-7', 'Barcode' => 'BK-0006', 'JumlahTotal' => 4, 'JumlahTersedia' => 4, 'Status' => 'tersedia'],
            ['Judul' => 'Thinking, Fast and Slow', 'Penulis' => 'Daniel Kahneman', 'Penerbit' => 'Farrar Straus Giroux', 'TahunTerbit' => 2011, 'ISBN' => '978-0-374-27563-1', 'Barcode' => 'BK-0007', 'JumlahTotal' => 3, 'JumlahTersedia' => 3, 'Status' => 'tersedia'],
            ['Judul' => 'Clean Code', 'Penulis' => 'Robert C. Martin', 'Penerbit' => 'Prentice Hall', 'TahunTerbit' => 2008, 'ISBN' => '978-0-13-235088-4', 'Barcode' => 'BK-0008', 'JumlahTotal' => 6, 'JumlahTersedia' => 5, 'Status' => 'tersedia'],
            ['Judul' => 'Introduction to Algorithms', 'Penulis' => 'Thomas H. Cormen', 'Penerbit' => 'MIT Press', 'TahunTerbit' => 2009, 'ISBN' => '978-0-262-03384-8', 'Barcode' => 'BK-0009', 'JumlahTotal' => 3, 'JumlahTersedia' => 2, 'Status' => 'dipinjam'],
            ['Judul' => 'A Brief History of Time', 'Penulis' => 'Stephen Hawking', 'Penerbit' => 'Bantam Books', 'TahunTerbit' => 1988, 'ISBN' => '978-0-553-38016-3', 'Barcode' => 'BK-0010', 'JumlahTotal' => 3, 'JumlahTersedia' => 3, 'Status' => 'tersedia'],
        ];

        foreach ($buku as $b) {
            DB::table('buku')->insert(array_merge($b, ['created_at' => now(), 'updated_at' => now()]));
        }

        // Relasi kategori
        $relasi = [
            [1,1],[2,1],[3,1],[4,1],[5,1], // Fiksi
            [6,2],[7,2], // Non-Fiksi
            [10,3], // Sains
            [8,5],[9,5], // Teknologi
        ];
        foreach ($relasi as $r) {
            DB::table('kategoribuku_relasi')->insert(['BukuID' => $r[0], 'KategoriID' => $r[1]]);
        }

        // Sample peminjaman
        DB::table('peminjaman')->insert([
            ['UserID' => 3, 'BukuID' => 2, 'PetugasID' => 2, 'TanggalPeminjaman' => '2025-04-10', 'TanggalJatuhTempo' => '2025-04-24', 'StatusPeminjaman' => 'terlambat', 'Denda' => 5000, 'StatusDenda' => 'belum_bayar', 'created_at' => now(), 'updated_at' => now()],
            ['UserID' => 4, 'BukuID' => 5, 'PetugasID' => 2, 'TanggalPeminjaman' => '2025-04-15', 'TanggalJatuhTempo' => '2025-04-29', 'StatusPeminjaman' => 'dipinjam', 'Denda' => 0, 'StatusDenda' => 'bebas', 'created_at' => now(), 'updated_at' => now()],
            ['UserID' => 5, 'BukuID' => 9, 'PetugasID' => 2, 'TanggalPeminjaman' => '2025-04-18', 'TanggalJatuhTempo' => '2025-05-02', 'StatusPeminjaman' => 'dipinjam', 'Denda' => 0, 'StatusDenda' => 'bebas', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
