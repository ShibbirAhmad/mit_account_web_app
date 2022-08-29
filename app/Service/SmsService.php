<?php

namespace App\Service ;

use Exception;
use App\Models\Loan;
use App\Models\LoanPaid;
use App\Models\Purchase;
use App\Models\EmployeeSalary;
use App\Models\SalaryPerMonth;
use App\Models\ProductForPrint;
use App\Models\SupplierPayment;
use App\Models\PrintHousePayment;
use App\Models\BoostAgencyReseller;
use App\Models\BoostAgencyResellerPayment;
use App\Models\BoostAgencyResellerAdAccount;
use App\Models\BoostAgencyResellerDollarTransaction;


class SmsService{





    public   static function directorPaymentConfirmationMessage($director,$amount,$payable_amount,$total_amount){

        $sms = 'Assalamualikum, '.$director->name .' you have paid '. number_format($amount).'/= of '.number_format($total_amount).'/= as your director fund and payable amount is '. $payable_amount .'/= Thanks for being with mit' ;
        return self::smsApi($director->phone,$sms);
    }



    public   static function directorRefundConfirmationMessage($director,$amount,$payable_amount){

        $sms = 'Assalamualikum, '.$director->name .' you have refunded '. number_format($amount).'/=  from your director fund and  payable amount is '. $payable_amount .'/= Thanks for being with mit ';
        return self::smsApi($director->phone,$sms);
    }



    public static function sendDueMessageToClient($client_phone,$client_name,$service,$due_amount){

        $sms =  'Assalamualaikum, Dear '.$client_name. ' You have taken ' .$service. ' from MIT, your due amount is BDT '.number_format($due_amount).' requesting to pay the due amount. Thanks from mohasagorit.solutions ' ;
        return self::smsApi($client_phone,$sms);

    }


    public static function sendNewServiceMessage($client,$service,$amount,$paid){
        $sms = 'Assalamualaikum, Dear '.$client->name. ' You have taken ' .$service. ' from MIT, your payable amount is BDT '.number_format($amount).', paid amount '.number_format($paid).' and due amount is '.number_format($amount -  $paid).'. Thanks from mohasagorit.solutions ' ;
        return self::smsApi($client->phone,$sms);

    }


    public static function servicePaymentConfirmationMessage($client,$package){

        $sms =  'Assalamualaikum, Dear '.$client->name. ' You have total paid BDT '.number_format($package->paid).',  and total  due amount is '.number_format($package->amount - $package->paid).'. Thanks from mohasagorit.solutions ' ;
        return self::smsApi($client->phone,$sms);

    }


