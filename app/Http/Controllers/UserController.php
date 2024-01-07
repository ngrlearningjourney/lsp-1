<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function create_signin(){
        // method yang digunakna untuk menampilkan halaman sign in
        return view('signin');
    }

    public function store_signin(Request $request){
        // method yang digunakan untuk menganalisa apakah pendatang yang masuk merupakan user atau tidak

        // dimulai dengan memavalidasi inputnya
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4|max:255'
        ]);

        // mengecek dia tergabung di dalam user table
        $user_checker = User::where('email',$request->email)->get()->first();
        if($user_checker){
            // mengecek password yang telah dimasukkan apakah sesuai dengan yang ada pada database
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
        // method untuk log out dan penghapusan session
        if(Session::has("id_sign_in")){
            Session::pull("id_sign_in");
            return redirect('/');
        }
    }

    public function create_signup(){
        // method untuk memunculkan form sign up
        return view('signup');
    }

    public function store_signup(Request $request){
        // method untuk memasukkan data signup kepada database

        // dimulai dari cek input
        $request->validate([
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // hingga pembuatan data user baru
        $data = new User();
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->save();

        // serta pemberian session
        $request->session()->put('id_sign_in',$data->id);

        return redirect('/index-transaksi');
    }
}
