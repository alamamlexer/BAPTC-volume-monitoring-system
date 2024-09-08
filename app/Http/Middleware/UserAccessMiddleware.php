<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth ;
class UserAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if (!empty(Auth::user()) && Auth::user()->type==0){
            return $next($request);
            }
        elseif(!empty(Auth::user()) && Auth::user()->type==1){
            return redirect('admin-dashboard');
        }
        else{
            return redirect('/');
        };
            
    }
}
