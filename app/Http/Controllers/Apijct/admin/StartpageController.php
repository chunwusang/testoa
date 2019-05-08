<?php
/**
 *    启动页管理
 *    主页：http://www.rockoa.com/
 *    软件：OA云平台
 *    作者：雨中磐石(rainrock)
 *    时间：2017-12-05
 */

namespace App\Http\Controllers\Apijct\admin;

use App\Http\Controllers\Admin\AdminController;
use App\Model\Base\UsersModel;
use Illuminate\Http\Request;
use DB;


class StartpageController extends AdminController
{

    /**
     *    列表
     */
    public function index(Request $request)
    {

        $data = DB::table('startpage')->get();


        return $this->getShowView('admin/startpage', [
            'pagetitle' => 'app启动广告管理',
            'data' => $data,
            'mtable' => c('rockjm')->encrypt('users'),
            'lang' => 'table/users',
            'kzq' => 'Admin/Users'
        ]);
    }

    /**
     *    用户编辑新增
     */
    public function getForm($id, Request $request)
    {
        $data = DB::table('startpage')->find($id);
        if (!$data) {
            $data = new \StdClass();
            $data->id = 0;
            $data->page_img = '';
            $data->enddt = '';
            $data->startdt = '';

        }
        if (isempt($data->page_img)) $data->page_img = '/images/noface.png';
        $ebts = ($data->id == 0) ? 'addtext' : 'edittext';

        return $this->getShowView('admin/startpageedit', [
            'pagetitle' => ($id == 0) ? '新增启动页' : '编辑启动页',
            'data' => $data
        ]);
    }

    public function post_data(Request $request)
    {
        $id = Request('id');
        $info['page_img'] = Request('page_img');
        $info['enddt'] = Request('enddt');
        $info['startdt'] = Request('startdt');
        if(empty( $info['page_img']) || empty( $info['enddt']) || empty( $info['startdt']) ){
            $data['code']=422;
            $data['errors']['name'][]='参数不全';
            $data['success']=false;
            return $data;
        }

        $where['id'] = $id;
        if (empty($id) || $id == 0) {
            //insert
            DB::table('startpage')->insert($info);
        } else {
            //update
            DB::table('startpage')->where($where)->update($info);
        }

        $data['code']=200;
        $data['success']=true;
        return $data;
    }


}
