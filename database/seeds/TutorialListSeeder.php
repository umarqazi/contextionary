<?php

use Illuminate\Database\Seeder;
use App\TutorialList;

class TutorialListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('tutorial_lists')->truncate();
    }
}
