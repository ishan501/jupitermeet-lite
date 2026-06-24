<?php
namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create([
            'name' => 'Default Plan',
            'description' => 'This is a default plan',
            'currency' => 'USD',
            'decimals' => null,
            'amount_month' => '0',
            'amount_year' => '0',
            'coupons' => null,
            'tax_rates' => null,
            'visibility' => null,
            'features' => [
                'text_chat' => '1',
                'file_share' => '1',
                'screen_share' => '1',
                'whiteboard' => '1',
                'hand_raise' => '1',
                'meeting_no' => '50',
                'time_limit' => '60',
                'recording' => '1',
                'user_limit' => '10',
                'chatgpt' => '1',
                'video_quality' => 'VGA',
                'max_filesize' => '10',
            ],
            'color' => null,
            'status' => '1',
        ]);

    }
}