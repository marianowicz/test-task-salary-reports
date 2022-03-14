<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // @TODO maybe I should just use Eloquent models and factories? :P
        $hrId = DB::table('departments')->insertGetId(
            ['name' => 'HR', 'allowance_type' => 'seniority', 'allowance_value' => 100.0]
        );

        $customerServiceId = DB::table('departments')->insertGetId(
            ['name' => 'Customer Service', 'allowance_type' => 'percentage', 'allowance_value' => 10.0]
        );

        DB::table('employees')->insert([
            [
                'department_id' => $hrId,
                'first_name' => 'Adam',
                'last_name' => 'Kowalski',
                'joined_at' => '2010-03-10',
                'base_salary' => 1000.00
            ],
            [
                'department_id' => $customerServiceId,
                'first_name' => 'Ania',
                'last_name' => 'Nowak',
                'joined_at' => '2017-03-10',
                'base_salary' => 1100.00
            ],
            [
                'department_id' => $customerServiceId,
                'first_name' => 'Kazimierz',
                'last_name' => 'Nowacki',
                'joined_at' => '2019-03-10',
                'base_salary' => 1000.00
            ]
        ]);
    }
}
