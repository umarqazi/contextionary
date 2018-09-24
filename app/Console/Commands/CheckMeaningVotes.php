<?php

namespace App\Console\Commands;

use App\Http\Controllers\CronController;
use Illuminate\Console\Command;

class CheckMeaningVotes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'define:illustrator';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will check total number of votes against meanings and will transfer meaning to illustrator phase';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    protected $cronController;
    public function __construct()
    {
        parent::__construct();
        $this->cronController=new CronController();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->cronController->checkExpiredVotes();
    }
}
