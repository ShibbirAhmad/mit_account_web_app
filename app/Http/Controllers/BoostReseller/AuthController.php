<?php

namespace App\Http\Controllers\BoostReseller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::guard('api-reseller')->attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $reseller = Auth::guard('api-reseller')->user();
        $token = $reseller->createToken('API Token')->plainTextToken;

        return response()->json(['token' => $token, 'reseller' => $reseller]);
    }

    public function logout(Request $request)
    {
        Auth::guard('api-reseller')->logout();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user('api-reseller'));
    }
}
