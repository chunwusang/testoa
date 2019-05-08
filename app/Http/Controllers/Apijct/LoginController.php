<?php
/**
 *    注册登录等
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use Illuminate\Http\Request;
use App\Model\Base\UsersModel;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;
use App\Http\Controllers\Controller;
use  DB;

class LoginController extends ApiController
{


    public function __construct()
    {
        //初始化  public $client_token = 'h4yIWsbbwS3Tq1JQQzo1';
        //    public $client_info ;
        $this->client_token = Request('client_token');

        $this->client_info = $this->getclient_info($this->client_token);

        if (empty($this->client_token) || empty($this->client_info)) {
            $info['code'] = 0;
            $info['msg'] = '必须携带client_token或client_token非法';
            die(json_encode($info));
        }
    }

    public function regCheck(Request $request)
    {
        $mobile = $request->mobile;
        $device = $request->input('device');

        // 验证输入。
        $this->validate($request, [
            'mobile' => 'required|numeric|unique:users',
            //'captcha' 	=> 'required|captcha',
            'mobileyzm' => 'required|mobileyzm:' . $mobile . ',reg,' . $device . '',
            'name' => 'required',
            'pass' => 'required|between:6,30',
        ], [
            'mobile.unique' => trans('users/reg.mobilecz'),
            'name.required' => trans('users/reg.namerequired'),
        ]);


        $obj = new UsersModel();
        $obj->mobilecode = nulltoempty($request->mobilecode);
        $obj->mobile = $mobile;
        $obj->name = $request->name;
        $obj->nickname = $request->name;
        $obj->password = $request->pass;
        $obj->email = $request->email;
        $obj->flaskm = 0;
        $obj->save();
        c('log')->adds('用户注册', '[' . $mobile . ']注册', $obj->name, $obj->id);

        $toobj = new TokenModel();
        $token = $toobj->createToken($obj->id, 'api');
        $this-> update_usertoken($obj->id, $token);

        $info['code'] = 200;
        $info['msg'] = '注册成功';
        $info['success'] = true;
        $info['data']['uid'] = $obj->id;
        $info['data']['name'] = $obj->name;
        $info['data']['usertoken'] = $token;
        die(json_encode($info));
    }

    /**
     *    api登录验证
     */
    public function loginCheck(Request $request)
    {
        $this->validate($request, [
            'user' => 'required',
            'pass' => 'required'
        ]);
        if(preg_match("/^1[34578]{1}\d{9}$/",$request->input('user'))){
           // "是手机号码";
            $to = UsersModel::where('mobile', $request->input('user'))->count();
            if($to==0){
                $info['code'] = 410;
                $info['msg'] = '手机号码不存在';
                die(json_encode($info));
            }
        }else{
            // "不是手机号码";
            $to = UsersModel::where('userid', $request->input('user'))->count();
            if($to==0){
                $info['code'] = 410;
                $info['msg'] = '用户不存在';
                die(json_encode($info));
            }
        }

        $user = htmlspecialchars($request->input('user'));
        $pass = $request->input('pass');
        $cfrom = $request->input('cfrom', 'pc');
        $loglx = 'userid';
        $check = c('check');
        if ($check->iscnmobile($user)) $loglx = 'mobile';
        if ($check->isemail($user)) $loglx = 'email';

        $auth = Auth::guard('users');
        $bo = $auth->attempt([$loglx => $user, 'password' => $pass, 'status' => 1], false);

        if (!$bo) {
            c('log')->adderror('' . $cfrom . '登录', '[' . $user . ']登录失败', $user);
            $info['code'] = 400;
            $info['msg'] = '[' . $user . ']登录失败';
            die(json_encode($info));
        }

        $agent = md5(strtolower($request->userAgent()));

        $uobj = $auth->user();
        $toobj = new TokenModel();
        $token = $toobj->createToken($uobj->id, $cfrom, $agent);

        $this->update_usertoken($uobj->id, $token);

        //检查其是否为常用设备
        $is_common_device = true;
        if ($this->client_info['type'] == 'ios' || $this->client_info['type'] == 'android') {
            $is_common_device = $this->check_device_sn($uobj->id, $request->input('device_sn'));
        }

        c('log')->adds('' . $cfrom . '登录', '[' . $user . ']登录成功', $uobj->name, $uobj->id);
        $info['code'] = 200;
        $info['msg'] = 'success';
        $info['uid'] = $uobj->id;
        $info['mobile'] = $uobj->mobile;
        $info['moren_cid'] = $uobj->devcid;
        $info['is_common_device'] = $is_common_device; //是否常用设备
        $info['moren_company'] = $this->objToArr(DB::table('company')->leftjoin('usera', 'usera.cid', '=', 'company.id')->select('company.*', 'usera.id as aid')->where(['company.id' => $uobj->devcid])->first());
        $info['usea_info'] = $this->objToArr(DB::table('usera')->join('company', 'usera.cid', '=', 'company.id')->select('company.num as cnum', 'company.id as cid', 'usera.id as aid')->get());
        $info['user_info'] = [
            'usertoken' => $token,
            'face' => $uobj->face,
            'bootstyle' => $uobj->bootstyle,
            'title' => config('app.name')
        ];
        return $info;
    }


    /**
     *    找回密码
     */
    public function findCheck(Request $request)
    {
        $mobile = $request->mobile;
        $mobilecode = nulltoempty($request->mobilecode);
        $device = $request->input('device');

        // 验证输入。
        $this->validate($request, [
            'mobile' => 'required|numeric',
            'mobileyzm' => 'required|mobileyzm:' . $mobile . ',find,' . $device . '',
            'pass' => 'required|between:6,30',
            //'captcha' 	=> 'required|captcha',
        ]);

        $ors = UsersModel::where('mobile', $mobile)->where('mobilecode', $mobilecode)->first();
        if (!$ors) return $this->returnerrors($request, 'mobile', trans('users/reg.regnot'));

        $ors->password = $request->pass;
        $ors->save();
        c('log')->adds('找回密码', '[' . $mobile . ']找回密码', $ors->name, $ors->id);

        $info['code'] = 200;
        $info['msg'] = '重置密码成功';
        $info['success'] = true;
        $info['data']['uid'] = $ors->id;
        die(json_encode($info));
    }


    /**
     * 第三方登录
     */
    public function authLogin(Request $request)
    {
        $nickname = $request->input('nickname');
        $uid = $request->input('uid');
        $auth_type = $request->input('auth_type'); //qq wx sn
        $face = $request->input('face'); //qq wx sn

        $thirdauth = $this->objToArr(DB::table('auththird')->where(['third_uid' => $uid])->first());
        if (!$thirdauth) {
            $info['code'] = 401;
            $info['msg'] = '请先设置用户手机号码';
            $info['success'] = false;
            die(json_encode($info));
        }
        $user = UsersModel::where('id', $thirdauth['uid'])->first();

        //更新昵称头像
        $user->name = $nickname;
        $user->nickname = $nickname;
        $user->face = $face;
        $user->save();

        $toobj = new TokenModel();
        $token = $toobj->createToken($user->id, '', '');
        $this->update_usertoken($user->id, $token);

        //检查其是否为常用设备
        $is_common_device = true;
        if ($this->client_info['type'] == 'ios' || $this->client_info['type'] == 'android') {
            $is_common_device = $this->check_device_sn($user->id, $request->input('device_sn'));
        }

        c('log')->adds('' . $auth_type . '登录', '[' . $auth_type . '_' . $uid . ']登录成功', $nickname, $user->id);
        $info['code'] = 200;
        $info['msg'] = 'success';
        $info['uid'] = $user->id;
        $info['mobile'] = $user->mobile;
        $info['moren_cid'] = $user->devcid;
        $info['is_common_device'] = $is_common_device; //是否常用设备
        $info['moren_company'] = $this->objToArr(DB::table('company')->leftjoin('usera', 'usera.cid', '=', 'company.id')->select('company.*', 'usera.id as aid')->where(['company.id' => $user->devcid])->first());
        $info['usea_info'] = $this->objToArr(DB::table('usera')->join('company', 'usera.cid', '=', 'company.id')->select('company.num as cnum', 'company.id as cid', 'usera.id as aid')->get());
        $info['user_info'] = [
            'usertoken' => $token,
            'face' => $user->face,
            'bootstyle' => $user->bootstyle,
            'title' => config('app.name')
        ];
        return $info;
    }



    //设置账号密码
    public function authSetInfo(Request $request)
    {
        $face = $request->input('face');
        $nickname = $request->input('nickname');
        $uid = $request->input('uid');
        $auth_type = $request->input('auth_type'); //qq wx sn
        $mobile = $request->input('mobile'); //qq wx sn
        $device = $request->input('device');

        // 验证输入
        $yzm = c('rockapi')->checkcode($mobile, $request->input('mobileyzm'), 'normal', $device);

        if ($yzm['code'] != 200) {
            $yzm['code'] = 411;
            die(json_encode($yzm));
        }
         $user = UsersModel::where('mobile', $mobile)->first();
         $thirdauth = DB::table('auththird')->where(['third_uid' => $uid])->first();
          if (!$user && !$thirdauth) {
              // 新增 用户
              $obj = new UsersModel();
              $obj->mobilecode = '';
              if ($request->pass) {
                  $user->password = $request->pass;
              }
              $obj->userid = $mobile;
              $obj->mobile = $mobile;
              $obj->name = $nickname;
              $obj->nickname = $nickname;
              $obj->password = '';
              $obj->face = $face;
              $obj->flaskm = 0;
              $obj->save();
              c('log')->adds('用户注册', '[' . $auth_type . '_' . $uid . ']注册', $obj->name, $obj->id);
              $thirdauth = [];
              $thirdauth['uid'] = $obj->id;
              $thirdauth['third_uid'] = $uid;
              $thirdauth['nickname'] = $nickname;
              $thirdauth['face'] = $face;
              $thirdauth['auth_type'] = $auth_type;
              DB::table('auththird')->insert($thirdauth);
              //
          } elseif ($user && !$thirdauth) {
              $thirdauth = [];
              $thirdauth['uid'] = $user->id;
              $thirdauth['third_uid'] = $uid;
              $thirdauth['nickname'] = $nickname;
              $thirdauth['face'] = $face;
              $thirdauth['auth_type'] = $auth_type;
              DB::table('auththird')->insert($thirdauth);
          }

        $info['code'] = 200;
        $info['msg'] = '设置成功，重新授权登录即可';
        $info['success'] = true;
        die(json_encode($info));
    }

}