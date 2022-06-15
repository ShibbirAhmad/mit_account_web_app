<?php

namespace App\Console\Commands;

use App\Service\SmsService;
use App\Models\ServicePackage;
use Illuminate\Console\Command;
use App\Models\ServicePackageBill;

class MonthlyServicePayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:monthlyServicePayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send monthly service notification to  due clients';

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
           //send message to monthly service clients
           $clients= ServicePackage::where('is_monthly',1)->where('is_paid',0)->where('status',1)->with(['client:id,name,phone','service:id,name'])->get();
           foreach ($clients as  $item) {
               //checking is there  due amount of current month and current year
               $bill= ServicePackageBill::where('service_package_id',$item->id)->where('month',intval(date('m')))->where('year',date('Y'))->where('status',0)->first();
               if (!empty($bill)) {
                   $due_amount = intval($bill->amount) - intval($bill->paid) ;
                   SmsService::sendDueMessageToClient($item->client->phone,$item->client->name,$item->service->name,$due_amount);
               }
            }
    }
}
