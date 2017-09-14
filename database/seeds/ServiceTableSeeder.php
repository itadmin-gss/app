<?php

class ServiceTableSeeder extends Seeder
{

    public function run()
    {
            DB::table('services')->delete();

            DB::table('services')->insert(['title'=>'interior / Exterior Painting','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Flooring (Carpet/ Wood/ Tile)','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Drywall - New/ Repairs','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Countertops/ Cabinets','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>' Carpentry','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Cleaning/ Trush Removal','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Electrical','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Garage Doors','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Plumbing','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Fences','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Ceramic Tile','price'=>'','status'=>'1']);
            DB::table('services')->insert(['title'=>'Roofing','price'=>'','status'=>'1']);
    }
}
