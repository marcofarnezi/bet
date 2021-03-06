<?php
namespace App\Console\Commands;

use App\Rules\BetRule;
use App\Services\BetGameService;
use Illuminate\Console\Command;


/**
 * Class deletePostsCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class BetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "bet:play";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Start a new game";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $rule = new BetRule();
            $betGame = new BetGameService($rule);
            $betGame->loadConfigs();
            $this->info($betGame->checkResults());
        } catch (\Exception $e) {
            $this->error($e->getTraceAsString());
        }
    }
}
