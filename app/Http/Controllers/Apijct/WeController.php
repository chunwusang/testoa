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

use DB;
class WeController extends ApijctController
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        Gateway::$registerAddress = '127.0.0.1:1236';
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

    /**
     *    post方法
     */
    public function postApidata($act, $cnum = '', Request $request)
    {
        $this->getCompanyInfo();
        $this->getCompanyId($request, $cnum);
        $acta = explode('_', $act);
        $runa = 'post' . arrvalue($acta, 1, 'Data');
        $obj = c('Weapi:' . $acta[0] . '');

        $msg = $obj->initUseainfo($cnum, $this->userinfo, $this->companyarr); //初始化
        if (!isempt($msg)) return $this->returnerror($msg);
        $barr = $obj->$runa($request);
        if (!$barr['success']) return $this->returnerror($barr['msg']);
        //发送及时聊天信息
        $this->postchatdata($act, $this->useaid, Request('gid'), $request);

        //  return $barr['data']; //不知道为什么要这样 可能返回后再封装，但是找不到封装函数
        return $barr;
    }


//因为前段觉得太麻烦，不远理解逻辑 所以单独拆分api
//==================================================================================================================

    public function index_chatlist(Request $request)
    {

        $barr = $this->getApidata('index', '', $request);
        if ($barr['code'] == 200) {
            $barr['charhist'] = $barr['data']['charhist'];
            unset($barr['data']);

            return $barr;
        }
    }

    public function index_agenharrlist(Request $request)
    {
        $sort_k= array(0=>'jb', 1=>'zy', 2=>'lc', 3=>'wp', 4=>'rs', 5=>'kh', 6=>'kq', 7=>'xt',8=>'gj');
        $sort= array('jb'=>[], 'zy'=>[], 'lc'=>[], 'wp'=>[], 'rs'=>[], 'kh'=>[], 'kq'=>[], 'xt'=>[], 'gj'=>[]);
        $barr = $this->getApidata('index', '', $request);
        if ($barr['code'] == 200) {
            $barr['agenharr'] = $barr['data']['agenharr'];
            unset($barr['data']);
            foreach ($barr['agenharr'] as $k=>$v){
               if(array_key_exists(pinyin_abbr($k), $sort) ){
                   $sort[pinyin_abbr($k)]['name']=$k;
                   $sort[pinyin_abbr($k)]['list']=$v;
                   $sort[pinyin_abbr($k)]['sort']=array_search(pinyin_abbr($k),$sort_k);//专门解决ios排序
               }
                unset($barr['agenharr'][$k]);
            }
            $barr['agenharr']=$sort;
            return $barr;
        }
    }
    

    public function get_cnumbyaid(Request $request)
    {
        $aid = Request('aid');

        $where2['id'] = $aid;
        $usera = DB::table('usera')->where($where2)->first();

        $where1['uid'] = $this->userid;
        $where1['cid'] = $usera->cid;

        $user = DB::table('usera')->where($where1)->first();

        $data=$this->objToArr($user);

        if (empty($data)){
            $info['code']=400;
            $info['msg'] = '两用户不在同一公司';
            die(json_encode($info));
        }
         $company = DB::table('company')->where(['id'=>$data['cid']])->first();

        $info['code']=200;
        $info['data'] = $this->objToArr($company);
        die(json_encode($info));
    }

    /**一下是工具函数**/
    //================================================================================


    public function add_recordtype($barr){

        $rows= $barr['data']['rows'];
        foreach ($rows as $k=>$v){
            if($v->fileid>0){
                $rows[$k]->type='file';
            }else{
                $rows[$k]->type='text';
            }
        }
        $barr['data']['rows'] =$rows;
        return $barr;
    }
    public function postchatdata($act, $send_aid, $receiver_aid, Request $request)
    {

        if ($act == 'reim_sendinfor') {
            $receiver_info = $this->receiver_info($receiver_aid);

            $lastdt = strtotime(Request('optdt'));
            $msg = $this->getrecord_info($send_aid, $receiver_aid, $lastdt, 1);
            $str['type'] = 'say';
            $str['code'] = 200;
            $str['data'] = $msg;
            $str['success'] = true;

            if ( $receiver_aid > 0 && Gateway::isUidOnline($receiver_aid) === 1) { //像某人发送信息
                //如果两个人不在一个房间 并且不是ios 跳过
                if (!$this->is_two_chatroom($send_aid, $receiver_aid) && !in_array("ios", $receiver_info['login_type'])) {
                    return;
                }
                $str['type'] = 'say';
                $str['code'] = 200;
                $str['data'] = $msg;
                $str['success'] = true;
                Gateway::sendToUid($receiver_aid, json_encode($str));
                return;
            }

            if (!$this->is_two_chatroom($send_aid, $receiver_aid) && in_array("ios", $receiver_info['login_type'])) {
                $Alias = (string)$receiver_info['uid'];
                $type_info['send_aid'] = $send_aid;
                $type_info['receiver_aid'] = $receiver_aid;
                $str['type'] = 'say';
                $str['code'] = 200;
                $str['data'] = $msg;
                $str['type_info']['cat'] = 'say_chat_two';
                $str['type_info']['data'] = $type_info;
                $str['success'] = true;
                $title_jpush=$msg['rows'][0]->sendname.':'.c('rockjm')->base64decode($msg['rows'][0]->cont);
                $this->jpush_user($Alias,$title_jpush, json_encode($str));
                return;
            }
        }
    }


}