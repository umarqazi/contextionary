<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GlossaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('glossary_items')->insert([
            'id'            =>  1,
            'name'          =>  'The Red Ball',
            'price'         =>  '100',
            'description'   =>  '<div>The Red Ball&#39;s description</div>',
            'thumbnail'     =>  'images/glossary/thumb/book-cover.jpg',
            'file'          =>  'images/glossary/files/Advertisement No 26-2018.pdf',
            'url'           =>  'http://www.google.com.pk/',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        DB::table('glossary_items')->insert([
            'id'            =>  2,
            'name'          =>  'The White Hat',
            'price'         =>  '100',
            'description'   =>  '<div>The White Hat&#39;s description</div>',
            'thumbnail'     =>  'images/glossary/thumb/book-cover.jpg',
            'file'          =>  'images/glossary/files/Advertisement No 26-2018.pdf',
            'url'           =>  'http://www.google.com.pk/',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
