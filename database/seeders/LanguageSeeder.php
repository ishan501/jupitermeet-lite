<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Language::insert([
            [
                'code' => 'en',
                'name' => 'English',
                'direction' => 'ltr',
                'default' => 'yes',
                'status' => 'active',
            ],
            [
                'code' => 'es',
                'name' => 'Spanish',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'fr',
                'name' => 'French',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'it',
                'name' => 'Italian',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'de',
                'name' => 'German',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'zh-CN',
                'name' => 'Chinese (Simplified)',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'ar',
                'name' => 'Arabic',
                'direction' => 'rtl',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'ru',
                'name' => 'Russian',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'pt',
                'name' => 'Portuguese',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
            [
                'code' => 'tr',
                'name' => 'Turkish',
                'direction' => 'ltr',
                'default' => 'no',
                'status' => 'active',
            ],
        ]);
    }
}
