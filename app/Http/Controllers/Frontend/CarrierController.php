<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Carrier;
use App\Models\Job_apply;

class CarrierController extends Controller
{
  
    public  function index(){
  
        $carriers= Carrier::orderBy('id','DESC')->where('status',1)->get();
        return response()->json([
            "success" => "OK",
            "carriers" => $carriers ,
        ]);
    }



    public function carrier_details($id){
          
          $carrier=Carrier::find($id);
          return response()->json([ "success"  => "OK", "carrier" => $carrier]);

    }



    public function job_apply(Request $req, $id) {
       
        $validatedData = $req->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required| digits:11 ',
            'file.*' => 'required|file|mimes:doc,docx,pdf|max:1024',
           
        ]);
  
            $apply = new Job_apply ();
            $apply->job_id=$id ;
            $apply->name = $req->name ;
            $apply->email=$req->email;
            $apply->phone=$req->phone;
            if($req->hasFile('file')){
             $file_path = $req->file('file')->store('file/job_apply','public');
            $apply->file=$file_path ;
            }
            $apply->save() ;  
            
            return response()->json([
                "success" => "OK",
                "message" => 'Thanks, you have successfully applied' ,
            ]);  

        
  
  
    }
  

    



}
