<?php

/**

*	api 建材通开发 路由 介于源程序升级会覆盖文件所有不能修改原文件，只能另起新文件

*	主页：http://www.rockoa.com/

*	软件：信呼OA云平台

*	作者：雨中磐石(rainrock)

*	时间：2018-06-18

*/

//--------------------以下是app/移动端接-------------------

Route::post('/auth/get_clienttoken', 'Apijct\AuthController@get_clienttoken')->name('auth_get_clienttoken');  //得到链接token //ok http://oa.com/apijct/auth/get_clienttoken?key=mx4NOZi

Route::post('/auth/get_usertoken', 'Apijct\WeController@get_usertoken')->name('auth_get_usertoken');      //得到用户登录后token

Route::get('/auth/get_startpage', 'Apijct\AuthController@get_startpage')->name('auth_get_startpage');      //得到启动页广告

//提交注册登录，找回密码

Route::post('/user/regcheck', 'Apijct\LoginController@regCheck')->name('apijct_regcheck');

Route::post('/user/logincheck', 'Apijct\LoginController@loginCheck')->name('apijct_logincheck');  //http://oa.com/apijct/user/logincheck?user=13380302514&pass=123456z

Route::post('/user/findcheck', 'Apijct\LoginController@findCheck')->name('apijct_findcheck');

Route::post('/user/authSetInfo', 'Apijct\LoginController@authSetInfo')->name('apijct_authSetInfo'); //第三方登录，绑定手机号码和密码

Route::post('/user/setinfo', 'Apijct\LoginController@setinfo');

Route::post('/user/authLogin', 'Apijct\LoginController@authLogin')->name('apijct_findcheck'); //第三方登录

Route::post('/user/loginout', 'Apijct\LoginController@loginOut')->name('apijct_loginout');

//个人设置
Route::post('/user/change_name', 'Apijct\UserController@change_name')->name('user_change_name');//修改名字（詹）

Route::post('/user/change_mobile', 'Apijct\UserController@change_mobile')->name('user_change_mobile');//修改手机（詹）

Route::post('/user/change_face', 'Apijct\UserController@change_face')->name('user_change_face');//修改头像（詹）

Route::post('/user/change_sex', 'Apijct\UserController@change_sex')->name('user_change_sex');//修改性别（詹）

Route::post('/user/change_city', 'Apijct\UserController@change_city')->name('user_change_city');//修改地区（詹）

Route::post('/user/set_email', 'Apijct\UserController@set_email')->name('user_set_email');//绑定邮箱（詹）

Route::post('/user/check_mobile', 'Apijct\UserController@check_mobile')->name('user_check_mobile');//校验手机号码（詹）

Route::post('/user/change_birthday', 'Apijct\UserController@change_birthday')->name('user_change_birthday');//设置生日（詹）



//一些基本路由，如获取验证码，发验证码，发短信等

Route::get('/base/{act}', 'Apijct\BaseController@index')->name('apijctbase');



//官网api ajax跨域请求

//--------------------以下是聊天相关-------------------

Route::post('/chat/user_inputting', 'Apijct\ChatController@user_inputting');

Route::get('/chat/bind', 'Apijct\ChatController@bind'); //http://oa.com/apijct/chat/bind?client_id=7f00000108fc00000004&gid=2&company=n6b4nr

//--------------------以下是app/移动端接口必须act方法，cnum单位编号-------------------

Route::get('/we/{act}/{cnum?}', 'Apijct\WeController@getApidata')->name('apijctwe');

Route::post('/we/{act}/{cnum?}', 'Apijct\WeController@postApidata')->name('pijctwepost');

//--------------------应用接口必须cnum单位编号,num应用编号，act方法----------

//Route::get('/agent/{num}/{act}', 'Apijct\AgentController@getApidata');

//Route::post('/agent/{num}/{act}', 'Apijct\AgentController@postApidata');

