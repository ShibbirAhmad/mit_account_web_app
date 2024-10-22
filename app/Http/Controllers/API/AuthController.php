<?php

namespace App\Http\Controllers\API;

use App\Service\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BoostAgencyReseller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\API\BaseController as BaseController;

class AuthController extends BaseController
{


    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'company_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = BoostAgencyReseller::create($data);

        $success['token'] =  $user->createToken('laravel_api_token')->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'User register successfully.');
    }


    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        $user = BoostAgencyReseller::where('phone', $request->phone)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'phone' => ['The credentials you entered are incorrect.']
            ]);
        }

        $success['token'] =  $user->createToken('laravel_api_token')->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'User login successfully.');
    }



    public function logout(Request $request)
    {
        // Ensure the user is authenticated via the 'api-reseller' guard
        $user = $request->user('api-reseller');

        if ($user) {
            // Delete the current access token to log the user out from this session
            $user->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'No active session found.'
        ], 400);
    }


    public function resetCode(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|digits:11',
        ]);

        $user = BoostAgencyReseller::where('phone', $data['phone'])->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => ['The phone number you entered are incorrect.']
            ]);
        }

        // $code = rand(000000, 999999);
        $code = 1234;
        DB::table('password_resets')->insert([
            'mobile_no' => $data['phone'],
            'token' => Hash::make($code)
        ]);
        SmsService::SendUserPasswordResetCode($data['phone'], $code);
        return response()->json([
            'status' => true,
            'phone' => $data['phone'],
            'message' => 'Successfully send otp code. please check your phone'
        ],200);
    }

    public function codeVerify(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|digits:11',
            'code' => 'required'
        ]);

        $user = DB::table('password_resets')->where('mobile_no', $data['phone'])->first();
        if (!$user || !Hash::check($data['code'], $user->token)) {
            throw ValidationException::withMessages([
                'phone' => ['The token you entered are incorrect!']
            ]);
        }

        return response()->json([
            'status' => true,
            'phone' => $data['phone'],
            'code' => $data['code'],
            'message' => 'Successfully match your OTP'
        ],200);

    }

    public function ResetPassword(Request $request)
    {
        $data = $request->validate([
            'phone' => 'required|digits:11',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        $user = BoostAgencyReseller::where('phone', $request->phone)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'phone' => ['The credentials you entered are incorrect.']
            ]);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        $success['token'] =  $user->createToken('laravel_api_token')->plainTextToken;
        $success['user'] = $user;

        return $this->sendResponse($success, 'User login successfully.');

    }
}
