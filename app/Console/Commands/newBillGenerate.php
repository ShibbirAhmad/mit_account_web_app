<?php

namespace App\Console\Commands;

use Throwable;
use App\Service\LogTracker;
use App\Models\ServiceClient;
use App\Models\ServicePackage;
use App\Service\HelperService;
use Illuminate\Console\Command;
use App\Models\ServicePackageBill;
use Illuminate\Support\Facades\DB;

class newBillGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:newBillGenerate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'generate monthly service charge bill who are active client';

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
            DB::beginTransaction();
            try{
                //send message to monthly service clients
                ServicePackage::where('is_monthly',1)->where('status',1)->with(['client:id,name,phone','service:id,name'])->each(function($s_package){
                   // if one month completed then generate new bill
                    $bill= ServicePackageBill::where('service_package_id',$s_package->id)->orderBy('created_at','desc')->first();
                    if(!empty($bill) && HelperService::dateDiffCheckerMonth($bill->end_date,date('Y-m-d')) >= 1 ) {
                        //service package bill create for first months
                        $data['service_package_id']= $s_package->id ;
                        $data['client_id']= $s_package->client_id ;
                        $data['service_id']= $s_package->service_id ;
                        $data['month'] = date('m', strtotime(date('Y-m-d'))) ;
                        $data['year'] =date('Y');
                        $data['start_date'] = date('Y-m-d');
                        $data['end_date'] = date('Y-m-d', strtotime(date('Y-m-d'). ' +29 days'));
                        $data['amount'] =  $s_package->monthly_charge   ;
                        $data['paid'] =  0 ;
                        $data['status'] =  0 ;
                        ServicePackageBill::query()->create($data);
                        $s_package->amount = $s_package->amount + $s_package->monthly_charge  ;
                        $s_package->is_paid = 0  ;
                        $s_package->save();
                        //increasing client total_money
                        $client=ServiceClient::findOrFail($s_package->client_id);
                        $client->total_amount = round(floatval($client->total_amount),2) + round(floatval($s_package->monthly_charge),2) ;
                        $client->save();
                    }
                });

                DB::commit();
               
            }catch(Throwable $th){
               DB::rollBack();
               LogTracker::failLog($th,null) ;
            }
    }
}
