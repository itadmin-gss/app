<?php

class AssignedServiceTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('assign_requests')->delete();
                DB::statement('ALTER TABLE assign_requests AUTO_INCREMENT = 1;');
                DB::table('assign_requests')->insert(array('request_id'=> 3,'requested_service_id'=> 1,'vendor_id'=>4,'status' => 1));
                DB::table('assign_requests')->insert(array('request_id'=> 3,'requested_service_id'=> 2,'vendor_id'=>4,'status' => 1));
                DB::table('assign_requests')->insert(array('request_id'=> 3,'requested_service_id'=> 3,'vendor_id'=>4,'status' => 1));

                DB::table('assign_requests')->insert(array('request_id'=> 4,'requested_service_id'=> 1,'vendor_id'=>4,'status' => 1));
                DB::table('assign_requests')->insert(array('request_id'=> 4,'requested_service_id'=> 2,'vendor_id'=>4,'status' => 1));
                DB::table('assign_requests')->insert(array('request_id'=> 4,'requested_service_id'=> 3,'vendor_id'=>4,'status' => 1));
	}

}