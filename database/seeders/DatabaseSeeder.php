<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(1)->create(); // Tạo 1 user thường
    
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'role' => UserRole::ADMIN, // Gán quyền admin
        ]);
    
        $this->call([
            ProductSeeder::class,
            NewsSeeder::class,
            ReviewSeeder::class,
            FaqSeeder::class,
            FavoriteDishSeeder::class,
        ]);
    }
}
