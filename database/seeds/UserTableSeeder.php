<?php

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
 
        DB::table('users')->insert(array(
            'id' => 1,
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'username' => 'admin',
            'profile_status' => 1,
            'status' => 1,
            'type_id' => 1,
            'user_role_id' => 1,
            'email' => 'admin@invortex.com',
            'password' => Hash::make('123123'),
            'latitude' => '39.149959',
            'longitude' => '-103.1807294',
        ));


        DB::table('users')->insert(array(
            'id' => 2,
            'first_name' => 'Customer',
            'last_name' => 'customer',
            'username' => 'customer',
            'profile_status' => 1,
            'status' => 1,
            'type_id' => 2,
            'user_role_id' => 1,
            'email' => 'customer@invortex.com',
            'password' => Hash::make('123123'),
            'latitude' => '39.149959',
            'longitude' => '-103.1807294',
        ));


        DB::table('users')->insert(array(
            'id' => '3',
            'first_name' => 'Vendor',
            'last_name' => 'Vendor',
            'username' => 'vendor',
            'profile_status' => 1,
            'status' => 1,
            'type_id' => 3,
            'user_role_id' => 1,
            'email' => 'vendor@invortex.com',
            'password' => Hash::make('123123'),
            'latitude' => '39.149959',
            'longitude' => '-103.1807294',
        ));
    }
}
