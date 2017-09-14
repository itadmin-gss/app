<?php

class StateTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('states')->delete();
                DB::table('states')->insert(['id'=>1, 'name'=> 'Alabama', 'status' => 1]);
                DB::table('states')->insert(['id'=>2, 'name'=> 'Alaska', 'status' => 1]);
                 DB::table('states')->insert(['id'=>3, 'name'=> 'Arizona', 'status' => 1]);
                  DB::table('states')->insert(['id'=>4, 'name'=> 'California', 'status' => 1]);
    }
}
