<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([CourseSeeder::class]);
        $this->call([UserSeeder::class]);
        $this->call([GroupSeeder::class]);
        $this->call([RubricSeeder::class]);
    }
}
