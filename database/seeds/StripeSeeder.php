<?php

use Carbon\Carbon;
use Cartalyst\Stripe\Stripe;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StripeSeeder extends Seeder
{
    /**
     * @var Stripe
     */
    protected $stripe;

    /**
     * FunFactsTableSeeder constructor.
     */
    public function __construct(){
        $this->stripe   =   Stripe::make(env('STRIPE_SECRET'));
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $plan = $this->stripe->plans()->create([
            'name'                 => 'Basic Plan',
            'amount'               =>  2.99,
            'currency'             => 'USD',
            'interval'             => 'month',
            'statement_descriptor' => 'Monthly Basic Plan.',
        ]);
        DB::table('plans')->insert([
            'id'            =>  1,
            'plan_id'       =>  $plan['id'],
            'name'          => 'Basic Plan',
            'amount'        =>  2.99,
            'currency'      => 'USD',
            'interval'      => 'month',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        $plan = $this->stripe->plans()->create([
            'name'                 => 'Premium Plan',
            'amount'               =>  9.99,
            'currency'             => 'USD',
            'interval'             => 'month',
            'statement_descriptor' => 'Monthly Premium Plan.',
        ]);
        DB::table('plans')->insert([
            'id'            =>  2,
            'plan_id'       =>  $plan['id'],
            'name'          => 'Premium Plan',
            'amount'        =>  9.99,
            'currency'      => 'USD',
            'interval'      => 'month',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        $plan = $this->stripe->plans()->create([
            'name'                 => 'Advance Plan',
            'amount'               =>  6.99,
            'currency'             => 'USD',
            'interval'             => 'month',
            'statement_descriptor' => 'Monthly Advance Plan.',
        ]);
        DB::table('plans')->insert([
            'id'            =>  3,
            'plan_id'       =>  $plan['id'],
            'name'          => 'Advance Plan',
            'amount'        =>  6.99,
            'currency'      => 'USD',
            'interval'      => 'month',
            'created_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at'    =>  Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
