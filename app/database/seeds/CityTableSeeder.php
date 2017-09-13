<?php

class CityTableSeeder extends Seeder {

    public function run() {
        DB::table('cities')->delete();
        DB::table('cities')->insert(array('id' => 1, 'name' => 'Abbeville', 'state_id' => 1, 'status' => 1));
        DB::table('cities')->insert(array('id' => 2, 'name' => 'Abernant', 'state_id' => 1, 'status' => 1));
        DB::table('cities')->insert(array('id' => 3, 'name' => 'Adamsville', 'state_id' => 1, 'status' => 1));
        DB::table('cities')->insert(array('id' => 4, 'name' => 'Addison', 'state_id' => 1, 'status' => 1));
        DB::table('cities')->insert(array('id' => 5, 'name' => 'Adger', 'state_id' => 1, 'status' => 1));

        DB::table('cities')->insert(array('id' => 6, 'name' => 'Adak', 'state_id' => 2, 'status' => 1));
        DB::table('cities')->insert(array('id' => 7, 'name' => 'Akiachak', 'state_id' => 2, 'status' => 1));
        DB::table('cities')->insert(array('id' => 8, 'name' => 'Chefornak', 'state_id' => 2, 'status' => 1));
        DB::table('cities')->insert(array('id' => 9, 'name' => 'Buckland', 'state_id' => 2, 'status' => 1));
        DB::table('cities')->insert(array('id' => 10, 'name' => 'Douglas', 'state_id' => 2, 'status' => 1));


        DB::table('cities')->insert(array('id' => 11, 'name' => 'Aguila', 'state_id' => 3, 'status' => 1));
        DB::table('cities')->insert(array('id' => 12, 'name' => 'Arlington', 'state_id' => 3, 'status' => 1));
        DB::table('cities')->insert(array('id' => 13, 'name' => 'Catalina', 'state_id' => 3, 'status' => 1));
        DB::table('cities')->insert(array('id' => 14, 'name' => 'Central', 'state_id' => 3, 'status' => 1));
        DB::table('cities')->insert(array('id' => 15, 'name' => 'Concho', 'state_id' => 3, 'status' => 1));



        DB::table('cities')->insert(array('id' => 16, 'name' => 'Acampo', 'state_id' => 4, 'status' => 1));
        DB::table('cities')->insert(array('id' => 17, 'name' => 'Adelanto', 'state_id' => 4, 'status' => 1));
        DB::table('cities')->insert(array('id' => 18, 'name' => 'Angwin', 'state_id' => 4, 'status' => 1));
        DB::table('cities')->insert(array('id' => 19, 'name' => 'Baker', 'state_id' => 4, 'status' => 1));
        DB::table('cities')->insert(array('id' => 20, 'name' => 'Big Bar', 'state_id' => 4, 'status' => 1));
    }

}
