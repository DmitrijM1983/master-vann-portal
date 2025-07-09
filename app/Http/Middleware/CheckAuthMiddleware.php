<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        if (Auth::check()) {
            $role = User::find(Auth::user()->id)->role;

            if ($role->title === 'master') {
                return redirect()->route('profile', ['id' => Auth::user()->id] );
            }
           // return redirect()->route('index');
        }

        return $next($request);
    }
}
