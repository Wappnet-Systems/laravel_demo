<?php

use Illuminate\Database\Seeder;

use App\Model\ActivityPoints;

class ActivityPointsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ActivityPoints::truncate();
        $data = [
            ['activity' => "User Spend Time", 'point' => '2'],
            ['activity' => "Create Survey", 'point' => '2'],
            ['activity' => "Create Contest", 'point' => '2'],
            ['activity' => "Create Advertisement", 'point' => '2'],
            ['activity' => "Fill Survey", 'point' => '2'],
            ['activity' => "View Survey", 'point' => '2'],
            ['activity' => "Favourite Survey", 'point' => '2'],
            ['activity' => "Fill Contest", 'point' => '2'],
            ['activity' => "View Contest", 'point' => '2'],
            ['activity' => "Favourite Contest", 'point' => '2'],
            ['activity' => "Click Advertisement", 'point' => '2'],
            ['activity' => "View Advertisement", 'point' => '2'],
            ['activity' => "Survey Comment", 'point' => '2'],
            ['activity' => "Contest Comment", 'point' => '2'],
            ['activity' => "Survey Winner Declaration", 'point' => '2'],
            ['activity' => "Contest Winner Declaration", 'point' => '2'],
            ['activity' => "Friend Request Send", 'point' => '2'],
            ['activity' => "Friend Request Accept", 'point' => '2'],
            ['activity' => "Use Your Referral Link", 'point' => '5'],
            ['activity' => "Share Survey Link Via Our Chat", 'point' => '2'],
            ['activity' => "Share Contest Link Via Our Chat", 'point' => '2'],
        ];

        ActivityPoints::insert($data);
    }
}
