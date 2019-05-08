<?php
/**
 *    api接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use App\Http\Controllers\Controller;
use DB;
use App\Model\Base\BaseModel;
use App\Model\Base\ImmessModel;
use App\Model\Base\ImmessztModel;
use App\Model\Base\ImchatModel;
use JPush\Client as JPushClient;
use GatewayClient\Gateway;
use Illuminate\Http\Request;
class ApiController extends Controller
{
    public $client_token = 'h4yIWsbbwS3Tq1JQQzo1';
    public $client_info;
    public $usertoken = 'm063ddjnqm';
    public $usertoken_info;
//======================================================
    public $userid ;
    public $useaid ;
    public $userinfo;
    public $useainfo;
    public $companyarr = false; //对应单位下用户usera.id数组
    public $companyinfo = false; //对应用户所在的单位
    public $companyid = 0; //单位Id
    public $agenhinfo;
    //===========工具函数======================================
    protected function update_usertoken($uid, $usertoken)
    {
        $where['uid'] = $uid;
        $where['login_type'] = $this->client_info['type'];
        $old_info = DB::table('usertoken')->where($where)->first();
        $usertoken_info = [];
        $usertoken_info['update_time'] = time();
        $usertoken_info['dead_time'] = time() + 24 * 3600 * 365;//过期时间
        $usertoken_info['usertoken'] = $usertoken;
        $usertoken_info['login_type'] = $this->client_info['type'];
        $usertoken_info['client_token'] = $this->client_info['token'];
        $usertoken_info['uid'] = $uid;
        if (empty($old_info)) {
            DB::table('usertoken')->insert($usertoken_info);
        } else {
            DB::table('usertoken')->where($where)->update($usertoken_info);
        }
        $this->usertoken_info = $usertoken_info;
    }

    //检查是否为常用设备
    protected function check_device_sn($uid, $device_sn)
    {
        $where['uid'] = $uid;
        $where['login_type'] = $this->client_info['type'];
        $old_device = DB::table('usertoken')->where($where)->first();
        if($device_sn ==$old_device->device_sn || !$old_device->device_sn){
            return true;
        }
        $device_info = [];
        $device_info['device_sn'] = $device_sn;
        DB::table('usertoken')->where($where)->update($device_info);
        return false;
    }

    /**
     * 字段中包含
     */
    public function dbinstr($fiekd, $str, $spl1 = ',', $spl2 = ',')
    {
        return "instr(concat('$spl1', $fiekd, '$spl2'), '" . $spl1 . $str . $spl2 . "')>0";
    }

    /**
     *    历史会话标识已读
     */
    public function biaoyd($type, $gid)
    {
        ImchatModel::where('type', $type)
            ->where('aid', $this->useaid)
            ->where('receid', $gid)
            ->update(['stotal' => 0]);
    }

    public function objToArr($object)
    {
        //先编码成json字符串，再解码成数组
        return json_decode(json_encode($object), true);
    }


    public function getclient_info($client_token)
    {
        return $this->objToArr(DB::table('authtoken')->where(['token' => $client_token])->first());
    }

    public function getAgenh($cnum, $num)
    {
        $this->getCompany($cnum);
        $this->agenhinfo	= BaseModel::agenhInfo($this->companyid, $num);
        return $this->agenhinfo;
    }

    /**
     *	获取单位信息,$cnum单位编号
     */
    public function getCompany($cnum)
    {
        $this->getUserId();
        $this->companyinfo	= BaseModel::getCompany($cnum);
        if(!$this->companyinfo)abort(404, 'not found company['.$cnum.']');
        $this->companyid	= $this->companyinfo->id;
        $this->useainfo		= BaseModel::getUsera($this->companyid, $this->userid); //单位用户ID
        if($this->useainfo)$this->useaid = $this->useainfo->id;
    }


    public function jpush_user($aid,$title,$json_msg){

        $jpush = new JPushClient(config('jpush.appKey'), config('jpush.masterSecret'));
        $response = $jpush->push()
            ->setPlatform('ios')
            ->addAlias($aid)
            ->iosNotification($title, [
                'sound' => 'sound',
                'badge' => '+1',
                'extras' => [
                    'json_data' => $json_msg
                ]
            ])
           // ->setNotificationAlert($json_msg)
            ->send();
        return $response;
    }

    /*  测试成功  */
    public function getTest(Request $request)
    {
        Gateway::$registerAddress = '127.0.0.1:1236';

        dd( Gateway::isUidOnline(Request('aid')), Gateway::getAllUidList());
    }

}
