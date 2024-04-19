<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SessionTimeout {

    protected $session;
    protected $timeout = 12;

    public function __construct(Store $session){
        $this->session = $session;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info(Auth::user()->roleid);
        $isLoggedIn = $request->path() != '/logout' 
        && $request->path() != '/reload-user'
        && $request->path() != '/refresh-time';
        if(! session('lastActivityTime'))
            $this->session->put('lastActivityTime', time());
        elseif(time() - $this->session->get('lastActivityTime') > $this->timeout){
            $this->session->forget('lastActivityTime');
            // $cookie = cookie('intend', $isLoggedIn ? url()->current() : 'dashboard');
            // $email = $request->user()->email;    
            if(!Auth::guest() && Auth::user()->roleid == 6){
                Auth::logout();
                Session::flush();
                return redirect(url('/'));
            }
            // return message('You had not activity in '.$this->timeout/60 .' minutes ago.', 'warning', 'login')->withInput(compact('email'));
            //->withCookie($cookie);
        }
        $isLoggedIn ? $this->session->put('lastActivityTime', time()) : $this->session->forget('lastActivityTime');
        return $next($request);
    }

}