<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categories; // Import model Categories
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menambahkan User default
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // Menambahkan kategori
        Categories::create([
            'name' => 'Drama',
            'slug' => 'drama',
        ]);

        Categories::create([
            'name' => 'Fiksi',
            'slug' => 'fiksi',
        ]);

        Categories::create([
            'name' => 'Science',
            'slug' => 'science',
        ]);

        Categories::create([
            'name' => 'Sejarah',
            'slug' => 'sejarah',
        ]);

        Categories::create([
            'name' => 'Pendidikan',
            'slug' => 'pendidikan',
        ]);

        Categories::create([
            'name' => 'Agama',
            'slug' => 'agama',
        ]);

        Categories::create([
            'name' => 'Filsafat',
            'slug' => 'filsafat',
        ]);

        Categories::create([
            'name' => 'Kesehatan',
            'slug' => 'kesehatan',
        ]);

        Categories::create([
            'name' => 'Teknologi',
            'slug' => 'teknologi',
        ]);

        Categories::create([
            'name' => 'Sosial',
            'slug' => 'sosial',
        ]);

        Categories::create([
            'name' => 'Ekonomi',
            'slug' => 'ekonomi',
        ]);

        Categories::create([
            'name' => 'Psikologi',
            'slug' => 'psikologi',
        ]);
    }
}
