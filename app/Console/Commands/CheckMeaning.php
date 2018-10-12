<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use App\Services\ContributorService;
use Illuminate\Console\Command;

class CheckMeaning extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meaning:vote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checking meaning available for vote, we are checking total number of meaning and if total no of meaning is less than 50 then we are checking expiry date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    /**
     * CheckMeaning constructor.
     *
     */
    protected $contributor;
    public function __construct()
    {
        parent::__construct();
        $cronController=new CronController();
        $this->contributor=$cronController;

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->contributor->meaningToVote();
        $this->contributor->checkExpiredVotes();
    }
}
