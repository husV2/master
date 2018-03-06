<?php
namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;

class CacheProfilePic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!empty($user) && !$request->session()->has('profilepic')) {
            $avatar = $user->settings->avatar;
            if(empty($avatar) || is_null($avatar))
            {
                $avatar = 'placeholder.png';
            }
            $request->session()->put('profilepic', $avatar);
        }
        return $next($request);
    }
    
}
