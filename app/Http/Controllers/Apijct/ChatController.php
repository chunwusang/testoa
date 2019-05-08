<?php
/**
 *    api-移动端/app等apikey接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use Illuminate\Http\Request;
use GatewayClient\Gateway;
use DB;


header("Access-Control-Allow-Origin:*");

header('Access-Control-Allow-Methods:POST');

header('Access-Control-Allow-Headers:x-requested-with, content-type');

class ChatController extends ApijctController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        Gateway::$registerAddress = '127.0.0.1:1236';
    }



    /**一下是业务**/
    //================================================================================

    /**
     * 绑定uid
     * @return mixed
     */
    public function bind(Request $request)
    {

        // 验证输入。
        if (empty(Request('company')) or empty(Request('company')) or empty(Request('client_id'))) {
            $info['code'] = 100;
            $info['msg'] = '参数不全';
            return response()->json($info);
        }

        $this->getCompanyId($request, Request('company'));

        $client_id = request('client_id');
        $res = request()->all();
        $res['type'] = 'bind';
        $res['time'] = date('H:i:s');
        // client_id与uid绑定
        Gateway::bindUid($client_id, $this->useaid);
        //更新数据库
        $last_time = time();
        DB::insert("INSERT INTO " . DB::getConfig('prefix') . "chatclientinfo(uid,worker_client_id,last_time) VALUE($this->useaid,'$client_id', $last_time) ON DUPLICATE KEY UPDATE worker_client_id= '$client_id' , last_time = $last_time");
        if (!empty(request('gid'))) {  //单聊
            //判断对方是否已经在 单聊房间内
            $gid_info = DB::table('chatclientinfo')->where(['uid' => request('gid')])->get();
            $gid_info = $this->objToArr($gid_info);

            if (empty($gid_info)) {  //从没有登录过
                //自己创建单聊房间
                $single_room_name = 'two_' . $this->useaid . '_' . request('gid');
                Gateway::joinGroup($client_id, $single_room_name);
                $chat_info['single_room_name'] = $single_room_name;
                Gateway::setSession($client_id, $chat_info);
            } else {
                //查看他目前现在的单聊房间 是不是双方的房间

                $room_info = Gateway::getSession($gid_info[0]['worker_client_id']);
                $room1 = 'two_' . $this->useaid . '_' . request('gid');
                $room2 = 'two_' . request('gid') . '_' . $this->useaid;
              //  var_dump($room_info,$gid_info[0]['worker_client_id'],$gid_info);
                @$room_info['single_room_name'] = array_key_exists('single_room_name', $room_info) ? $room_info['single_room_name'] : '';
                if (!empty($room_info['single_room_name']) && $room_info['single_room_name'] == $room1) {
                    //那么就加入该房间
                    Gateway::joinGroup($client_id, $room1);
                    $chat_info['single_room_name'] = $room1;
                    Gateway::setSession($client_id, $chat_info);

                } elseif ($room_info['single_room_name'] == $room2) {
                    //那么就加入该房间
                    Gateway::joinGroup($client_id, $room2);
                    $chat_info['single_room_name'] = $room2;
                    Gateway::setSession($client_id, $chat_info);
                } else {

                    //自己创建单聊房间
                    $single_room_name = 'two_' . $this->useaid . '_' . request('gid');
                    Gateway::joinGroup($client_id, $single_room_name);
                    $chat_info['single_room_name'] = $single_room_name;
                    Gateway::setSession($client_id, $chat_info);
                }
            }
        }
        Gateway::sendToUid($this->useaid, json_encode($res));
        if (empty($chat_info)) {
            $chat_info['single_room_name'] = '';
        }
        $r['msg'] = '绑定成功 uid 是' . $this->useaid . 'room_name是' . $chat_info['single_room_name'];
        $r['code'] = 200;
        return response()->json($r);
    }

    /*告诉用户正在输入中
     * **/
    public function user_inputting(Request $request)
    {
        $aid = Request('aid');
        if($this->is_two_chatroom($aid, $this->useaid)){
            $str['type'] = 'inputting';
            $str['code'] = 200;
            $str['data']['aid'] = $this->useaid;
            $str['data']['msg'] = '对方正在输入中';
            $str['success'] = true;
            Gateway::sendToUid($aid, json_encode($str));
        }

        $info['code'] = 200;
        return response()->json($info);
    }

}

