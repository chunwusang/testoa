<?php
/**
 *    api-应用接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use Illuminate\Http\Request;
use DB;

class AgentController extends ApijctController
{
    public function postUserloction(Request $request)
    {
        $this->getCompany(Request('cnum'));
        $data['cid'] = $this->companyid;
        $data['aid'] = $this->useaid;
        $data['lat'] =   Request('lat')?Request('lat'):'';
        $data['lng'] =  Request('lng')?Request('lng'):'';
        $data['accuracy'] =  Request('accuracy')?Request('accuracy'):'';
        $data['imgpath'] =   Request('imgpath')?Request('imgpath'):'';
        $data['explain'] =  Request('explain')?Request('explain'):'';
        $data['mac'] =   Request('mac')?Request('mac'):'';
        $data['ip'] = Request('ip')?Request('ip'):'';
        $data['dkdt'] = nowdt();
        $data['optdt'] = nowdt();
        $data['type'] = 2;
        DB::table('kqdkjl')->insert($data);
        $info['code']=200;
        $info['success']=true;
        return  response()->json($info);
    }


    /**
     *	get方法
     */
    public function getApidata( Request $request)
    {

        $cnum =Request('cnum'); $num=Request('num'); $act=Request('act');
        $this->getAgenh($cnum, $num);
        if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
        $acta	= explode('_', $act);
        $runa	= arrvalue($acta, 1, 'getData');
        $obj 	= c('Agentapi:'.$acta[0].'', $this->useainfo);
        $obj->initFlow($num); //初始化流程
        $barr 	= $obj->$runa($request);
        if(!$barr['success'])return $this->returnerror($barr['msg']);
         //专门解决任务中 会有html 标签使用
        foreach ( $barr['data']['rows'] as  $k=>$v ){
            if(isset($v->status) ){
                $barr['data']['rows'][$k]->status =strip_tags($v->status);
            }
            if( isset($v->statusstr)){
                $barr['data']['rows'][$k]->statusstr =strip_tags($v->statusstr);
            }
        }

        return $barr;
    }

    /**
     *	post方法
     */
    public function postApidata( Request $request)
    {
        $cnum =Request('cnum'); $num=Request('num'); $act=Request('act');
        $this->getAgenh($cnum, $num);
        if($this->useaid==0 || !$this->agenhinfo)return $this->returnerror(trans('validation.notagenh',['num'=>$num]));
        $acta	= explode('_', $act);
        $runa	= 'post'.arrvalue($acta, 1, 'Data');
        $obj 	= c('Agentapi:'.$acta[0].'', $this->useainfo);
        $obj->initFlow($num); //初始化流程
        $barr 	= $obj->$runa($request);
        if(!$barr['success'])return $this->returnerror($barr['msg']);
        return $barr;
    }
}