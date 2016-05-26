<?php

namespace App\Console\Commands;

use App\Reseller;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Console\Command;
use Config;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class ProcessNotifications extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ans:notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for RabbitMQ messages to propagate to BillingPortals.';

    private $connections = [];
    private $resellers = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $resellers = Reseller::all();

        foreach ($resellers as $reseller)
        {
            $this->resellers[$reseller->domainid] = ['key'        => $reseller->apikey,
                                                     'portal_url' => $reseller->portal_url];
        }
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $queue = 'cloudstack';

        $conn = new AMQPStreamConnection(Config::get('rabbitmq.host'),
            Config::get('rabbitmq.port'),
            Config::get('rabbitmq.user'),
            Config::get('rabbitmq.pass'),
            Config::get('rabbitmq.vhost'));

        $channel = $conn->channel();

        while ($message = $channel->basic_get($queue))
        {
            $messageData = json_decode($message->body);

            // Don't think we care about messages that have no status or event
            if (empty($messageData->status) || empty($messageData->event))
            {
                $channel->basic_ack($message->delivery_info['delivery_tag']);
                continue;
            }

            // For the moment, we don't care about non-Completed messages.
            if (!in_array($messageData->status, ['Completed', 'Created']))
            {
                $channel->basic_ack($message->delivery_info['delivery_tag']);
                continue;
            }

            if (in_array($messageData->event, ['SNAPSHOT.CREATE', 'SNAPSHOT.DELETE', 'VM.CREATE', 'VM.DESTROY']))
            {
                $messageHandled = $this->parseMessage($messageData);
            }
            else
            {
                $messageHandled = true;
            }

            if ($messageHandled == true)
                $channel->basic_ack($message->delivery_info['delivery_tag']);
        }

        $channel->close();
        $conn->close();
    }

    private function parseMessage($message)
    {
        $this->info("$message->event $message->description $message->account");

        // We only care about good, completed messages
        if (strpos($message->description, 'Error') === false)
        {
            if ($message->event == 'SNAPSHOT.CREATE' && $message->status == 'Completed')
            {
                // Get the virtual machine data we care about
                $vm = $this->getVMDataWithVolume($message->Volume);

                // Data we care about for created snapshot: $vm->ostypeid, $message->account, $vm->domainid
                $domainid = $vm->domainid;

                // Discard message if it isn't to a reseller
                if ($this->verifyDomain($domainid) == false)
                     return true;

                $notification = ['apikey'   => $this->resellers[$vm->domainid]['key'],
                                 'account'  => $message->account,
                                 'event'    => $message->event,
                                 'uuid'     => $message->entityuuid,
                                 'ostypeid' => $vm->ostypeid];
            }

            if ($message->event == 'SNAPSHOT.DELETE' && $message->status == 'Completed')
            {
                $domainid = app('cloudstack')->listAccounts(['id' => $message->account])[0]->domainid;

                // Discard message if it isn't to a reseller
                if ($this->verifyDomain($domainid) == false)
                    return true;

                $notification = ['apikey'  => $domainid,
                                 'account' => $message->account,
                                 'event'   => $message->event,
                                 'uuid'    => $message->entityuuid];
            }

            if (isset($domainid) && isset($notification))
            {
                // Send notification to BillingPortal API
                $response = $this->sendNotificationToDomain($domainid, $notification);

                // If notification was successfully recorded, return true
                return $response;
            }
        }

        // Discard message as "handled" if we don't care about it.
        return true;
    }

    private function getVMDataWithVolume($volumeId)
    {
        $vol = app('cloudstack')->listVolumes(['id' => $volumeId])[0];
        $vm = app('cloudstack')->listVirtualMachines(['id' => $vol->virtualmachineid])[0];

        return $vm;
    }

    private function verifyDomain($domainid)
    {
        $this->info("Event is for domain $domainid");
        return in_array($domainid, array_keys($this->resellers));
    }

    private function createNotificationWithMessage($message)
    {

    }

    private function sendNotificationToDomain($domainid, $notification)
    {
        // See if we have a connection to the proper BillingPortal API
        if (!isset($this->connections[$domainid]))
        {
            // Found no connection, create one.
            $this->connections[$domainid] = new Client(['base_uri' => $this->resellers[$domainid]['portal_url']]);
        }

        $this->info("Notifying domain $domainid about {$notification['event']} for account {$notification['account']}.");

        try
        {
            $this->connections[$domainid]->request('POST', 'api/receive-notification', ['json' => $notification]);
        }
        catch (RequestException $e)
        {
            /* These functions seem able to produce classes that have no __toString() implementation.
            echo $e->getRequest();
            if ($e->hasResponse())
            {
                echo $e->getResponse();
            }
            */

            return false;
        }

        return true;
    }
}
