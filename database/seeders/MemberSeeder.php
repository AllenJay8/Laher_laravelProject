<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $plans = Plan::pluck('id')->toArray();
        $sample = [
            ['full_name'=>'Alice Walker','email'=>'alice@example.test','phone'=>'+10000000001','joined_date'=>now()->subMonths(2)->toDateString(),'plan_id'=>$plans[array_rand($plans)]],
            ['full_name'=>'Bob Martin','email'=>'bob@example.test','phone'=>'+10000000002','joined_date'=>now()->subMonths(1)->toDateString(),'plan_id'=>$plans[array_rand($plans)]],
            ['full_name'=>'Carol Smith','email'=>'carol@example.test','phone'=>'+10000000003','joined_date'=>now()->subDays(20)->toDateString(),'plan_id'=>null],
            ['full_name'=>'David Jones','email'=>'david@example.test','phone'=>'+10000000004','joined_date'=>now()->subDays(10)->toDateString(),'plan_id'=>$plans[array_rand($plans)]],
            ['full_name'=>'Eve Johnson','email'=>'eve@example.test','phone'=>'+10000000005','joined_date'=>now()->toDateString(),'plan_id'=>$plans[array_rand($plans)]],
        ];
        foreach ($sample as $m) {
            Member::create($m);
        }
    }
}
