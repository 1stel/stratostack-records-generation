<?php namespace App\Console\Commands;

use App;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DB;

class BillingRun extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'billing:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate usable usage records for our resellers.';

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

        // Setup lists to look up ACS IDs.

        // We need: zones, accounts, domains, instances, service offerings and templates.

        $dataTypes = ['cloud.data_center'           => 'zones',
                      'cloud.domain'                => 'domains',
                      'cloud.account'               => 'accounts',
                      'cloud.vm_instance'           => 'instances',
                      'cloud.service_offering_view' => 'serviceofferings',
                      'cloud.template_view'         => 'templates'];

        $uuidLookup = [];

        foreach ($dataTypes as $table => $type) {
            $dbInfo = DB::table($table)->get();
            $uuidLookup[$type] = [];

            foreach ($dbInfo as $info) {
                $uuidLookup[$type][$info->id] = $info->uuid;
            }
        }

        $diskInfo = DB::table('cloud.disk_offering')->get();
        $diskOfferings = [];

        foreach ($diskInfo as $do) {
            $diskOfferings[$do->id] = $do->tags;
        }

        // Grab yesterday's records
        $yesterday = date('Y-m-d', time() - 84600);

        $records = App\CloudUsage::like('start_date', $yesterday . '%')->billable()->get()->each(function ($record) use ($uuidLookup, $diskOfferings) {
            $recordType = '';

            switch ($record->usage_type) {
                case 2:
                    $recordType = 'VM Instance';

                    // Grab the service offering of the VM
                    $serviceOffering = App\ServiceOffering::find($record->offering_id);

                    if (null == $serviceOffering->cpu &&
                        null == $serviceOffering->speed &&
                        null == $serviceOffering->ram_size
                        ) {
                    // Our service offering is Custom.  Grab its resources from our custom table.
                            $resources = App\VmResources::find($record->vm_instance_id);
                            $cpuNumber = $resources->cpuNumber;
                            $cpuSpeed = $resources->cpuSpeed;
                            $memory = $resources->memory;
                    } else {
                        $cpuNumber = $serviceOffering->cpu;
                        $cpuSpeed = $serviceOffering->speed;
                        $memory = $serviceOffering->ram_size;
                    }

                        App\UsageVm::create([
                        'zoneId'            => $uuidLookup['zones'][$record->zone_id],
                        'accountId'         => $uuidLookup['accounts'][$record->account_id],
                        'domainId'          => $uuidLookup['domains'][$record->domain_id],
                        'vm_name'           => $record->vm_name,
                        'type'              => 'VM',
                        'usage'             => $record->raw_usage,
                        'vmInstanceId'      => $uuidLookup['instances'][$record->vm_instance_id],
                        'serviceOfferingId' => $uuidLookup['serviceofferings'][$record->offering_id],
                        'templateId'        => $uuidLookup['templates'][$record->template_id],
                        'cpuNumber'         => $cpuNumber,
                        'cpuSpeed'          => $cpuSpeed,
                        'memory'            => $memory,
                        'startDate'         => $record->start_date,
                        'endDate'           => $record->end_date
                        ]);
                    break;

                case 4:
                case 5:
                    $recordType = 'Network Usage';
                    App\UsageGeneral::create([
                        'zoneId'    => $uuidLookup['zones'][$record->zone_id],
                        'accountId' => $uuidLookup['accounts'][$record->account_id],
                        'domainId'  => $uuidLookup['domains'][$record->domain_id],
                        'type'      => (stripos($record->usage_display, 'Received') === false) ? 'Network Sent' : 'Network Received',
                        'usage'     => $record->raw_usage,
                        'startDate' => $record->start_date,
                        'endDate'   => $record->end_date
                    ]);

                    break;

                case 6:
                    $recordType = 'Disk/Volume';
                    // Fetch the VM instance this volume belongs to.
                    $vol = App\Volume::find($record->usage_id);

                    try {
                        $instance = App\VmInstance::find($vol->instance_id);
                    } catch (\Exception $e) {
                        continue; // Ignored bad record.
                    }

                    if (!($instance instanceof App\VmInstance)) {
                        continue; // Ignore this record, its bad for some reason.
                    }

                    App\UsageDisk::create([
                        'zoneId'       => $uuidLookup['zones'][$record->zone_id],
                        'accountId'    => $uuidLookup['accounts'][$record->account_id],
                        'domainId'     => $uuidLookup['domains'][$record->domain_id],
                        'volumeId'     => $vol->uuid,
                        'type'         => ($vol->volume_type == 'ROOT') ? 'Root Volume' : 'Volume',
                        'tags'         => $diskOfferings[$vol->disk_offering_id],
                        'usage'        => $record->raw_usage,
                        'size'         => $record->size,
                        'vmInstanceId' => $uuidLookup['instances'][$instance->id],
                        'startDate'    => $record->start_date,
                        'endDate'      => $record->end_date
                    ]);

                    break;

                case 9:
                    $recordType = 'Snapshot';
                    App\UsageDisk::create([
                        'zoneId'    => $uuidLookup['zones'][$record->zone_id],
                        'accountId' => $uuidLookup['accounts'][$record->account_id],
                        'domainId'  => $uuidLookup['domains'][$record->domain_id],
                        'type'      => 'Snapshot',
                        'usage'     => $record->raw_usage,
                        'size'      => $record->size,
                        'startDate' => $record->start_date,
                        'endDate'   => $record->end_date
                    ]);

                    break;

                case 11:
                    $recordType = 'Load Balancer';
                    App\UsageGeneral::create([
                        'zoneId'       => $uuidLookup['zones'][$record->zone_id],
                        'accountId'    => $uuidLookup['accounts'][$record->account_id],
                        'domainId'     => $uuidLookup['domains'][$record->domain_id],
                        'type'         => 'LB',
                        'usage'        => $record->raw_usage,
                        'vmInstanceId' => $uuidLookup['instances'][$record->vm_instance_id],
                        'templateId'   => $uuidLookup['templates'][$record->template_id],
                        'startDate'    => $record->start_date,
                        'endDate'      => $record->end_date
                    ]);

                    break;

                case 12:
                    $recordType = 'Port Forward';
                    App\UsageGeneral::create([
                        'zoneId'       => $uuidLookup['zones'][$record->zone_id],
                        'accountId'    => $uuidLookup['accounts'][$record->account_id],
                        'domainId'     => $uuidLookup['domains'][$record->domain_id],
                        'type'         => 'PF',
                        'usage'        => $record->raw_usage,
                        'vmInstanceId' => $uuidLookup['instances'][$record->vm_instance_id],
                        'templateId'   => $uuidLookup['templates'][$record->template_id],
                        'startDate'    => $record->start_date,
                        'endDate'      => $record->end_date
                    ]);

                    break;

                case 14:
                    $recordType = 'VPN';
                    App\UsageGeneral::create([
                        'zoneId'       => $uuidLookup['zones'][$record->zone_id],
                        'accountId'    => $uuidLookup['accounts'][$record->account_id],
                        'domainId'     => $uuidLookup['domains'][$record->domain_id],
                        'type'         => 'VPN',
                        'usage'        => $record->raw_usage,
                        'vmInstanceId' => $uuidLookup['instances'][$record->vm_instance_id],
                        'templateId'   => $uuidLookup['templates'][$record->template_id],
                        'startDate'    => $record->start_date,
                        'endDate'      => $record->end_date
                    ]);

                    break;

                default:
                    $recordType = 'Unknown (' . $record->usage_type . ')';
                    break;
            }

            $this->info('Found a record of type ' . $recordType);
        });
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [];
        /*		return [
                    ['example', InputArgument::REQUIRED, 'An example argument.'],
                ];
        */
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [];
        /*		return [
                    ['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
                ];
        */
    }

    private function getList($name)
    {
        switch ($name) {
            case 'domain':
                break;

            case 'account':
                break;

            case 'zone':
                break;
        }
    }
}
