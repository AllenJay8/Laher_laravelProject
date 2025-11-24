<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name'=>'Basic', 'price'=>19.99, 'duration_in_months'=>1, 'description'=>'Access to gym equipment during staffed hours.'],
            ['name'=>'Standard', 'price'=>49.99, 'duration_in_months'=>3, 'description'=>'Includes classes and basic trainer sessions.'],
            ['name'=>'Premium', 'price'=>89.99, 'duration_in_months'=>6, 'description'=>'Full access, classes and 1-on-1 trainer.'],
            ['name'=>'Annual', 'price'=>299.99, 'duration_in_months'=>12, 'description'=>'Best value annual plan.'],
            ['name'=>'Trial', 'price'=>0.00, 'duration_in_months'=>1, 'description'=>'7-day trial for new members.'],
        ];
        foreach ($plans as $p) {
            Plan::create($p);
        }
    }
}
