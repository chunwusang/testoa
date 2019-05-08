<?php
/**
 *    api接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;
use GatewayClient\Gateway;
use App\Model\Base\BaseModel;
use App\Model\Base\UsersModel;
use App\Model\Base\CompanyModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Rock;
use App\Model\Base\ImmessModel;
use App\Model\Base\ImmessztModel;
use App\Model\Base\UseraModel;
class ApijctController extends ApiController
{


    public function __construct(Request $request)
    {
        //初始化
        //  $usertoken = $request->headers->get('usertoken')?$request->headers->get('usertoken'):$request->get('usertoken','m063ddjnqm');
        //  $request->merge(['usertoken' => $usertoken]);// //测试使用
        //拿usertoken
        if ($request->headers->get('usertoken')) {
            //如果是web 登录的
            $web_usertoken = $request->headers->get('usertoken');
            $this->web_tojctapi($web_usertoken);
            $request->merge(['usertoken' => $web_usertoken]);
        }

        $this->usertoken_info = $this->checkuser_jct($request);
        $this->usertoken = $this->usertoken_info['usertoken'];
        $this->client_token = $this->usertoken_info['client_token'];
        $this->client_info = $this->getclient_info($this->client_token);
        $this->usertoken_info = $this->getusertoken_info($this->usertoken);

        $now_user = $this->getuser_byusertaken($this->usertoken);
        Rock::setApiUser([
            'user.id' => $now_user->id,
            'user.info' => $now_user,
        ]);
        $this->getUserId();

    }


    /**
     *    获取用户ID
     */
    public function getUserId()
    {
        if ($this->userid > 0) return $this->userid;
        $uarr = \Rock::getApiUser();
        $uid = $uarr['user.id'];
        if (isempt($uid)) $uid = 0;
        $this->userid = $uid;
        $this->userinfo = $uarr['user.info'];
        return $uid;
    }

    /**
     *    判断是否可以操作该单位权限
     */
    public function getCompanyInfo()
    {
        $this->getUserId();
        if (!$this->userid) return;
        $this->companyarr = UsersModel::find($this->userid)->joincompany()->get(); //这个获取下我加入单位的人员
    }

    /**
     *    判断是否可以操作该单位权限
     */
    public function getCompanyId($request, $cnum = '')
    {
        $cid = 0;
        if ($cnum == '') $cid = (int)$request->input('cid', '0');
        if ($cid == 0) {
            if ($cnum == '') $cnum = $request->input('cnum');
            if (!isempt($cnum)) {
                $cnumfof = CompanyModel::where('num', $cnum)->first();
                if ($cnumfof) $cid = $cnumfof->id;
            }
        }
        if ($cid == 0) return $cid;
        if (!$this->companyarr) $this->getCompanyInfo();
        if (!$this->companyarr) return 0;
        $bo = false;
        foreach ($this->companyarr as $k => $rs) {
            if ($rs->company->id == $cid) {
                $this->companyinfo = $rs->company;
                $bo = true;
                break;
            }
        }
        if (!$bo) return 0;
        $this->useainfo = BaseModel::getUsera($cid, $this->userid); //单位用户ID
        if ($this->useainfo) $this->useaid = $this->useainfo->id;
        $this->companyid = $cid;
        return $cid;
    }


    public function getuser_byusertaken($token)
    {

        $uinfo = DB::table('usertoken')->where('usertoken', $token)->where('dead_time', '>=', time())->first();
        // $this->uinfo = $uinfo;

        if ($uinfo) {
            return UsersModel::find($uinfo->uid);
        } else {
            return false;
        }
    }





    public function getusertoken_info($usertoken)
    {
        return $this->objToArr(DB::table('usertoken')->where(['usertoken' => $usertoken])->first());
    }


    public function web_tojctapi($web_usertoken)
    {
        //如果是web登录header携带的token 更新吧
        $token_info = DB::table('token')->where('token', $web_usertoken)->first();
        if ($token_info) {
            $this->client_token = 'BeNo7vHQznpsbSM5YuCS';  //固定webclient_token
            $this->client_info = $this->getclient_info($this->client_token);
            $this->update_usertoken($token_info->uid, $web_usertoken);
            return $web_usertoken;
        }
        return '';
    }

    //检查用户合法性
    public function checkuser_jct(Request $request)
    {

        $input_data = $request->all();
        if (empty($input_data['usertoken'])) {
            $info['code'] = 800;
            $info['msg'] = '请求必须携带usertoken参数';
            die(json_encode($info));
        }
        $tokeninfo = DB::table('usertoken')->where('usertoken', $input_data['usertoken'])->where('dead_time', '>=', time())->first();
        if (empty($tokeninfo)) {
            $info['code'] = 801;
            $info['msg'] = 'usertoken失效';
            die(json_encode($info));
        }
        return $this->objToArr($tokeninfo);
    }

    public function is_two_chatroom($uid1, $uid2)
    {
        $gid_info = DB::table('chatclientinfo')->where(['uid' => $uid1])->orwhere(['uid' => $uid2])->get();
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

       public function receiver_info($aid){
           $receiver_info = DB::table('usera')->leftjoin('usertoken','usera.uid','=','usertoken.uid')->select('usera.id as aid','usera.uid','usertoken.login_type')->where(['usera.id' => $aid])->orwhere('usertoken.dead_time','<',time())->get();
           $receiver_info =$this->objToArr($receiver_info);
           $login_type=array();
           foreach ($receiver_info as $v){
               $login_type[] =$v['login_type'];
           }
           $info['login_type'] = $login_type;
           $info['data']=$receiver_info;
           $info['uid']=$receiver_info[0]['uid'];
           $info['aid']=$aid;
           return $info;
       }


    /**
     *    读取消息记录
     */
    public function getrecord($type, $gid, $minid = 0, $lastdt = '', $clbo = 0)
    {
        $type = $this->gettypeid($type);
        $obj = ImmessModel::where('cid', $this->companyid);
        $obj->where('type', $type);
        if ($type == 0) {
            $obj->whereRaw('((`aid`=' . $this->useaid . ' and `receid`=' . $gid . ') or (`receid`=' . $this->useaid . ' and `aid`=' . $gid . '))');
        } else {
            $obj->where('receid', $gid);
        }

        if ($lastdt != '') {
            $lastdt = nowdt('', $lastdt - 2);
            $obj->where('optdt', '>', $lastdt);

        } elseif ($minid > 0) {
            $obj->where('id', '<', $minid);
        }

        $obj->whereRaw($this->dbinstr('receids', $this->useaid));

        if ($clbo == 1) return $obj->get();
        if ($clbo == 2) return $obj->count();

        $obj->orderBy('id', 'desc');

        //DB::connection()->enableQueryLog();

        $data = $obj->simplePaginate($this->limit)->getCollection();

        $mids = $bdata = array();

        foreach ($data as $k => $rs) {
            $ars = UseraModel::find($rs->aid);
            $sendname = '';
            $face = '/images/noface.png';
            $das = new \StdClass();
            $das->cont = $rs->cont;
            $das->fileid = $rs->fileid;
            $das->id = $rs->id;
            if ($rs->fileid > 0) {
                $filers = FileModel::find($rs->fileid);
                $das->filers = $filers;
            }
            $das->optdt = $rs->optdt;

            if ($ars) {
                $sendname = $ars->name;
                $face = $ars->face;
            }
            $das->sendid = $rs->aid;
            $das->sendname = $sendname;
            $das->face = $face;

            $mids[] = $rs->id;

            $bdata[] = $das;
        }

        //把这几个消息标识已读
        if ($mids) ImmessztModel::where('aid', $this->useaid)->whereIn('mid', $mids)->delete();

        $this->biaoyd($type, $gid);


        return array(
            'rows' => $bdata,
            'lastdt' => $lastdt
        );
    }


    public function getrecord_info($send_aid, $receiver_aid,$lastdt,$loadci)
    {

        $type = 'user';
        $gid = (int)$receiver_aid;
        $minid = 0;
        $loadci = (int)($loadci);//loadci 为0 是首次加载
        $lastdt = nulltoempty($lastdt);

        $sendinfo = new \StdClass();
        $sendinfo->id = $send_aid;
        $sendinfo->name = $this->useainfo->name;
        $sendinfo->face = $this->useainfo->face;

        $data = $this->getrecord($type, $gid, $minid, $lastdt);
        $barr = [
          //  'sendinfo' => $sendinfo,
            'rows' => $data['rows'],
          //  'type' => $type,
          //  'nowdt' => time(),
          //  'loadci' => $loadci,
         //   'baseurl' => config('rock.baseurl'),
        //    'wdtotal' => 0 //未读消息
        ];


      //  foreach ($data as $k => $v) $barr[$k] = $v;

        return $barr;
    }

    public function gettypeid($type)
    {
        $barr['user'] = 0;
        $barr['group'] = 1;
        $barr['agenh'] = 2;
        return $barr[$type];
    }
}
