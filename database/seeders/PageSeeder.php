<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('pages')->insert([
            'title' => 'Home',
            'slug' => 'home',
            'footer' => 'no',
            'content' => 'Major Upgrade. Maximum Control — Host Your Own Enhanced Video Meetings!'
        ]);

        DB::table('pages')->insert([
            'title' => 'Privacy Policy',
            'slug' => 'privacy-policy',
            'footer' => 'yes',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
        ]);

        DB::table('pages')->insert([
            'title' => 'Terms & Conditions',
            'slug' => 'terms-and-conditions',
            'footer' => 'yes',
            'content' => 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.'
        ]);

        DB::table('pages')->insert([
            'title' => 'Thank You',
            'slug' => 'thank-you',
            'footer' => 'no',
            'content' => 'Thank you for participating in the meeting!'
        ]);
    }
}
