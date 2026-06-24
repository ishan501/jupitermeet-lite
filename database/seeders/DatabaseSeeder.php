<?php

namespace Database\Seeders;

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
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(PlanSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(EmailTemplateSeeder::class);
        $this->call(PageSeeder::class);

    }
}