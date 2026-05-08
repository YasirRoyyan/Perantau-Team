<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AssessmentDataSeeder::class);
        $this->call(SiteContentSeeder::class);

        User::updateOrCreate(['email' => 'admin@interiology.test'], [
            'name' => 'Admin Interiology',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'city' => 'Bandung',
            'bio' => 'Mengelola data pengguna dan alur asesmen Interiology.',
        ]);

        User::updateOrCreate(['email' => 'user@interiology.test'], [
            'name' => 'User Interiology',
            'password' => Hash::make('password'),
            'role' => 'user',
            'city' => 'Bandung',
            'bio' => 'Mencari inspirasi gaya ruang tamu yang paling cocok.',
        ]);
    }
}
