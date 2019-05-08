<?php
/**
*	api接口
*	主页：http://www.rockoa.com/
*	软件：OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2017-12-05
*/

namespace App\Http\Controllers\Apijct;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Rock;

//因为不需要登录状态，不用验证继承Controller
class AuthController extends ApiController
{
	

	
	/**
	*	验证api中间件
	*/
	public function __construct()
    {

		//parent::__construct();
		//$this->middleware('apiauth');
    }
	


	//换取token
    public function get_clienttoken(Request $request){
	    $input_data= $request->all();
        $input_data['key'];
        $input_data['md5code']='';
        $token_info = DB::table('authtoken')->where(['key' => $input_data['key']])->get();
        $token_info = $this->objToArr($token_info);
        $token_info = $token_info[0];
        $info['code']= 200 ;
        $info['msg']= 'success' ;
        $info['client_token']= $token_info['token'] ;
        return  response()->json($info);
    }

    public function get_startpage(){
        $page_info = DB::table('startpage')->get();
        $page_info = $this->objToArr($page_info);
        foreach ( $page_info as $k=>$v){
            $page_info[$k]['page_img'] =env('APP_URL').\Rock::replaceurl( $v['page_img']);
        }
        $info['code']=200;
        $info['data']=$page_info;
        return $info;
    }



}
