<?php

class RoleFunctionTableSeeder extends Seeder
{

    public function run()
    {
            DB::table('role_functions')->delete();
            DB::statement('ALTER TABLE role_functions AUTO_INCREMENT = 1;');
            DB::table('role_functions')->insert(['role_function'=>'User',
            'status'=>1,'access_function_id'=>1]);
            DB::table('role_functions')->insert([ 'role_function'=>'Access Level',
            'status'=>1,'access_function_id'=>1]);
            DB::table('role_functions')->insert([ 'role_function'=>'Access Rights',
            'status'=>1,'access_function_id'=>1]);
            DB::table('role_functions')->insert([ 'role_function'=>'Customer',
            'status'=>1,'access_function_id'=>2]);
            DB::table('role_functions')->insert(['role_function'=>'Asset',
            'status'=>1,'access_function_id'=>3]);
            DB::table('role_functions')->insert([ 'role_function'=>'Vendor',
            'status'=>1,'access_function_id'=>4]);
            DB::table('role_functions')->insert([ 'role_function'=>'Maintenance Request',
            'status'=>1,'access_function_id'=>5]);
            DB::table('role_functions')->insert([ 'role_function'=>'Service',
            'status'=>1,'access_function_id'=>6]);
            DB::table('role_functions')->insert([ 'role_function'=>'Order',
            'status'=>1,'access_function_id'=>7]);
            DB::table('role_functions')->insert([ 'role_function'=>'Completed Request',
            'status'=>1,'access_function_id'=>8]);
            DB::table('role_functions')->insert([ 'role_function'=>'Invoice',
            'status'=>1,'access_function_id'=>9]);
            DB::table('role_functions')->insert([ 'role_function'=>'Special Price',
            'status'=>1,'access_function_id'=>10]);
    }
}
