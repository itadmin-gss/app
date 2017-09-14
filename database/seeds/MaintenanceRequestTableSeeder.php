<?php

class MaintenanceRequestTableSeeder extends Seeder
{

	public function run()
	{
		DB::table('maintenance_requests')->delete();
                DB::table('maintenance_requests')->insert(array('customer_id'=> 2,'asset_id'=> 1,'status' => 1));
                DB::table('maintenance_requests')->insert(array('customer_id'=> 2,'asset_id'=> 1,'status' => 1));
                DB::table('maintenance_requests')->insert(array('customer_id'=> 2,'asset_id'=> 1,'status' => 1));
	}

}