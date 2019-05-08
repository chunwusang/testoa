<?php
/**
 *    官网管理
 */

namespace App\Http\Controllers\Apijct\admin;

use App\Http\Controllers\Admin\AdminController;
use App\Model\Base\UsersModel;
use Illuminate\Http\Request;
use DB;


class OfficialwebsiteController extends AdminController
{

    /**
     *    全局配置
     */
    public function config(Request $request)
    {
        $data = DB::table('officialwebsite_article_detail')->select('text')->find(1);
        $data = json_decode($data->text);

        if (!$data) {
            $data = new \StdClass();
            $data->email = '';
            $data->appCode_url = '';//二维码
            $data->workTime = '';//工作时间
            $data->cerocd = '';//备案号
            $data->url = '';//域名
            $data->shop_url = '';//商城域名
            $data->oay_url = '';//oay域名
            $data->project_num = '';
            $data->influence_num= '';
            $data->winning_num= '';
            $data->distribution_num= '';
        }

        if(empty($data->project_num)===true){$data->project_num= '';}
        if(empty($data->influence_num)===true){$data->influence_num= '';}
        if(empty($data->winning_num)===true){$data->winning_num= '';}
        if(empty($data->distribution_num)===true){$data->distribution_num= '';}
        return $this->getShowView('admin/officialwebsite/configedit', [
            'pagetitle' => '官网全局配置',
            'data' => $data,
            'lang' => 'table/users',
            'kzq' => 'Admin/Users'
        ]);
    }


    public function article_cat(Request $request)
    {
        $this->getLimit();

        $obj = DB::table('officialwebsite_article_cat')->where('id','>', 1)->get();
        $total = $obj->count();
        //  $data 	= $obj->simplePaginate($this->limit)->getCollection();
        return $this->getShowView('admin/officialwebsite/article_catlist', [
            'pagetitle' => '官网栏目管理',
            'data' => $obj,
            'pager' => $this->getPager('adminusers', $total),
            'mtable' => c('rockjm')->encrypt('users'),
        ]);
    }


    public function article_list($id, Request $request)
    {
        $this->getLimit();
        $where['cat_id'] = $id;
        $obj = DB::table('officialwebsite_article_detail')->leftjoin('officialwebsite_article_cat', 'officialwebsite_article_detail.cat_id', '=', 'officialwebsite_article_cat.id')->select( 'officialwebsite_article_detail.*', 'officialwebsite_article_cat.cat_name')->where($where)->get();
        $total = $obj->count();

        //  $data 	= $obj->simplePaginate($this->limit)->getCollection();
        return $this->getShowView('admin/officialwebsite/articlelist', [
            'pagetitle' => '栏目列表',
            'data' => $obj,
            'pager' => $this->getPager('adminusers', $total),
            'mtable' => c('rockjm')->encrypt('users'),
        ]);
    }






    public function getForm_article_detail($id, Request $request)
    {

        $where['officialwebsite_article_detail.id'] = $id;
        $data= null;
         $data_c = DB::table('officialwebsite_article_detail')->leftjoin('officialwebsite_article_cat', 'officialwebsite_article_detail.cat_id', '=', 'officialwebsite_article_cat.id')->select('officialwebsite_article_detail.*', 'officialwebsite_article_cat.cat_name')->where($where)->get();
        if(count( $data_c)>0){$data= $data_c[0];}

        if (!$data) {
            $data = new \StdClass();
            $data->id = 0;
            $data->img = '';
            $data->title = '';
            $data->cat_id = '';
            $data->text = '';
            $data->cat_name = '';
            $data->introduction = '';
        }
        $data_cat=  $obj = DB::table('officialwebsite_article_cat')->where('id','>', 1)->get();
        if (isempt($data->img)) $data->img = '/images/noface.png';
        return $this->getShowView('admin/officialwebsite/articleedit', [
            'pagetitle' => ($id == 0) ? '新增文章' : '编辑文章',
            'data' => $data,
            'data_cat'=>$data_cat
        ]);
    }

    public function getForm_bannerlist(Request $request)
    {
        $where['cat_id'] = 0;
        $data = DB::table('officialwebsite_article_detail')->where($where)->get();
        return $this->getShowView('admin/officialwebsite/bannerlist', [
            'pagetitle' => '官网banner管理',
            'data' => $data,
            'mtable' => c('rockjm')->encrypt('users'),
            'lang' => 'table/users',
            'kzq' => 'Admin/Users'
        ]);
    }


    public function getForm_banneredit($id, Request $request)
    {
        $data = DB::table('officialwebsite_article_detail')->find($id);
        if (!$data) {
            $data = new \StdClass();
            $data->id = 0;
            $data->img = '';
        }
        if (isempt($data->img)) $data->img = '/images/noface.png';
        return $this->getShowView('admin/officialwebsite/banneredit', [
            'pagetitle' => ($id == 0) ? '新增banner' : '编辑banner',
            'data' => $data
        ]);
    }

    public function post_article_detail(Request $request)
    {
        $id = Request('id');
        $info['cat_id'] = Request('cat_id');
        $info['img'] = Request('img');
        $info['title'] = Request('title');
        $info['text'] = Request('content');
        $info['introduction'] = Request('introduction');
        $where['id'] = $id;
        if (empty($id) || $id == 0) {
            //insert
            $info['add_time'] = time();
            DB::table('officialwebsite_article_detail')->insert($info);
        } else {
            //update
            DB::table('officialwebsite_article_detail')->where($where)->update($info);
        }

        $data['code'] = 200;
        $data['success'] = true;
        return $data;
    }


    public function post_config(Request $request)
    {
        $info['email'] = Request('email');
        $info['appCode_url'] = Request('appCode_url');
        $info['workTime'] = Request('workTime');
        $info['cerocd'] = Request('cerocd');
        $info['url'] = Request('url');
        $info['shop_url'] = Request('shop_url');
        $info['oay_url'] = Request('oay_url');
        $info['project_num'] = Request('project_num');
        $info['influence_num'] =Request('influence_num');
        $info['winning_num']= Request('winning_num');
        $info['distribution_num']=Request('distribution_num');
        $where['id'] = 1;
        $data['text'] = json_encode($info);
        DB::table('officialwebsite_article_detail')->where($where)->update($data);
        $data['code'] = 200;
        $data['success'] = true;
        return $data;
    }

    public function post_banner(Request $request)
    {
        $info['img'] =  Request('img');
        $id = Request('id');
        $where['id'] = $id;
        $where['cat_id'] = 0;
        if ($id > 0) {
            $info['title'] = '这是banner';
            DB::table('officialwebsite_article_detail')->where($where)->update($info);
        } else {
            $info['cat_id'] = 0;
            $info['title'] = '这是banner';
            $info['img'] =  Request('img');
            DB::table('officialwebsite_article_detail')->insert($info);
        }
        $data['code'] = 200;
        $data['success'] = true;
        return $data;
    }

    public function article_delete($id, Request $request)
    {
        $where['id'] = $id;
        DB::table('officialwebsite_article_detail')->delete($where);
        $data['code'] = 200;
        $data['success'] = true;
        return $data;
    }


}
