<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Config;
use App\UsageEvent;
use App\VmResources;
use DB;

class RecordCustomVM extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'billingusage:recordCustomVMs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /*
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
        // This process isn't necessary for resellers
        if ('true' == env('RESELLER')) {
            dd('We are a reseller.');
        }

        // Grab all new events concerning virtual machine creation or service offering changes.
        $eventId = Config::firstOrCreate(['parameter' => 'lastEventId']);

        if (null == $eventId->data) {
            $eventId->data = 0; // Initialize the parameter with a value.
        }

        $this->info('Last Processed Event ID is: [' . $eventId->data . ']');

        $maxEventId = UsageEvent::max('id');
        $this->info('Max ID in Event table: [' . $maxEventId . ']');

        if ($eventId->data == $maxEventId) { // If there are no new events, no processing is required.
            exit;
        }

        $events = UsageEvent::where('id', '>', $eventId->data)
            ->where('id', '<=', $maxEventId)
            ->where(function ($query) {
                $query->where('type', '=', 'VM.CREATE')
                    ->orWhere('type', '=', 'VM.UPGRADE');
            })
            ->get();

        // Iterate over each event and process
        $events->each(function ($event) {
            // In this context:
            // resource_id = vm_instance_id
            // type = Event type
            // offering_id = service_offering_id

            // Grab the Service Offering of the VM the event concerns if it is customized.
            $this->info('VM_ID: [' . $event->resource_id . '] Type: [' . $event->type . '] SO_ID: [' . $event->offering_id . ']');

            $so = DB::connection('cloud')
                ->table('service_offering')
                ->whereId($event->offering_id)
                ->whereNull('cpu')
                ->whereNull('speed')
                ->whereNull('ram_size')
                ->first();

            // If we have a result, the Service Offering is custom.
            if (isset($so->id)) {
                $this->info('We have a custom service offering');
                // We need to grab the custom fields for this VM.
                $vmDetails = \App\VmInstance::find($event->resource_id)->details->toArray();
                $resources = [];

                foreach ($vmDetails as $detail) {
                    if ('cpuNumber' == $detail->name || 'cpuSpeed' == $detail->name || 'memory' == $detail->name) {
                        $resources["$detail->name"] = $detail->value;
                    }
                }
                $resources['vmInstanceId'] = $event->resource_id;

                VmResources::create($resources);
            }
        });

        // Set the last processed record ID so we don't go over records multiple times.
        $eventId->data = $maxEventId;
        $eventId->save();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
