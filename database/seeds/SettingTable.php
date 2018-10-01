<?php

use Illuminate\Database\Seeder;
use App\Setting;

class SettingTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['keys' => 'office_hours_monday', 'values'=>'Monday : 8am–6pm']);
        Role::create(['keys' => 'office_hours_tuesday', 'values'=>'Tuesday : 8am–6pm']);
        Role::create(['keys' => 'office_hours_wednesday', 'values'=>'Wednesday : 8am–6pm']);
        Role::create(['keys' => 'office_hours_thursday', 'values'=>'Thursday : 8am–6pm']);
        Role::create(['keys' => 'office_hours_friday', 'values'=>'Friday : 8am–6pm']);
        Role::create(['keys' => 'address', 'values'=>'1646 McIntyre Street Ann Arbor, MI 48105 United States']);
        Role::create(['keys' => 'email', 'values'=>'gfotso@contextionary.com']);
        Role::create(['keys' => 'phone', 'values'=>'+1 734-747-4294']);
        Role::create(['keys' => 'total_context', 'values'=>'27']);
        Role::create(['keys' => 'minimum_bids', 'values'=>'20']);
        Role::create(['keys' => 'minimum_votes', 'values'=>'20']);
        Role::create(['keys' => 'bid_expiry_days', 'values'=>'20']);
        Role::create(['keys' => 'vote_expiry_days', 'values'=>'20']);
        Role::create(['keys' => 'selected_bids', 'values'=>'9']);
    }
}
