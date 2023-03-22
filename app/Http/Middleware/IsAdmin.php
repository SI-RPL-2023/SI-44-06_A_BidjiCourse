<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guest()){
            return redirect('/login');

        } elseif (!Auth::user()->is_admin){
            // return response()->view('403', [
            //     'title' => '403'
            // ]);
            abort(403);
            
        } else {
            return $next($request);
        }
    }
}
