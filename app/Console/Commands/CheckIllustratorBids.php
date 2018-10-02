<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class CheckIllustratorBids extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $illustratorsBids;

    protected $signature = 'illustrator:bids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check total number of bids against illustrators';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->illustratorsBids=new CronController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->illustratorsBids->illustratorBidtoVote();
        $this->illustratorsBids->checkIllustratorVotes();
    }
}
