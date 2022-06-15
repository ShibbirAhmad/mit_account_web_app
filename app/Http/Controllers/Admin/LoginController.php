<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Carbon\Carbon;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Models\OtpVerification;
use App\Http\Controllers\Controller;
use App\Service\SmsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required ',
            'password' => 'required',
        ]);
        $credential = ['email' => $request->email, 'password' => $request->password, 'status' => 1];

        if (Auth::guard('admin')->attempt($credential)) {

            Session::put('admin', Auth::guard('admin')->user());
            $admin=Auth::guard('admin')->user();
            $admin->last_login_time=Carbon::now();
            $admin->save();

            // $code=random_int(1000,9999);
            // $otp=new OtpVerification();
            // $otp->mobile_no=$admin->phone;
            // $otp->code=Hash::make($code);
            // $otp->save();
            // $sms = "now your account has been accessed in mohasagor.com ";
            //  SmsService::verifyLogin($admin->phone,$sms);

            return response()->json([
                'status' => 'SUCCESS',
                'admin' => $admin,
                'token' => Hash::make($request->password),
                'message' => 'Login successfully'
            ]);

        } else {

            return response()->json([
                'status' => 'FAILD',
                'message' => 'sorry ! invalid login information '
            ]);

        }

    }




    public function otpVerification(Request $request){

     $validatedData = $request->validate([
         'code' => 'required:digits:4'
     ]);

   //  return $request->all();
     $otp=OtpVerification::where('mobile_no',session('admin')['phone'])->latest()->first();
     $to_time = strtotime(Carbon::now()->format('Y-m-d g:i:s'));
     $from_time = strtotime(Carbon::parse($otp->created_at)->format('Y-m-d g:i:s'));
     $expire_time= round(abs($to_time - $from_time) / 60,2);

     if (Hash::check($request->code, $otp->code)) {
           if($expire_time > 5){
               return response()->json([
                   'status' => 'EXPIRED',
                   'message' => 'Code Time Expired',
               ]);
             }else{
                return response()->json([
                    'status'=>"OK",
                    'message'=> 'verified successfully',
                ]);
            }
           }else{
              return response()->json([
                   'status' => 'FAILED',
                   'message' => " Code isn't matching ",
              ]);
           }

  }


    public function admin()
    {

        // return "hello";
        $admin = session()->get('admin');
        if ($admin) {
            return response()->json([
                'status' => 'SUCCESS',
                'admin' => $admin
            ]);
        }


    }

    public function logout()
    {

        $admin=Auth::guard('admin')->user();
        $admin->last_logout_time=Carbon::now();
        $admin->save();
        Auth::guard('admin')->logout();
        session()->forget('admin');
        return response()->json([
            'status' => 'SUCCESS',
            'message' => 'Logout successfully'
        ]);
    }

    public function sessionCheck()
    {


        if (!Session::has('admin')) {
            return response()->json([
                'status' => 'EXPIRED',
                'message' => 'Your session has expired'
            ]);
        } else {
            return response()->json([
                'status' => 'RUNNING',
                'message' => 'your session is running',
                'admin'=>session()->get('admin'),
            ]);
        }
    }
}
