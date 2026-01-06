<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckMustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Redirect to change password page if not already there
            if (!$request->is('change-password') && !$request->is('logout')) {
                return redirect()->route('change-password')
                    ->with('warning', 'Untuk keamanan akun Anda, silakan ubah password terlebih dahulu.');
            }
        }

        return $next($request);
    }
}
