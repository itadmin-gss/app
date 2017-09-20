<?php

class AssetTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('assets')->delete();
        DB::table('assets')->insert([
            'asset_number' => 10001,
            'customer_id' => 2,
            'property_address' => 'Street#20 App B-10 Brooklyn',
            'state_id' => '1',
            'city_id' => '1',
            'zip' => '890049',
            'loan_number' => 'Loan Number',
            'property_type' => 'single family',
            'agent' => 'Agent',
            'property_status' => 'active',
            'electric_status' => 0,
            'water_status' => 1,
            'gas_status' => 0,
            'utility_note' => 'Utility Note over here',
            'status' => 1,
            'lock_box' => 'Lock Box',
            'access_code' => 'Access code over here',
            'brokage' => 'Brokage',
            'outbuilding_shed' => 1,
            'outbuilding_shed_note' => 'Out building Shed note over here',
            'special_direction_note' => 'This is special direction note',
            'swimming_pool' => 'pool',
            'latitude' => '39.149959',
            'longitude' => '-103.1807294',
        ]);

        DB::table('assets')->insert([
            'asset_number' => 14534,
            'customer_id' => 2,
            'property_address' => 'Street#20 App B-10 Brooklyn',
            'state_id' => '1',
            'city_id' => '1',
            'zip' => '890049',
            'loan_number' => 'Loan Number',
            'property_type' => 'single family',
            'agent' => 'Agent',
            'property_status' => 'active',
            'electric_status' => 0,
            'water_status' => 1,
            'gas_status' => 0,
            'utility_note' => 'Utility Note over here',
            'status' => 1,
            'lock_box' => 'Lock Box',
            'access_code' => 'Access code over here',
            'brokage' => 'Brokage',
            'outbuilding_shed' => 1,
            'outbuilding_shed_note' => 'Out building Shed note over here',
            'special_direction_note' => 'This is special direction note',
            'swimming_pool' => 'pool',
            'latitude' => '39.149959',
            'longitude' => '-103.1807294',
        ]);
    }
}
