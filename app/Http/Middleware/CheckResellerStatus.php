<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CheckResellerStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        Log::info('=== RESELLER STATUS MIDDLEWARE DEBUG ===', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp' => now(),
        ]);

        // Check if customer or admin is authenticated
        $isCustomerAuth = Auth::guard('customer')->check();
        $isWebAuth = Auth::guard('web')->check();
        
        Log::info('Middleware Authentication Check', [
            'customer_auth' => $isCustomerAuth,
            'web_auth' => $isWebAuth,
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
        ]);

        // If admin is authenticated, allow access for checkout (admin can order on behalf of customers)
        if ($isWebAuth && !$isCustomerAuth) {
            $adminUser = Auth::guard('web')->user();
            
            Log::info('Middleware - Admin Access Granted', [
                'admin_id' => $adminUser->user_id,
                'admin_name' => $adminUser->name,
                'admin_email' => $adminUser->email,
                'role_id' => $adminUser->role_id,
                'action' => 'admin_checkout_allowed'
            ]);

            // Allow admin to proceed to checkout
            return $next($request);
        }

        // If neither customer nor admin is authenticated
        if (!$isCustomerAuth && !$isWebAuth) {
            Log::warning('Middleware - No Authentication', [
                'redirect_to' => 'login',
                'reason' => 'no_authentication',
                'available_guards' => [
                    'customer' => $isCustomerAuth,
                    'web' => $isWebAuth,
                ]
            ]);

            // Check if we can redirect to a different page to avoid loop
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Authentication required',
                    'redirect' => route('login')
                ], 401);
            }

            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // If customer is authenticated, check their status
        if ($isCustomerAuth) {
            $customer = Auth::guard('customer')->user();
            
            Log::info('Middleware Customer Data', [
                'customer_id' => $customer->customer_id,
                'name' => $customer->name_customer,
                'email' => $customer->email_customer,
                'status' => $customer->status,
                'customer_type_id' => $customer->customer_type_id,
            ]);

            // Cek status reseller/customer
            if ($customer->status !== 'Aktif') {
                Log::error('Middleware - Customer Status Not Active', [
                    'customer_id' => $customer->customer_id,
                    'current_status' => $customer->status,
                    'required_status' => 'Aktif',
                    'action' => 'logout_and_redirect',
                ]);

                Auth::guard('customer')->logout();
                $request->session()->invalidate();
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Customer status not active',
                        'current_status' => $customer->status,
                        'redirect' => route('login')
                    ], 403);
                }

                return redirect()->route('login')->with('error', 'Akun Anda sedang menunggu konfirmasi atau tidak aktif. Status: ' . $customer->status);
            }

            Log::info('Middleware - Customer Status Valid', [
                'customer_id' => $customer->customer_id,
                'status' => $customer->status,
                'action' => 'proceed_to_next',
            ]);
        }

        return $next($request);
    }
}
