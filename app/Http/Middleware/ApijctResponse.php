<?php
/**
*	api接口中间件，验证apikey获取用户信息
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Middleware;
use Closure;
use App\Model\Base\TokenModel;
use Rock;
use Illuminate\Support\Facades\Cookie;

class ApijctResponse
{
	
	protected $input_key = 'usertoken';
	protected $agent_key = 'useragent';

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */


}
