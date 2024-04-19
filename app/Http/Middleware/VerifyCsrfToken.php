<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier {

	/**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    // protected $except = [
    //     '/api/*',
    // ];
	
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	// public function handle($request, Closure $next)
	// {
	// 	return parent::handle($request, $next);
	// }

	private $openRoutes = [
		'*',
		'api/user/login',
		'api/games',
		'free/route', 
		'free/too'];

	//modify this function
	public function handle($request, Closure $next)
		{
			return $next($request);
			//add this condition 
			foreach($this->openRoutes as $route) {

			if ($request->is($route)) {
				return $next($request);
			}
		}

		return parent::handle($request, $next);
	}
}