    public static function sendDollarConfirmationMessage($transaction){
        //get supplier due amount
        $reseller = BoostAgencyReseller::findOrFail($transaction->boost_agency_reseller_id);
        $advertise_account = BoostAgencyResellerAdAccount::findOrFail($transaction->boost_agency_reseller_ad_account_id);
        $payable_amount=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$transaction->boost_agency_reseller_id)->sum('amount');
        $total_dollar=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$transaction->boost_agency_reseller_id)->sum('dollar');
        $paid_amount=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$transaction->boost_agency_reseller_id)->sum('amount');
        $due_amount=$payable_amount - $paid_amount;
        $contacts = $reseller->phone;
        ///send message
        $sms =  'Assalamualaikum, Dear '.$reseller->name. ' You have purchase  $'.number_format($transaction->dollar).' for your advertise account ('.$advertise_account->name.'),  your total purchase dollar is '.$total_dollar.' and your due amount is '.$due_amount.'/=BDT, Thanks from MIT ';   // put here your dynamic message text here
        return self::smsApi($contacts,$sms);
    }




    public static function sendPaymentMessage($reseller,$reseller_id,$amount){
        //get supplier due amount
        $payable_amount=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller_id)->sum('amount');
        $paid_amount=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller_id)->sum('amount');
        $due_amount=$payable_amount - $paid_amount;
        $contacts = $reseller->phone;
        ///send message
        $sms = 'Assalamualaikum, Dear '.$reseller->name. ' Thanks for your recent payment . You have paid '.number_format($amount).'/=BDT, and your due amount is '.$due_amount.'/=BDT, Thanks from MIT ';
        return self::smsApi($contacts,$sms);
    }






    public  static function  SendMessageToInvestor($investor,$amount,$profit_month){

       $sms = 'Assalamualikum, You have received '.number_format($amount).'/=BDT, as your investment profit of '.$profit_month.'  from the mohasagorit.solutions Thanks for being with us';
       return self::smsApi($investor->mobile_no,$sms);

    }



    public  static function  SendMessageToPrintHouse($print_house,$amount){

        $print_house_amount=ProductForPrint::where('company_id',$print_house->id)->sum('total_cost');
        $print_house_paid_amount=PrintHousePayment::where('print_house_id',$print_house->id)->sum('amount');
        $due_amount=$print_house_amount - $print_house_paid_amount;
        $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received  from the mohasagorit.solutions and your due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($print_house->mobile_no,$sms);
    }


    public  static function  SendMerchantPasswordResetCode($contacts,$code){

       $sms = " your password reset code ".$code.' from mohasagorit.solutions';
       return self::smsApi($contacts,$sms);

    }


    public  static function  SendMessageToLoaner($loaner,$amount){
        //get supplier due amount
        $loans=Loan::where('loaner_id',$loaner->id)->sum('amount');
        $loanPaid=LoanPaid::where('loaner_id',$loaner->id)->sum('amount');
        $due_amount=$loans-$loanPaid;
        $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received from the mohasagorit.solutions and your due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($loaner->mobile_no,$sms);
    }


    public  static function  sendMessageToLoanerForNewLoan($loaner,$amount){
        //get supplier due amount
        $loans=Loan::where('loaner_id',$loaner->id)->sum('amount');
        $loanPaid=LoanPaid::where('loaner_id',$loaner->id)->sum('amount');
        $due_amount=$loans-$loanPaid;
        ///send message
        $sms =  'Assalamualaikum, Dear '.$loaner->name. ', mohasagorit.solutions has taken '.number_format($amount).'/=BDT as a loan from you. Total amount is '.number_format($loans).'/=BDT, and due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($loaner->mobile_no,$sms);
    }


    public  static function  SendMessageCustomer($number,$name,$invoice){

        $sms = 'Dear '.$name.','. 'Your order has been received. Invoice number is '.$invoice. '.' .'If you have any query please contact with us 09636203040. Thanks by  mohasagorit.solutions ';
        return self::smsApi($number,$sms);

    }


    public  static function  sendReturnMessage($admin_name,$invoice_no,$amount){

        $sms = 'Order has been returned, invoice number  '.$invoice_no. ', amount '.$amount.'Tk, Returned by '. $admin_name;   // put here your dynamic message text here
        return self::smsApi('01635555777',$sms);

    }


    public  static function  sendShipmentMessage($order){

        $total=0;
        if(!empty($order->total)){
          $total=($order->total)-($order->paid+$order->discount)+$order->shipping_cost;
        }
        $courier=$order->courier->name;
        $name=$order->customer->name;
        $sms = 'Dear ' . $name .'.'. ' Your order has been shipped to '.$courier.' Courier.'.' memo number is. ' .$order->memo_no.' and payable amount '.$total.' Tk.'.' Thanks by mohasagorit.solutions';   // put here your dynamic message text here
        return  self::smsApi($order->cutomer_phone,$sms);
    }



    public  static function  SendMessageToCustomer($customer,$amount,$invoice){

        $sms = 'Dear '.$customer->name .', Thank you for your purchase from mohasagorit.solutions. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';   // put here your dynamic message text here
        return  self::smsApi($customer->phone,$sms);
    }


    public static function SendSaleMessageToCustomer($customer,$amount,$invoice){

        $sms = 'Dear '.$customer->name .', Thank you for your purchase from Mohasagor. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';   // put here your dynamic message text here
        return  self::smsApi($customer->phone,$sms);
    }



    public  static function  SendMessageToCompany($company,$amount,$invoice){

        $sms = 'Dear '.$company->name .', Thank you for your purchase from mohasagorit.solutions. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';
        return  self::smsApi($company->phone,$sms);
    }




    public  static function  SendMessageToSupplier($supplier,$amount){

        //get supplier due amount
        $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
        $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
        $supplier_due_amount=$supplier_purchase_amount-$supplier_paid_amount;

        $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received from the mohasagor and your due amount is '.number_format($supplier_due_amount).'/=BDT';
        return self::smsApi($supplier->phone,$sms);
    }


     public  static function  SendReverseSaleMessageToSupplier($supplier,$invoice_no,$amount){

        //get supplier due amount
        $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
        $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
        $supplier_due_amount=$supplier_purchase_amount - $supplier_paid_amount;

        $sms = 'Assalamualikum, Dear '.$supplier->company_name.' you have purchased '.$amount.' BDT from  mohasagor  invoice No is '.$invoice_no.' your due amount is '.$supplier_due_amount.' BDT' ;   // put here your dynamic message text here
         return self::smsApi($supplier->phone,$sms);

    }




    public  static function  sendNewPurchaseMessage($supplier,$amount,$invoice_no)  {

            //get supplier due amount
            $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
            $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
            $supplier_due_amount=$supplier_purchase_amount-$supplier_paid_amount;

            $sms = 'Assalamualaikum Dear '.$supplier->name .', mohasagorit.solutions  has a new purchase '.number_format($amount).'/=BDT from you. Invoice is : '.$invoice_no.' and total due amount is '.number_format($supplier_due_amount).'/=BDT Thanks from mohasagorit.solutions';    // put here your dynamic message text here
            return self::smsApi($supplier->phone,$sms);
    }



    public static function reversePaymentMessageToSupplier($supplier,$amount){

        $sms =  'আসসালামুয়ালাইকুম '.$supplier->name. '  আপনি  '.number_format($amount).' টাকা পরিশোধ করেছেন। ধন্যবাদান্তে  mohasagorit.solutions' ;
        return self::smsApi($supplier->phone,$sms);

    }



    public  static function  sendMessageToEmployee($employee,$amount){

        $total_taken_amount=EmployeeSalary::where('employee_id',$employee->id)->sum('amount');
        $total_paid_amount=SalaryPerMonth::where('employee_id',$employee->id)->sum('amount');
        $due_amount= intval($total_taken_amount)  - intval($total_paid_amount) ;

        $sms = 'Assalamualikum, '. $employee->name.' you have received a payment as your salary ' .$amount.'/= BDT, from the mohasagorit.solutions and your due/advance amount is ' .$due_amount.'/=BDT';
        return self::smsApi($employee->phone,$sms);
    }



    public   static function   verifyLogin($number,$sms){

        return self::smsApi($number,$sms);
    }



    public static function  sendOtpCode($number,$code){

        $sms =$code.' is your OTP by mohasagorit.solutions';
        return self::smsApi($number,$sms);

    }





    public static function SendUserPasswordResetCode($contacts,$code){

        $sms = "Your password reset code is ".$code.' Thanks by mohasagorit.solutions';
        return self::smsApi($contacts,$sms);

    }



    public  static function   smsApi($contacts,$sms){

        // $url = "https://mshastra.com/sendurl.aspx?user=mit22&pwd=m91y91j9&senderid=MTSMS3&CountryCode=880&mobileno=".$contacts."&msgtext=". urlencode($sms) .'"';
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // try {
        //     $curl_scraped_page = curl_exec($ch);
        //     curl_close($ch);
        //     return $curl_scraped_page;
        // }catch (Exception $e) {
        //     return $e->getMessage();
        // }


    }




















}







?>