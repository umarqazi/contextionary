<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            'id'            =>  1,
            'keys'          =>  'Address',
            'values'        =>  '1646 McIntyre Street Ann Arbor, MI 48105 United States',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  2,
            'keys'          =>  'Office Hours Monday',
            'values'        =>  'Mon : 8am–6pm',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  3,
            'keys'          =>  'Office Hours Tuesday',
            'values'        =>  'Mon : 8am–6pm',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  4,
            'keys'          =>  'Office Hours Wednesday',
            'values'        =>  'Mon : 8am–6pm',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  5,
            'keys'          =>  'Office Hours Thursday',
            'values'        =>  'Mon : 8am–6pm',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  6,
            'keys'          =>  'Office Hours Friday',
            'values'        =>  'Mon : 10am–4pm',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  7,
            'keys'          =>  'Email',
            'values'        =>  'gfotso@contextionary.com',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  8,
            'keys'          =>  'Phone',
            'values'        =>  '+1 734-747-4294',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('settings')->insert([
            'id'            =>  9,
            'keys'          =>  'Feedback Question',
            'values'        =>  'What is your feedback of the site?',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