//--------------------应用接口必须cnum单位编号,num应用编号，act方法----------

Route::post('/agent/userloction', 'Apijct\AgentController@postUserloction');

// 单独获取聊天消息

//==============================================================================================

Route::get('/alone/index_chatlist', 'Apijct\WeController@index_chatlist');

Route::get('/alone/get_cnumbyaid', 'Apijct\WeController@get_cnumbyaid');

Route::get('/alone/get_agenharrlist', 'Apijct\WeController@index_agenharrlist');

//页面详情

Route::get('/detail', 'Apijct\DetailController@index')->name('jct_detail');

//手机端应用

Route::get('/ying', 'Apijct\YingController@index')->name('jct_ying'); //底部导航菜单

//--------------------应用接口必须cnum单位编号,num应用编号，act方法----------

Route::get('/agent', 'Apijct\AgentController@getApidata')->name('jct_apiagent');

Route::post('/agent', 'Apijct\AgentController@postApidata')->name('jct_apiagentpost');



/** admin 端******************************************************************************************/

//启动页管理

Route::get('/admin/startpage', 'Apijct\admin\StartpageController@index')->name('jctadmin_startpage');

Route::get('/admin/startpageedit/{id?}', 'Apijct\admin\StartpageController@getForm')->name('jctadmin_startpageedit');

Route::post('/admin/startpageedit/{id?}', 'Apijct\admin\StartpageController@post_data')->name('jctadmin_startpageedit_post_data');



//官网管理

Route::get('/admin/official_website_config', 'Apijct\admin\OfficialwebsiteController@config')->name('jctadmin_website_config');

Route::post('/admin/official_website_config', 'Apijct\admin\OfficialwebsiteController@post_config')->name('jctadmin_website_postconfig');

Route::get('/admin/official_website_articlecat', 'Apijct\admin\OfficialwebsiteController@article_cat')->name('jctadmin_website_articlecat');

Route::get('/admin/official_website_articlelist/{id?}', 'Apijct\admin\OfficialwebsiteController@article_list')->name('jctadmin_website_articlelist');

Route::get('/admin/official_website_articlecatedit/{id?}', 'Apijct\admin\OfficialwebsiteController@getForm_banneredit')->name('jctadmin_website_articlecatedit');

Route::get('/admin/official_website_articleedit/{id?}', 'Apijct\admin\OfficialwebsiteController@getForm_article_detail')->name('jctadmin_website_articleedit');

Route::get('/admin/official_website_bannerlist', 'Apijct\admin\OfficialwebsiteController@getForm_bannerlist')->name('jctadmin_website_bannerlist');

Route::get('/admin/official_website_banneredit/{id?}', 'Apijct\admin\OfficialwebsiteController@getForm_banneredit')->name('jctadmin_website_banneredit');

Route::post('/admin/official_website_postbanner', 'Apijct\admin\OfficialwebsiteController@post_banner')->name('jctadmin_website_postbanner');

Route::post('admin/official_website_articledetail/{id?}', 'Apijct\admin\OfficialwebsiteController@post_article_detail')->name('jctadmin_website_articledetail_post_data');

Route::get('admin/official_website_articledelete/{id?}', 'Apijct\admin\OfficialwebsiteController@article_delete')->name('jctadmin_website_articledelete'); //删除



Route::get('official_website/config','Apijct\OfficialwebsiteController@get_config');

Route::get('official_website/article_cat','Apijct\OfficialwebsiteController@get_article_cat');

Route::get('official_website/banner_list','Apijct\OfficialwebsiteController@get_banner_list');

Route::get('official_website/article_list/','Apijct\OfficialwebsiteController@get_article_list');

Route::get('official_website/article_detail','Apijct\OfficialwebsiteController@get_article_detail');





//test连接

Route::get('/test', 'Apijct\ApiController@getTest'); //http://oa.com/apijct/chat/bind?client_id=7f00000108fc00000004&gid=2&company=n6b4nr