<?php
/**
 *    api-移动端/app等apikey接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use GatewayClient\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Model\UserModel;
use App\Model\Base\UsersModel;

use DB;
use Rock;
class UserController extends ApijctController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        Gateway::$registerAddress = '127.0.0.1:1236';
    }

    /**
     * @interface User-change_name
     * @name 修改用户昵称
     *
     * @param nickname{String}(必须)昵称
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function change_name(Request $request){
     //获取参数
     $uarr = \Rock::getApiUser();
     $uid = $uarr['user.id'];
     $params=$request->all();
     if(!isset($params['nickname'])){
         $this->errorReturn('请输入姓名！');
     }
     //修改用户昵称
        $nickname=$params['nickname'];
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['nickname']==$nickname){
            $this->errorReturn('用户昵称没有改变！');
        }
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['nickname']=$nickname;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->errorReturn('修改名称失败！');
        }

        $this->successReturn('修改成功！');
    }
    /**
     * @interface User-change_mobile
     * @name 修改用户手机号码
     *
     * @param mobile{String}(必须)数字
     * @param code{int}(必须)数字
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function change_mobile(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        if(!isset($params['mobile'])){
             $this->errorReturn('手机号码不能为空！');
        }
        $mobile=$params['mobile'];
        //检验提交的数据
//        if(!c('check')->iscnmobile($params['mobile']))return $this->returnSuccess('手机号码格式有误');
//        if(!$this->base_checkcode()){
//            $this->errorReturn('短信验证码错误！');
//        }
        //操作修改手机号码
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['mobile']==$mobile){
            $this->errorReturn('新手机号码与原来手机号码相同！');
        }
        //检验短信验证码
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['mobile']=$mobile;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->errorReturn('修改手机号码失败！');
            return false;
        }
        $this->successReturn('修改手机号码成功！');
    }
    /**
     * @interface User-check_mobile
     * @name 检查手机号码
     *
     * @param mobile{String}(必须)手机号码
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function check_mobile(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        if(!isset($params['mobile'])){
            $this->errorReturn('手机号码不能为空！');
        }
        if(!c('check')->iscnmobile($params['mobile']))return $this->returnSuccess('手机号码格式有误');
        $mobile=$params['mobile'];
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['mobile']==$mobile){
            $this->errorReturn('新手机号码与原来手机号码相同！');
        }
        $result=UsersModel::select()->where('mobile',$mobile)->first();
        if($result){
            $this->errorReturn('该手机号码已经被使用，不能作为更换手机号！');
        }
        $this->successReturn('手机号码可用于更换！');
    }

    /**
     * @interface User-change_sex
     * @name 修改用户性别
     *
     * @param sex{String}(必须)性别参数
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function change_sex(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        if(!isset($params['sex'])){
            $this->errorReturn('获取不到性别参数！');
        }
        $sex=$params['sex'];
        //操作数据库更改性别
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['sex']==$sex){
            $this->successReturn('修改性别成功！');
        }
        //检验短信验证码
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['sex']=$sex;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->errorReturn('修改性别失败！');
        }
        $this->successReturn('修改性别成功！');
    }
    /**
     * @interface User-change_birthday
     * @name 修改用户生日
     *
     * @param birthday{String}(必须)生日（时间日期）
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function change_birthday(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];

        //修改用户生日操作start
        if(!isset($params['birthday'])){
            $this->errorReturn('获取不到生日参数！');
        }
        if(strtotime($params['birthday']) > time()){
            $this->errorReturn('用户生日参数有误！');
        }
        $birthday=$params['birthday'];
        //操作数据库更改性别
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['birthday']==$birthday){
            $this->successReturn('修改生日成功！');
        }
        //检验短信验证码
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['birthday']=$birthday;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->errorReturn('修改生日失败！');
        }
        $this->successReturn('修改生日成功！');
    }
    /**
     * @interface User-set_email
     * @name 绑定邮箱
     *
     * @param email{String}(必须)邮箱
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function set_email(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        $reg_email ='/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/';//邮箱
        //绑定邮箱操作
        if(!isset($params['email'])){
            $this->errorReturn('获取不到邮箱！');
        }
        if(!preg_match($reg_email,$params['email'])){
            $this->errorReturn('邮箱格式有误！');
        }
        $email=$params['email'];
        //操作数据库更改性别
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['email']==$email){
            $this->successReturn('绑定邮箱成功！');
        }
        //检验短信验证码
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['email']=$email;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->successReturn('绑定邮箱失败！');
        }
        $this->successReturn('绑定邮箱成功！');
    }
    /**
     * @interface User-set_email
     * @name 修改用户头像
     *
     * @param face{String}(必须)头像地址
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function change_face(Request $request){
        //获取参数
        $params=$request->all();
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        if(!isset($params['face'])||empty($params['face'])){
            $this->errorReturn('获取不到头像地址！');
        }
        $face=$params['face'];
        //操作数据库更改头像地址
        $res=UsersModel::select()->where('id',$uid)->first();
        if($res['face']==$face){
            $this->successReturn('绑定邮箱成功！');
        }
        //检验短信验证码
        $where=[];
        $where['id']=$uid;
        $data=[];
        $data['face']=$face;
        $result=DB::table('users')->where($where)->update($data);
        if($result==false){
            $this->successReturn('修改头像失败！');
        }
        $this->successReturn('修改头像成功！');
    }
    /**
     * @interface User-set_email
     * @name 提交实名认证身份照片
     *
     * @param positive_img{String}(必须)正面身份图片
     * @param aspect_img(String)必须反面身份图片
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function put_card_img(Request $request){
        //获取参数
        $params=$request->all();

        print_r($params);die;
    }
    /**
     * @interface User-real_name
     * @name 实名认证
     *
     * @param positive_img{String}(必须)正面身份图片
     * @param aspect_img(String)必须反面身份图片
     * @param usertoken{String}(必须)usertoken
     *
     * @return data{Object}数据 没有则为空
     *
     **/
    public function real_name(Request $request){
        //获取参数
        $params=$request->all();

        print_r($params);die;
    }




    public function getApidata($act, $cnum = '', Request $request)
    {
        $this->getCompanyInfo();
        $acta = explode('_', $act);
        $runa = arrvalue($acta, 1, 'getData');
        $obj = c('Weapi:' . $acta[0] . '');
        $msg = $obj->initUseainfo($cnum, $this->userinfo, $this->companyarr); //初始化
        if (!isempt($msg)) return $this->returnerror($msg);

        $barr = $obj->$runa($request);
        if (!$barr['success']) return $this->returnerror($barr['msg']);
        //  return $barr['data']; //不知道为什么要这样 可能返回后再封装，但是找不到封装函数
         if($act=='reim_getrecord'){$barr=$this->add_recordtype($barr);}
        return $barr;
    }




}