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

class ApiauthResponse
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
	public function handle($request, Closure $next, $cform='')
	{
		$garr 			= $this->getUserToken($request);
		$this->cform	= $cform; //中间件来源
		$token 	= $garr[0];
		$tokenagent 	= $garr[1];
		if(isempt($token))return $this->returnerror($request,''.$this->input_key.' is empty');
	
		$obj 	= new TokenModel();
		$user	= $obj->getTokenInfo($token);
		if(!$user)return $this->returnerror($request, ''.$this->input_key.' invalid');
		
		if($tokenagent != $obj->getuserAgent())return $this->returnerror($request, ''.$this->agent_key.' error');
		session(['usertoken'=>$token]); //保存起来
		Rock::setApiUser([
			'user.id' 	=> $user->id,
			'user.info' => $user,
		]);
		Rock::setToken($token);
		return $next($request);
	}
	
	private function returnerror($request, $msg)
	{
		if($request->ajax()){
			return response($msg, 401);
		}else{
			if($this->cform=='we'){
				$url = arrvalue($_SERVER,'REQUEST_URI', '/'.$request->path());
				return redirect('/we?backurl='.c('rockjm')->base64encode($url).'');
			}else{
				return abort(401, $msg.'登录失效,请退出重新登录');
			}
		}
	}
	
	/**
	*	获取用户token，优先从header从读取
	*/
	protected function getUserToken($request)
	{
		$token 	= $request->headers->get($this->input_key);
		if (isempt($token)) {
			$token 	= $request->input($this->input_key);
		}
		
		if (isempt($token)) {
			$token = session($this->input_key);
		}
		
		//从cookie读取
		if (isempt($token)) {
			$cokey	= ''.config('cache.prefix').'usertoken';
			$token 	= @$_COOKIE[$cokey];
			if($token)$token = c('rockjm')->uncrypt($token);
		}

		$agent	= md5(strtolower($request->userAgent()));
		
		return [$token, $agent];
	}
}
