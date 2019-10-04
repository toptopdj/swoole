<?php

namespace app\http\middleware;

class checkUser
{
    public function handle($request, \Closure $next)
    {
    	if (!session('userInfo')) {
			return redirect('index/index/login');
	    } else {
    		return $next($request);
	    }
    }
}
