<?php

class RequestedServiceTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('requested_services')->delete();
                DB::table('requested_services')->insert(array('request_id'=> 1,'service_id'=> 1,'status' => 1));
                DB::table('requested_services')->insert(array('request_id'=> 1,'service_id'=> 2,'status' => 1));
                DB::table('requested_services')->insert(array('request_id'=> 1,'service_id'=> 3,'status' => 1));
                DB::table('requested_services')->insert(array('request_id'=> 2,'service_id'=> 1,'status' => 1));
                DB::table('requested_services')->insert(array('request_id'=> 2,'service_id'=> 2,'status' => 1));
                DB::table('requested_services')->insert(array('request_id'=> 2,'service_id'=> 3,'status' => 1));
    }
}
