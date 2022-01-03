<?php

use Illuminate\Database\Seeder;
use App\Model\PlanSubscription;
use App\Model\PlanSubscriptionFeature;
use Illuminate\Support\Facades\DB;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('plan_subscription')->truncate();

        $plan_data = [
            [
                'plan_name' => "Normal User",
                'plan_price_label' => "Free",
                'plan_price' => NULL,
                'plan_feature' => [
                    [
                        'feature_label' => 'Test1 Normal '
                    ],
                    [
                        'feature_label' => 'Test2 Normal '
                    ],
                    [
                        'feature_label' => 'Test3 Normal '
                    ]
                ]
            ],
            [
                'plan_name' => "Premium Users",
                'plan_price_label' => "First Month Free",
                'plan_price' => "20",
                'plan_feature' => [
                    [
                        'feature_label' => 'Test1 Premium '
                    ],
                    [
                        'feature_label' => 'Test2 Premium '
                    ],
                    [
                        'feature_label' => 'Test3 Premium '
                    ]
                ]
            ],
            [
                'plan_name' => "Business Users",
                'plan_price_label' => "First Month Free",
                'plan_price' => "50",
                'plan_feature' => [
                    [
                        'feature_label' => 'Test1 Business '
                    ],
                    [
                        'feature_label' => 'Test2 Business '
                    ],
                    [
                        'feature_label' => 'Test3 Business '
                    ]
                ]
            ],
        ];
        foreach ($plan_data as $key => $value) {
            $last_plan_id = PlanSubscription::insertGetId([
                'plan_name' => $value['plan_name'],
                'plan_price_label' => $value['plan_price_label'],
                'plan_price' => $value['plan_price'],
            ]);
            foreach ($value['plan_feature'] as $key1 => $value1) {
                PlanSubscriptionFeature::insert([
                    'plan_subscription_id' => $last_plan_id,
                    'feature_label' => $value1['feature_label'],
                ]);
            }
        }
    }
}
