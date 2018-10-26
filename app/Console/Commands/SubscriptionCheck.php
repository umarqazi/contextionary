<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class AutoRenewal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $cron_controller;

    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check subscriptions';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->cron_controller = new CronController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cron_controller->autoRenewal();
    }
}
