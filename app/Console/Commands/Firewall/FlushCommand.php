<?php namespace App\Console\Commands\Firewall;

use Illuminate\Console\Command;
use DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class FlushCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'firewall:flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set all firewall rules as inactive.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Set all firewall rules as inactive
        DB::update('UPDATE firewall_rules SET active = 0');
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
//			['example', InputArgument::REQUIRED, 'An example argument.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
//			['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }
}
