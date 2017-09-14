<?php

class UserRoleTableSeeder extends Seeder {

    public function run() {

        DB::table('user_roles')->delete();
        DB::table('user_roles')->insert(array('id' => 1, 'role_name' => 'Admin', 'description' => 'This is the admin role', 'status' => 1));
        DB::table('user_roles')->insert(array('id' => 2, 'role_name' => 'Customer', 'description' => 'This is the customer role', 'status' => 1));
        DB::table('user_roles')->insert(array('id' => 3, 'role_name' => 'Vendors', 'description' => 'This is the vendor role', 'status' => 1));

        DB::table('user_roles')->insert(array('id' => 4, 'role_name' => 'Manager', 'description' => 'This is the manager manaigin the services', 'status' => 1));
        DB::table('user_roles')->insert(array('id' => 5, 'role_name' => 'Admin Assitant', 'description' => 'He will be assisting the Admin', 'status' => 1));
    }

}
