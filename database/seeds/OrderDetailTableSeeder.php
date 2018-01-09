<?php

class OrderDetailTableSeeder extends Seeder
{

    public function run()
    {
            DB::table('order_details')->delete();

            DB::table('order_details')->insert(['order_id'=>1,'service_id'=>'1','status'=>'1']);
            DB::table('order_details')->insert(['order_id'=>1,'service_id'=>'2','status'=>'1']);
            DB::table('order_details')->insert(['order_id'=>1,'service_id'=>'3','status'=>'1']);
            
            DB::table('order_details')->insert(['order_id'=>2,'service_id'=>'1','status'=>'0']);
            DB::table('order_details')->insert(['order_id'=>2,'service_id'=>'2','status'=>'1']);
            DB::table('order_details')->insert(['order_id'=>2,'service_id'=>'3','status'=>'0']);
    }
}
