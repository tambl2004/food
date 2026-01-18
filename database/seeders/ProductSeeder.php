<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sử dụng ProductFactory để tạo ra 15 sản phẩm laptop mẫu
        \App\Models\Product::factory()->count(15)->create();
    }
}
