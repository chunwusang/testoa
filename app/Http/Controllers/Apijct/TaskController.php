<?php
/**
 *    api-移动端/app等apikey接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use GatewayClient\Gateway;
use DB;

use App\Model\Base\UsersModel;
use Illuminate\Support\Facades\Auth;
use App\Model\Base\TokenModel;

header("Access-Control-Allow-Origin:*");

header('Access-Control-Allow-Methods:POST');

header('Access-Control-Allow-Headers:x-requested-with, content-type');

class TaskController extends ApijctController
{

    public function __construct(Request $request)
    {
        parent::__construct();
        Gateway::$registerAddress = '127.0.0.1:1236';
    }




    /**一下是定时任务**/
    //================================================================================


    //本来想通过mysql 触发http 请求完成工作，但是该插件只有linux 而且 1.0版本，我担心
    //不稳定，就使用定时任务执行，虽然更高资源写。后期量大后加入redis 减少资源消耗
    /*  定时任务  5s*/
    public function getTask_5s(Request $request)
    {
        //1得到所有在线uid
        $uid_list = Gateway::getAllUidList();
        if (count($uid_list) < 1) {
            return;
        }

        foreach ($uid_list as $aid) {
            //   http://oa.com/api/we/index/hncsuy
             //通过aid获取用户 userinfo 公司编号
            $ainfo = DB::select("select  a.uid,c.num  from  ".DB::getConfig('prefix')."usera as a LEFT join ".DB::getConfig('prefix')."company  as c on a.cid= c.id where a.id=$aid");
            $ainfo=  $this->objToArr($ainfo);
            $auth = Auth::guard('users');
            $bo = $auth->loginUsingId($ainfo[0]['uid']);
            $agent = md5(strtolower($request->userAgent()));
            $uobj = $auth->user();
            $toobj = new TokenModel();
            $token = $toobj->createToken($uobj->id, '', $agent);
            session(['usertoken' => $token]);
            $act = 'index';
            $cnum = $ainfo[0]['num'];
            $msg = $this->getApidata($act, $cnum, $request);
            $str['type'] = 'base_info';
            $str['code'] = 200;
            $str['data'] = $msg;
            $str['success'] = true;
            Gateway::sendToUid($aid, json_encode($str));
        }

        dd('发送成功');
    }


    /*  定时任务  1h 小时的*/
    public function getTask_1h(Request $request)
    {

        $new_message = array(
            'type' => 'say',
            'from_client_id' => 1,
            'from_client_name' => '哈哈哈',
            'to_client_id' => 'all',
            'content' => nl2br(htmlspecialchars('出现吧')),
            'time' => date('Y-m-d H:i:s'),
        );
        Gateway::sendToGroup(1, json_encode($new_message));
        dd('发送成功');
    }

    /**一下是工具函数**/
    //================================================================================


    public function postchatdata($act, $cnum = '', Request $request,$send_aid)
    {

        $request_data = $request->all();
        if ($act == 'reim_sendinfor' && $request_data['gid'] > 0 && Gateway::isUidOnline($request_data['gid']) === 1) { //像某人发送信息
            $lastdt = strtotime($request_data['optdt']);
            $request2 = $request;
            //首先清空
            foreach ($request_data as $k => $v) {
                $request2->offsetUnset($k);
            }
            //重新构建
            // http://oa.com/api/chat/reim_getrecord/8lkce1?type=user&gid=15&minid=0&lastdt=1544691913&loadci=1
            $this->useaid = $request_data['gid'];
            $request2->merge(['type' => 'user']);
            $request2->merge(['gid' => $request_data['gid']]);
            $request2->merge(['minid' => 0]);
            $request2->merge(['lastdt' => $lastdt]);
            $request2->merge(['loadci' => 1]);//loadci 为0 是首次加载
            $act2 = 'reim_getrecord';

            //	$obj->where('id','<', $minid); chat id  之前的记录
            //单聊只有两个人都在 单聊房间内才发送

            if (!$this->is_two_chatroom($send_aid, $request_data['gid'])) {
                return;
            }


            $msg = $this->getApidata($act2, $cnum, $request2);
            $str['type'] = 'say';
            $str['code'] = 200;
            $str['data'] = $msg;
            $str['success'] = true;
            Gateway::sendToUid($request_data['gid'], json_encode($str));
        }
    }

    public function objToArr($object)
    {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);

    }


    public function is_two_chatroom($uid1, $uid2)
    {
        $gid_info = DB::table('chat_clientinfo')->where(['uid' => $uid1])->orwhere(['uid' => $uid2])->get();
        $gid_info = $this->objToArr($gid_info);
        if (count($gid_info) < 2) {
            return false;
        }
        $info1 = Gateway::getSession($gid_info[0]['worker_client_id']);
        $info2 = Gateway::getSession($gid_info[1]['worker_client_id']);

        if (!empty($info1['single_room_name']) && $info1['single_room_name'] == $info2['single_room_name']) {
            return true;
        }
        return false;

    }
}

