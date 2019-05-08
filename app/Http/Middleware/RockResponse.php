<?php
/**
*	系统中间件
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Middleware;
use Closure;
use Rock;

class RockResponse
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
		$msg 	= Rock::initCheck();
		if($msg)return response($msg, 401);
		return $next($request);
	}
	
	
}
