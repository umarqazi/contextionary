<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(RolesTableSeeder::class);
         $this->call(AdminTablesSeeder::class);
         $this->call(SettingsTableSeeder::class);
         $this->call(TutorialsTableSeeder::class);
         $this->call(FunFactsTableSeeder::class);
         $this->call(GlossaryTableSeeder::class);
    }
}
