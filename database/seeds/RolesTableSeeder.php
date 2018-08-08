<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'basic plan']);
        Role::create(['name' => 'premium plan']);
        Role::create(['name' => 'advance plan']);
        Role::create(['name' => 'illustrator']);
        Role::create(['name' => 'definition']);
        Role::create(['name' => 'translator']);
    }
}
