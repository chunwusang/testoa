<?php
/**
 *    api接口
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Rock;
header("Access-Control-Allow-Origin:*");
header('Access-Control-Allow-Methods:POST');
header('Access-Control-Allow-Methods:GET');
header('Access-Control-Allow-Headers:x-requested-with, content-type');
//因为不需要登录状态，不用验证继承Controller
class officialwebsiteController extends ApiController
{


    /**
     *    验证api中间件
     */
    public function __construct()
    {

        //parent::__construct();
        //$this->middleware('apiauth');
    }


    //换取token
    public function get_config(Request $request)
    {
        $data = DB::table('officialwebsite_article_detail')->select('text')->find(1);
        $data = json_decode($data->text);
        $data->appCode_url =env('APP_URL').Rock::replaceurl($data->appCode_url);
        $info['code'] = 200;
        $info['success'] = true;
        $info['data'] = $data;
        return $info;
    }

    public function get_article_cat(Request $request)
    {
        $data = $this->objToArr(DB::table('officialwebsite_article_cat')->where('id', '>', 1)->get());
        $info['code'] = 200;
        $info['success'] = true;
        $info['data'] = $data;
        return $info;
    }


    public function get_article_list( Request $request)
    {
        $where['cat_id'] = Request('cat_id');
        $obj = DB::table('officialwebsite_article_detail')->leftjoin('officialwebsite_article_cat', 'officialwebsite_article_detail.cat_id', '=', 'officialwebsite_article_cat.id')->select('officialwebsite_article_detail.*', 'officialwebsite_article_cat.cat_name')->where($where)->get();
        $data = $this->objToArr($obj);
        foreach ($data as $k=>$v){
            $data[$k]['img']=env('APP_URL').$v['img'];
        }
        $info['code'] = 200;
        $info['success'] = true;
        $info['data'] = $data;
        return $info;
    }

    public function get_banner_list(Request $request)
    {
        $where['cat_id'] = 0;
        $obj = DB::table('officialwebsite_article_detail')->where($where)->get();
        $data = $this->objToArr($obj);
        foreach ($data as $k=>$v){
            $data[$k]['img']=env('APP_URL').$v['img'];
    }
        $info['code'] = 200;
        $info['success'] = true;
        $info['data'] = $data;
        return $info;
    }

    public function get_article_detail(Request $request)
    {
        $data='';
        $where['officialwebsite_article_detail.id'] = Request('id');
        $obj = DB::table('officialwebsite_article_detail')->leftjoin('officialwebsite_article_cat', 'officialwebsite_article_detail.cat_id', '=', 'officialwebsite_article_cat.id')->select('officialwebsite_article_detail.*', 'officialwebsite_article_cat.cat_name')->where($where)->get();
        if(isset($obj[0])){
            $data = $this->objToArr($obj[0]);
            $data['img']=env('APP_URL').$data['img'];
        }
        $info['code'] = 200;
        $info['success'] = true;
        $info['data'] = $data;
        return $info;
    }

}
