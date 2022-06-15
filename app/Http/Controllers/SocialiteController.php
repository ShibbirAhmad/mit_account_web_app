<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;


class SocialiteController extends Controller
{
    public function login(){

        return Socialite::driver('google')->redirect();
    }

    public function redirect(){

        $g_user = Socialite::driver('google')->user();
        
        $user=User::where('email',$g_user->email)->first();
        
     

         if(empty($user)){
           return $this->createUser($g_user);
         }else{
            if (Auth::loginUsingId($user->id)){
                return redirect()->route('welcome')->with('SocialLoginMessage','Login Successfully');
            }
         }
  }

    public function loginFacebook(){

        return Socialite::driver('facebook')->redirect();
    }

 public function redirectFacebook(){

        $f_user = Socialite::driver('facebook')->user();
       
        $user=$f_user->user;
        if(!empty($user['email'])){
          return  $this->createUserFromFacebook($user);
        }else{
            return redirect()->route('welcome')->with('SocialLoginMessage','Login Faild ! Facebook Can Not Proived Your Email Address');
        }

    }
    public function createUser($g_user){

        $user=new User();
        $user->email=$g_user->email;
        $user->name=$g_user->name;
        $user->password=Hash::make('123456');
        $user->save();

        $credential = ['email' => $user->email, 'password'=>'123456'];
        if (Auth::attempt($credential)) {
            return redirect()->route('welcome')->with('SocialLoginMessage','Login Successfully');
        }
    }

    public function createUserFromFacebook($user){

        $check_user=User::where('email',$user['email'])->first();

        if(!empty($check_user)){
            if (Auth::loginUsingId($check_user->id)){
                return redirect()->route('welcome')->with('SocialLoginMessage','Login Successfully');
            }
        }else{
            $new_user=new User();
            $new_user->email=$user['email'];
            $new_user->name=$user['name'];
            $new_user->password=Hash::make('123456');
            $new_user->save();
    
            $credential = ['email' => $new_user->email, 'password'=>'12345678'];
             if (Auth::attempt($credential)) {
                return redirect()->route('welcome')->with('SocialLoginMessage','Login Successfully');
            }
        }

      
    }
    
}
