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

        // $sms =  'Assalamualaikum, Dear '.$client_name. ' You have taken ' .$service. ' from MIT, your due amount is BDT '.number_format($due_amount).' requesting to pay the due amount. Thanks from mit.mohasagor.com ' ;
        $sms = 'আসসালামু আলাইকুম, '.$client_name. ' আপনি MIT হতে ' .$service. ' নিয়েছেন, আপনার বকেয়া টাকার পরিমান '.number_format($due_amount).'টাকা। বকেয়া টাকা পরিশোধের জন্য অনুরোধ করা হলো। ধন্যবাদান্তে mohasagorit.solutions ' ;
        return self::smsApi($client_phone,$sms);

    }


    public static function sendNewServiceMessage($client,$service,$amount,$paid){
        // $sms = 'Assalamualaikum, Dear '.$client->name. ' You have taken ' .$service. ' from MIT, your payable amount is BDT '.number_format($amount).', paid amount '.number_format($paid).' and due amount is '.number_format($amount -  $paid).'. Thanks from mit.mohasagor.com ' ;
        $sms = 'আসসালামু আলাইকুম, '.$client->name. ' আপনি MIT হতে ' .$service. ' নিয়েছেন , আপনার প্রদানযোগ্য টাকার পরিমান '.number_format($amount).' টাকা। , পরিশোধিত টাকার পরিমান '.number_format($paid).' এবং বকেয়া টাকার পরিমান '.number_format($amount - $paid).'. ধন্যবাদান্তে mohasagorit.solutions ' ;
        return self::smsApi($client->phone,$sms);

    }


    public static function servicePaymentConfirmationMessage($client,$package){

        // $sms =  'Assalamualaikum, Dear '.$client->name. ' You have paid BDT '.number_format($package->paid).', paid amount and  due amount is '.number_format($package->amount - $package->paid).'. Thanks from mit.mohasagor.com ' ;
        $sms = 'আসসালামু আলাইকুম '.$client->name. ' আপনি পরিশোধ করেছেন '.number_format($package->paid).' টাকা, পরিশোধিত টাকার পরিমান এবং বকেয়া টাকার পরিমান '.number_format($package->amount - $package->paid).'. ধন্যবাদান্তে mohasagorit.solutions ' ;
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
        // $sms =  'Assalamualaikum, Dear '.$reseller->name. ' You have purchase  $'.number_format($transaction->dollar).' for your advertise account ('.$advertise_account->name.'),  your total purchase dollar is '.$total_dollar.' and your due amount is '.$due_amount.'/=BDT, Thanks from Mohasagor It Solution ';   // put here your dynamic message text here
        $sms = 'আসসালামু আলাইকুম '.$reseller->name. ' আপনি ক্রয় করেছেন $'.number_format($transaction->dollar).' আপনার বিজ্ঞাপন অ্যাকাউন্টের জন্য ('.$advertise_account->name.'), আপনার মোট ক্রয়কৃত ডলারের পরিমান '.$total_dollar.' এবং আপনার বকেয়া '.$due_amount.'টাকা, ধন্যবাদান্তে mohasagorit.solutions '; // put here your dynamic message text here
        return self::smsApi($contacts,$sms);
    }




    public static function sendPaymentMessage($reseller,$reseller_id,$amount){
        //get supplier due amount
        $payable_amount=BoostAgencyResellerDollarTransaction::where('boost_agency_reseller_id',$reseller_id)->sum('amount');
        $paid_amount=BoostAgencyResellerPayment::where('boost_agency_reseller_id',$reseller_id)->sum('amount');
        $due_amount=$payable_amount - $paid_amount;
        $contacts = $reseller->phone;
        ///send message
        // $sms = 'Assalamualaikum, Dear '.$reseller->name. ' Thanks for your recent payment . You have paid '.number_format($amount).'/=BDT, and your due amount is '.$due_amount.'/=BDT, Thanks from Mohasagor It Solution '; // put here your dynamic message text here
        $sms = 'আসসালামু আলাইকুম '.$reseller->name. ' আপনার পেমেন্টের জন্য ধন্যবাদ। আপনি পরিশোধ করেছেন '.number_format($amount).'টাকা, এবং বকেয়া টাকার পরিমান '.$due_amount.'টাকা, ধন্যবাদান্তে mohasagorit.solutions '; // put here your dynamic message text here
        return self::smsApi($contacts,$sms);
    }






    public  static function  SendMessageToInvestor($investor,$amount,$profit_month){

    //    $sms = 'Assalamualikum, You have received '.number_format($amount).'/=BDT, as your investment profit of '.$profit_month.'  from the mohasagor.com Thanks for being with us';   // put here your dynamic message text here
       $sms = 'আসসালামু আলাইকুম, আপনি গ্রহন করেছেন '.number_format($amount).'টাকা, আপনার বিনিয়োগ হিসেবে '.$profit_month.' mohasagor.com এর সাথে থাকার জন্য ধন্যবাদ।'; // put here your dynamic message text here
       return self::smsApi($investor->mobile_no,$sms);

    }



    public  static function  SendMessageToPrintHouse($print_house,$amount){

        $print_house_amount=ProductForPrint::where('company_id',$print_house->id)->sum('total_cost');
        $print_house_paid_amount=PrintHousePayment::where('print_house_id',$print_house->id)->sum('amount');
        $due_amount=$print_house_amount - $print_house_paid_amount;
        $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received  from the mohasagor.com and your due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($print_house->mobile_no,$sms);
    }


    public  static function  SendMerchantPasswordResetCode($contacts,$code){

       $sms = " আপনার পাসওর্য়াড রিসেট কোডটি হলো ".$code.' ধন্যবাদান্তে mohasagor.com';
       return self::smsApi($contacts,$sms);

    }


    public  static function  SendMessageToLoaner($loaner,$amount){
        //get supplier due amount
        $loans=Loan::where('loaner_id',$loaner->id)->sum('amount');
        $loanPaid=LoanPaid::where('loaner_id',$loaner->id)->sum('amount');
        $due_amount=$loans-$loanPaid;
        $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received from the mohasagor.com and your due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($loaner->mobile_no,$sms);
    }


    public  static function  sendMessageToLoanerForNewLoan($loaner,$amount){
        //get supplier due amount
        $loans=Loan::where('loaner_id',$loaner->id)->sum('amount');
        $loanPaid=LoanPaid::where('loaner_id',$loaner->id)->sum('amount');
        $due_amount=$loans-$loanPaid;
        ///send message
        $sms =  'Assalamualaikum, Dear '.$loaner->name. ', mohasagor.com has taken '.number_format($amount).'/=BDT as a loan from you. Total amount is '.number_format($loans).'/=BDT, and due amount is '.number_format($due_amount).'/=BDT';
        return self::smsApi($loaner->mobile_no,$sms);
    }


    public  static function  SendMessageCustomer($number,$name,$invoice){

        // $sms = 'Dear '.$name.','. 'Your order has been received. Invoice number is '.$invoice. '.' .'If you have any query please contact with us 09636203040. Thanks by  mohasagor.com ';
        $sms = $name.','. 'আপনার অর্ডারটি রিসিভ করা হয়েছে। ইনভয়েস নাম্বার '.$invoice. '.' .'আপনার কোন প্রশ্ন থাকলে আমাদের সাথে যোগাযোগ করুন 09636203040। ধন্যবাদান্তে mohasagor.com ';
        return self::smsApi($number,$sms);

    }


    public  static function  sendReturnMessage($admin_name,$invoice_no,$amount){

        // $sms = 'Order has been returned, invoice number  '.$invoice_no. ', amount '.$amount.'Tk, Returned by '. $admin_name;   // put here your dynamic message text here
        $sms = 'অর্ডারটি ফিরিয়ে দেওয়া হয়েছে, ইনভয়েস নাম্বার '.$invoice_no. ', পরিমান '.$amount.'টাকা , Returned by '. $admin_name; // put here your dynamic message text here
        return self::smsApi('01635555777',$sms);

    }


    public  static function  sendShipmentMessage($order){

        $total=0;
        if(!empty($order->total)){
          $total=($order->total)-($order->paid+$order->discount)+$order->shipping_cost;
        }
        $courier=$order->courier->name;
        $name=$order->customer->name;
        // $sms = 'Dear ' . $name .'.'. ' Your order has been shipped to '.$courier.' Courier.'.' memo number is. ' .$order->memo_no.' and payable amount '.$total.' Tk.'.' Thanks by mohasagor.com';   // put here your dynamic message text here
        $sms = $name . '.' . ' আপনার অর্ডারটি প্রেরণ করা হয়েছে ' . $courier . ' কুরিয়ারে.' . ' মেমো নাম্বার. ' . $order->memo_no . ' পরিশোধীত টাকা ' . $total . ' Tk.' . ' ধন্যবাদান্তে mohasagor.com'; // put here your dynamic message text here
        return  self::smsApi($order->cutomer_phone,$sms);
    }



    public  static function  SendMessageToCustomer($customer,$amount,$invoice){

        // $sms = 'Dear '.$customer->name .', Thank you for your purchase from mohasagor.com. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';   // put here your dynamic message text here
        $sms = $customer->name . ', mohasagor.com  থেকে আপনার ক্রয়ের জন্য আপনাকে ধন্যবাদ। ইনভয়েস নাম্বার S-' . $invoice . ' & পরিশোধীত টাকা ' . $amount . ' টাকা।'; // put here your dynamic message text here
        return  self::smsApi($customer->phone,$sms);
    }


    public static function SendSaleMessageToCustomer($customer,$amount,$invoice){

        // $sms = 'Dear '.$customer->name .', Thank you for your purchase from Mohasagor. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';   // put here your dynamic message text here
        $sms = 'Dear '.$customer->name .', mohasagor.com থেকে ক্রয়ের জন্য আপনাকে ধন্যবাদ। ইনভয়েস নাম্বার S-'.$invoice.' & পরিশোধিত পরিমান ' .$amount.' টাকা।';   // put here your dynamic message text here
        return  self::smsApi($customer->phone.'+01635555777',$sms);
    }



    public  static function  SendMessageToCompany($company,$amount,$invoice){

        // $sms = 'Dear '.$company->name .', Thank you for your purchase from mohasagor.com. Invoice number is S-'.$invoice.' & payable amount is ' .$amount.'/=BDT';
        $sms = $company->name . ', mohasagor.com থেকে আপনার ক্রয়ের জন্য আপনাকে ধন্যবাদ, ইনভয়েস নাম্বার S-'.$invoice.' & প্রদান যোগ্য টাকার পরিমান ' .$amount.' টাকা।';
        return  self::smsApi($company->phone,$sms);
    }




    public  static function  SendMessageToSupplier($supplier,$amount){

        //get supplier due amount
        $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
        $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
        $supplier_due_amount=$supplier_purchase_amount-$supplier_paid_amount;

        // $sms = 'Thank you for the recent payment of '.number_format($amount).'/=BDT, You have received from the mohasagor and your due amount is '.number_format($supplier_due_amount).'/=BDT';
        $sms = 'আপনাকে ধন্যবাদ সর্বশেষ '.number_format($amount).' টাকা পরিশোধ করার জন্য।, আপনি mohasagor.com থেকে পেয়েছেন এবং আপনার বকেয়া টাকার পরিমান '.number_format($supplier_due_amount).' টাকা।';
        return self::smsApi($supplier->phone,$sms);
    }


     public  static function  SendReverseSaleMessageToSupplier($supplier,$invoice_no,$amount){

        //get supplier due amount
        $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
        $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
        $supplier_due_amount=$supplier_purchase_amount - $supplier_paid_amount;

        // $sms = 'Assalamualikum, Dear '.$supplier->company_name.' you have purchased '.$amount.' BDT from  mohasagor  invoice No is '.$invoice_no.' your due amount is '.$supplier_due_amount.' BDT' ;   // put here your dynamic message text here
        $sms = 'আসসালামু আলাইকুম '.$supplier->company_name.' আপনি '.$amount.' টাকা mohasagor হতে ক্রয় করেছেন ইনভয়েস নাম্বার '.$invoice_no.' আপনার বকেয়ার পরিমান '.$supplier_due_amount.' টাকা।' ; // put here your dynamic message text here
         return self::smsApi($supplier->phone,$sms);

    }




    public  static function  sendNewPurchaseMessage($supplier,$amount,$invoice_no)  {

            //get supplier due amount
            $supplier_purchase_amount=Purchase::where('supplier_id',$supplier->id)->sum('total');
            $supplier_paid_amount=SupplierPayment::where('supplier_id',$supplier->id)->sum('amount');
            $supplier_due_amount=$supplier_purchase_amount-$supplier_paid_amount;

            $sms = 'Assalamualaikum Dear '.$supplier->name .', mohasagor.com  has a new purchase '.number_format($amount).'/=BDT from you. Invoice is : '.$invoice_no.' and total due amount is '.number_format($supplier_due_amount).'/=BDT Thanks from mohasagor.com';    // put here your dynamic message text here
            return self::smsApi($supplier->phone,$sms);
    }



    public static function reversePaymentMessageToSupplier($supplier,$amount){

        $sms =  'আসসালামুয়ালাইকুম '.$supplier->name. '  আপনি  '.number_format($amount).' টাকা পরিশোধ করেছেন। ধন্যবাদান্তে  mohasagor.com' ;
        return self::smsApi($supplier->phone,$sms);

    }



    public  static function  sendMessageToEmployee($employee,$amount){

        $total_taken_amount=EmployeeSalary::where('employee_id',$employee->id)->sum('amount');
        $total_paid_amount=SalaryPerMonth::where('employee_id',$employee->id)->sum('amount');
        $due_amount= intval($total_taken_amount)  - intval($total_paid_amount) ;

        // $sms = 'Assalamualikum, '. $employee->name.' you have received a payment as your salary ' .$amount.'/= BDT, from the mohasagor.com and your due/advance amount is ' .$due_amount.'/=BDT';
        $sms = 'আসসালামু আলাইকুম, '. $employee->name.' আপনি বেতন বাবদ ' .$amount.' টাকা mohasagor.com হতে নিয়েছেন, এবং আপনার বকেয়া/অগ্রিম পরিমান ' .$due_amount.' টাকা।';
        return self::smsApi($employee->phone,$sms);
    }



    public   static function   verifyLogin($number,$sms){

        return self::smsApi($number,$sms);
    }



    public static function  sendOtpCode($number,$code){

        $sms =$code.' is your OTP by mohasagor.com';
        return self::smsApi($number,$sms);

    }





    public static function SendUserPasswordResetCode($contacts,$code){

        // $sms = "Your password reset code is ".$code.' Thanks by mohasagor.com';
        $sms = "আপনার পাসওয়ার্ড রিসেট কোড হলো ".$code.' ধন্যবাদান্তে mohasagor.com';
        return self::smsApi($contacts,$sms);

    }




    public  static function   smsApi($contacts,$sms){

        $url = "http://mshastra.com/sendurl.aspx?user=20102177&pwd=Moh@123&senderid=MTSMS3&CountryCode=880&mobileno=".$contacts."&msgtext=". urlencode($sms) .'"';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        try {
            $curl_scraped_page = curl_exec($ch);
            curl_close($ch);
            return $curl_scraped_page;
        }catch (Exception $e) {
            return $e->getMessage();
        }


    }



















}







?>