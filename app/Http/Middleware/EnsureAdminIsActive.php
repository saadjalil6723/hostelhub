<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminIsActive
{
    /**
     * Extra safety net: even if a session survives, a disabled admin
     * account is logged out immediately on the next request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admin')->user();

        if ($admin && ! $admin->is_active) {
            Auth::guard('admin')->logout();
            $request->session()->invalidate();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'This administrator account has been disabled.']);
        }

        return $next($request);
    }
}
