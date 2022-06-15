<?php

namespace App\Console\Commands;

use App\Service\SmsService;
use App\Models\ServicePackage;
use Illuminate\Console\Command;

class ContractualServicePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:contractualServicePayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send contractual service notification to  due clients';

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
    public function handle()
    {
          //send message to contractual clients
          $clients= ServicePackage::where('is_monthly',2)->where('is_paid',0)->where('status',1)->with(['client:id,name,phone','service:id,name'])->get();
           foreach ($clients as  $item) {
               $due_amount = intval($item->amount) - intval($item->paid) ;
               SmsService::sendDueMessageToClient($item->client->phone,$item->client->name,$item->service->name,$due_amount);
           }
    }
}
