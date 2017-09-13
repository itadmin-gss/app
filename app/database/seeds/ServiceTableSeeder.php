<?php

class ServiceTableSeeder extends Seeder
{

	public function run()
        {
            DB::table('services')->delete();

            DB::table('services')->insert(array('title'=>'interior / Exterior Painting','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Flooring (Carpet/ Wood/ Tile)','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Drywall - New/ Repairs','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Countertops/ Cabinets','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>' Carpentry','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Cleaning/ Trush Removal','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Electrical','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Garage Doors','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Plumbing','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Fences','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Ceramic Tile','price'=>'','status'=>'1'));
            DB::table('services')->insert(array('title'=>'Roofing','price'=>'','status'=>'1'));
            
            
        }

}