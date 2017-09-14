<?php

class UserTypeTableSeeder extends Seeder {

    public function run() {
        DB::table('user_types')->delete();
        DB::table('user_types')->insert(array('id' => 1 , 'title' => 'admin', 'status' => 1));
        DB::table('user_types')->insert(array('id' => 2 , 'title' => 'customer', 'status' => 1));
        DB::table('user_types')->insert(array('id' => 3 ,'title' => 'vendors', 'status' => 1));
        DB::table('user_types')->insert(array('id' => 4 ,'title' => 'user', 'status' => 1));
    }

}
