<?php

class DatabaseSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Eloquent::unguard();
        $this->call('StateTableSeeder');
        $this->call('CityTableSeeder');
        $this->call('UserTypeTableSeeder');
        $this->call('UserRoleTableSeeder');
        $this->call('AccessFunctionTableSeeder');
        $this->call('RoleFunctionTableSeeder');
        $this->call('UserRoleDetailTableSeeder');


        $this->call('UserTableSeeder');
        $this->call('AssetTableSeeder');

        $this->call('ServiceTableSeeder');
        //Dummy Coommet for testing
    }

}
