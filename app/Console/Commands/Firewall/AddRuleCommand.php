<?php namespace App\Console\Commands\Firewall;

use App\FirewallRule;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class AddRuleCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'firewall:addrule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Load new firewall rules from MySQL.';

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
        //
        $uid = posix_getuid();
        $this->info("UID is $uid");

        // Get the rules we need to add
        $rules = FirewallRule::whereActive(0)->get();

        foreach ($rules as $rule) {
        // Add the rule to iptables
            exec($this->genString($rule));

            // Mark rule as active in DB
            $rule->active = 1;
            $rule->save();
        }

        // See if there are any rules to delete
        $deletedRules = FirewallRule::onlyTrashed()->get();

        foreach ($deletedRules as $rule) {
            exec($this->genString($rule));

            $rule->forceDelete();
        }
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

    private function genString(FirewallRule $rule)
    {
        // If rule has been deleted, remove it from firewall.  If not, add it.
        if (null == $rule->deleted_at) {
            $rule_str = "iptables -I INPUT";
        } else {
            $rule_str = "iptables -D INPUT";
        }

        $rule_str .= " -s " . $rule->src . $rule->src_cidr . " -p $rule->protocol";

        if (isset($rule->dst_port)) {
            $rule_str .= " -m $rule->protocol --dport $rule->dst_port";
        }

        $rule_str .= " -j ACCEPT";

        return $rule_str;
    }
}
