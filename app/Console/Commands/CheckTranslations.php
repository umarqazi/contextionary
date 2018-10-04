<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class CheckTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $translationssBids;

    protected $signature = 'translations:bids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check total number of bids and votes against translations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->translationssBids=new CronController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->translationssBids->translateBidtoVote();
        $this->translationssBids->translateVote();
    }
}
