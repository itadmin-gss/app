<?php

class AccessFunctionTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('access_functions')->delete();
        DB::statement('ALTER TABLE access_functions AUTO_INCREMENT = 1;');
        DB::table('access_functions')->insert([ 'access_function' => 'Users']);
        DB::table('access_functions')->insert([ 'access_function' => 'Customer']);
        DB::table('access_functions')->insert([ 'access_function' => 'Asset']);
        DB::table('access_functions')->insert([ 'access_function' => 'Vendor']);
        DB::table('access_functions')->insert([ 'access_function' => 'Order Request']);
        DB::table('access_functions')->insert([ 'access_function' => 'Service']);
        DB::table('access_functions')->insert([ 'access_function' => 'Order']);
        DB::table('access_functions')->insert([ 'access_function' => 'Completed Request']);
        DB::table('access_functions')->insert([ 'access_function' => 'Invoice']);
        DB::table('access_functions')->insert([ 'access_function' => 'Special Price']);
    }
}
