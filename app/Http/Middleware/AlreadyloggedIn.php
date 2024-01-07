<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AlreadyloggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Session::has('id_sign_in') && url('/') == $request->url()){
            // logika middleware ini digunakan untuk mengecek apakah pengguna sudah memiliki session dan mencoba memasuki url '/'
            return redirect()->back();
        }
        return $next($request);
    }
}
