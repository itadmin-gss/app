<?php

class StateTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('states')->delete();
                DB::table('states')->insert(array('id'=>1, 'name'=> 'Alabama', 'status' => 1));
                DB::table('states')->insert(array('id'=>2, 'name'=> 'Alaska', 'status' => 1));
                 DB::table('states')->insert(array('id'=>3, 'name'=> 'Arizona', 'status' => 1));
                  DB::table('states')->insert(array('id'=>4, 'name'=> 'California', 'status' => 1));
    }
}
