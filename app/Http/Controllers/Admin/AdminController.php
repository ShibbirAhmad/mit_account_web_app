<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\AdminNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Session\Session;


class AdminController extends Controller
{


    public function __construct(Request $request)
    {
        $this->middleware('admin');


    }
      public function index(Request $request)
    {
        $admins = Admin::orderby('id', 'desc')->where('status',$request->status)->paginate(20);
        return response()->json([
            'admins' => $admins,
            'status' => 'SUCCESS'
        ]);
    }


    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'email' => 'required ',
            'phone' => 'required|digits:11|unique:admins',
            'password' => 'required',
            'name' => 'required',
         ]);
        $admin = new Admin();
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        $admin->password = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/admin', 'public');
            $admin->image = $path;
        }
        if ($admin->save()) {


            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'admin add successfully'
            ]);
        }

    }



    public function edit($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            return response()->json([
                'status' => 'SUCCESS',
                'admin' => $admin
            ]);
        }


    }


    public function search_admin($data){

        $admins = Admin::Where('email','like','%'.$data.'%')
                      ->orWhere('name','like','%'.$data.'%')
                      ->paginate(10);

            return response()->json([
                 "status" => "OK",
                 'admins' => $admins ,
            ]);

    }



    public function update(Request $request, $id)
    {

        //return $request->all();
        $validatedData = $request->validate([
            'email' => 'required ',
            'name' => 'required',
            'phone' => 'required|digits:11|unique:admins,phone,'.$id,

        ]);

     //   return $request->all();
        $admin =Admin::findOrFail($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->phone = $request->phone;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/admin', 'public');
            $admin->image = $path;
        }

        if(!empty($request->signature)){
            $name='admin-signature-'.time().'.png';
            Image::make(file_get_contents($request->signature))->save(public_path('storage/images/admin/signature/').$name);
            $admin->signature='images/admin/signature/'.$name;
        }

        if ($admin->save()) {
            return response()->json([
                'status' => 'SUCCESS',
                'message' => 'admin update successfully'
            ]);
        }
    }


    public function updatePassword(Request $request , $id){

        $validatedData = $request->validate([
            'old_password' => 'required ',
            'new_password' => 'required',

        ]);

        $admin= Admin::find($id);
        $admin_current_password=$admin->password;

        if (Hash::check($request->old_password,$admin_current_password)) {
            if($request->new_password==$request->old_password){
                return response()->json([
                    "message" => "current password and new password can't be same ",
                ]);
            }else{
                $admin->password=Hash::make($request->new_password);
                    if ($admin->save()) {
                        return response()->json([ "success" => "OK", "message" => "password changed successfully" ]);
                    }
            }
         }else{
             return response()->json([
                "message" => "current password is incorrect! ",
              ]);
         }



    }


    public function deactive($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $admin->status = 0;
            if ($admin->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'admin deactive successfully'
                ]);
            }
        }
    }


  public function active($id)
    {
        $admin = Admin::find($id);
        if ($admin) {
            $admin->status = 1;
            if ($admin->save()) {
                return response()->json([
                    'status' => 'SUCCESS',
                    'message' => 'admin active successfully'
                ]);
            }
        }
    }


    public function getAdminRole($id){
        $roles=Role::all();
        $admin=Admin::find($id);
        $admin_roles=DB::table('model_has_roles')->where('model_id',$id)->select('role_id')->pluck('role_id');
        return response()->json([
            'roles'=>$roles,
            'admin_roles'=>$admin_roles,
            'admin'=>$admin
        ]);
    }

    public function updateAdminRole(Request $request, $id){

       // return $request->all();
         $admin=Admin::find($id);
         $admin_roles=DB::table('model_has_roles')->where('model_id',$id)->delete();

         foreach($request->role as $role_id){
         $role=Role::where('id',$role_id)->first();
          $admin->assignRole($role);
       }
     return \response()->json('Adim Assign Role Updated');

    }



    public function getAdminPermission($id){

          $permissions = Permission::all();
          $admin_name=Admin::where('id',$id)->select('name')->get();
         $admin_has_permissions_id=DB::table('model_has_permissions')->where('model_id',$id)->select('permission_id')->pluck('permission_id') ;
          return response()->json([

              "status" => "OK",
              "permissions" => $permissions ,
              "admin_name"   => $admin_name ,
              "admin_has_permissions_id" => $admin_has_permissions_id ,

          ]);
    }




    public function assignAdminPermission(Request $request , $id){

      //  $model = Permission::findOrFail($id);
        $admin=Admin::findOrFail($id);

        //delete the previous permisson
        $model_has_permissons=DB::table('model_has_permissions')->where('model_id',$id)->delete();

        //insert the new permison
        foreach($request->permissons_id as $permison_id){
           $permission=Permission::where('id',$permison_id)->first();
           $admin->givePermissionTo($permission);
         }

       return response()->json([

            "status" => "OK",
            "message" => "Permission assigned successfully" ,

        ]);
  }







     public  function note_list(){

        $notes=AdminNote::orderBy('id','desc')->where('admin_id',session()->get('admin')['id'])->paginate(10);
        return response()->json([
            'success' => 'OK',
            'notes' => $notes,
        ]);

    }



    public  function store_note(Request $request)
    {
          $validateData= $request->validate([ 'text' => 'required' ]);
          $note=new AdminNote();
          $note->admin_id=session()->get('admin')['id'];
          $note->text=$request->text;
          if ($request->hasFile('attachment')) {
              $file_path=$request->file('attachment')->store('images/admin_attachment','public');
              $note->attachment=$file_path ;
          }
          $note->save();
          return response()->json([
               'success' => 'OK',
               'message' => 'Noted successfully'
          ]);
    }


     public  function delete_note($id){

        $note=AdminNote::findOrFail($id);
        $note->delete();
        return response()->json([
            'status' => 'OK',
            'message' => 'Deleted successfully'
        ]);

    }





}
