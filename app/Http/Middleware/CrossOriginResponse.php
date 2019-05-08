<?php
/**
*	api接口中间件,跨域的
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Middleware;

use Closure;

class CrossOriginResponse
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
		
		$response 	= $next($request);
		$origin 	= $request->header('origin', $request->header('referer'));
		$host		= $request->header('Host');
		
		//是跨域请求
		if(!contain($origin, $host)){
			$orgis 	= $this->getOrigin($origin, $request);
			if($orgis){
				$response->headers->set('Access-Control-Allow-Origin', $orgis);
				$response->headers->set('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, usertoken');
				$response->headers->set('Access-Control-Allow-Credentials', 'true');
			}
		}
		
        return $response;
	}
	
	/**
	*	判断是否允许的Origin
	*/
	private function getOrigin($origin, $request)
	{
		if($origin=='null')return $origin;
		$orlist 	= env('ALLOW_ORIGIN');
		if($orlist=='*')return '*';
		if(isempt($orlist))return;
		$orlisa 	= explode(',', $orlist);
		foreach($orlisa as $or1)if(contain($origin, $or1))return $or1;
	}
}
