<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{

    public function loginPage() {
        return Inertia::render('Auth/LoginPage');
    }

    public function userLogin(Request $request) {

           $request->validate([
               'email'=>'required|email',
               'password'=>'required|min:8',
           ]);
           $count=User::where('email','=',$request->email)->first();
           if($count != null && Hash::check($request->password,$count->password)){

                $request->session()->put('email',$count->email);
                $request->session()->put('name',$count->name);
                $request->session()->put('user_id',$count->id);
                $request->session()->put('role',$count->role);
                $data=['message'=>'User login Successfully','status'=>true,'error'=>''];
               if($count->role=='superadmin' || $count->role=='admin'){
                return redirect('/dashboard-page')->with( $data);
               }else{
                return redirect('/moderator-dashboard')->with($data);
               }

           }else{

                return redirect()->back()->with(['message'=>'User login Fail','status'=>'fail','error'=>'something went wrong']);
           }
    }

    public function userPage(Request $request){
        $users=User::get();
        return Inertia::render('User/UserListPage',['users'=>$users]);
    }
    public function userSavePage(Request $request){
        $id=$request->query('id');
        $user=User::where('id','=',$id)->first();
        return Inertia::render('User/UserSavePage',['users'=>$user]);
    }


    public function createUser(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8',
            'mobile'=>'required',
            'role'=>'required',
        ]);

        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'mobile'=>$request->mobile,
            'role'=>$request->role,
        ];
        User::create($data);
        return redirect()->back()->with(['status'=>true,'message'=>'User created successfully','error'=>'']);
    }

    public function updateUser(Request $request){
        $request->validate([
            'name'=>'required|string',
            'password'=>'required|min:8',
            'mobile'=>'required',
            'role'=>'required',
        ]);
        $data=[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'mobile'=>$request->mobile,
            'role'=>$request->role,
        ];
        $id=$request->id;
        User::where('id','=',$id)->update($data);
        return redirect()->back()->with(['status'=>true,'message'=>'User updated successfully','error'=>'']);
    }

    public function deleteUser(Request $request){
        $id=$request->id;
        User::where('id','=',$id)->delete();
        return redirect()->back()->with(['status'=>true,'message'=>'User deleted successfully','error'=>'']);
    }

    public function userLogout(Request $request) {
        $request->session()->flush();
        return redirect()->route('loginPage');
   }
}
