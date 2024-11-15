<?php

namespace Database\Seeders;

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
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
        User::factory()->create([
            'name' => 'Benyamin Ghafourian',
            'email' => 'benyamin.computer2000@gmail.com',
        ]);

        User::factory(10)->create();

        // cant generate more than 10, because we have unique constraint on name,
        // and defined only 10 values in CategoryFactory
        Category::factory(10)->create();

        Author::factory(10)->create();

        NewsSource::factory(10)->create();
    }
}
