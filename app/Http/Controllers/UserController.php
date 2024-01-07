<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function create_signin(){
        return view('signin');
    }

    public function store_signin(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|max:255'
        ]);

        $user_checker = User::where('email',$request->email)->get()->first();
        if($user_checker){
            if(hash::check($request->password,$user_checker->password)){
                $request->session()->put('id_sign_in',$user_checker->id);
                return redirect('/index-transaksi');
            }
            return redirect('/')->with('message','Sign In Gagal');
        }else{
            return redirect('/')->with('message','Sign In Gagal');
        }

    }

    public function logout(){
        if(Session::has("id_sign_in")){
            Session::pull("id_sign_in");
            return redirect('/');
        }
    }

    public function create_signup(){
        return view('signup');
    }

    public function store_signup(Request $request){
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->save();

        $request->session()->put('id_sign_in',$data->id);

        return redirect('/index-transaksi');
    }
}
