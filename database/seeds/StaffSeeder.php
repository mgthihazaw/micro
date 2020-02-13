<?php

use Illuminate\Database\Seeder;
use App\Staff;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // $staff = Staff::create([
        // 	'person_business_id' => 7,
        //     'business_id' => 2,
        // 	'username' => 'thantzinsoe',
        // 	'password' => bcrypt('thantzinsoe'),
        // ]);
        // $staff->attachRole(2);


        // $staff = Staff::create([
        //     'person_business_id' => 8,
        //     'business_id' => 2,
        //     'username' => 'thihazaw',
        //     'password' => bcrypt('thantzinsoe'),
        // ]);
        // $staff->attachRole(2);

        // $staff = Staff::create([
        //     'person_business_id' => 10,
        //     'business_id' => 2,
        //     'username' => 'mgmg',
        //     'password' => bcrypt('thantzinsoe'),
        // ]);
        // $staff->attachRole(3);

        // $staff = Staff::create([
        //     'person_business_id' => 11,
        //     'business_id' => 2,
        //     'username' => 'zawzaw',
        //     'password' => bcrypt('thantzinsoe'),
        // ]);
        // $staff->attachRole(3);


        // $staff = Staff::create([
        //     'person_business_id' => 12,
        //     'business_id' => 2,
        //     'username' => 'kyawkyaw',
        //     'password' => bcrypt('thantzinsoe'),
        // ]);
        // $staff->attachRole(4);


        $staff = Staff::create([
            'person_business_id' => 13,
            'business_id' => 2,
            'username' => 'administrator',
            'password' => bcrypt('admin'),
        ]);
        $staff->attachRole(1);
    }
}