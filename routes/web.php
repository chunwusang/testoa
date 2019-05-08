<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Users\HomeController@index')->name('usersindex');


//一些基本路由，如获取验证码，发验证码，发短信等
Route::get('/base/{act}', 'Base\BaseController@index')->name('base');

//手机端应用
Route::get('/ying/{cnum}/{num}', 'Web\YingController@index')->name('ying');

//录入
Route::get('/input/{cnum}/{num}/{mid?}', 'Web\InputController@index')->name('input');
//导入页面
Route::get('/daoru/{cnum}/{num}', 'Web\InputController@indexDaoru')->name('daoru');
//下载导入Excel模版
Route::get('/daorudown/{cnum}/{num}', 'Web\InputController@indexDaorudown')->name('daorudown');

//页面详情`
Route::get('/detail/{cnum}/{num}/{mid}', 'Web\DetailController@index')->name('detail');

//PC端应用显示
Route::get('/list/{cnum}/{num}', 'Web\ListController@index')->name('list');
Route::get('/listdata/{cnum}/{num}', 'Web\ListController@listdata')->name('listdata');

//企业微信回调用


/**
*	平台用户路由
*/
Route::group([
	'prefix' => 'users',
	'as' 	=> 'users',
], function () {
	
	//test路由
	// Route::get('/home/{cnum}/{type?}', 'Users\HomeController@home');



	Route::get('/', 'Users\HomeController@index')->name('users');
	Route::get('/login', 'Users\LoginController@showLoginForm')->name('login');
	Route::get('/reg', 'Users\LoginController@showRegForm')->name('reg');
//    Route::get('/reg', 'Users\LoginController@house')->name('reg');


	
	Route::get('/find', 'Users\LoginController@showFindForm')->name('find');
	Route::get('/loginout', 'Users\LoginController@loginout')->name('loginout');
	
	/************************测试的路由**************************/
	//test
	Route::get('/test/{type?}', 'Users\HomeController@test');
	Route::get('/category/{type?}', 'Users\CommonController@category');

	Route::get('/common/{tpl}/{type?}', 'Users\CommonController@common')->name('common');
	Route::get('/showCogForm/{tpl}/{type?}', 'Users\CogController@showCogForm')->name('showCogForm');


	/************************测试的路由**************************/

	Route::get('/index/{cnum?}', 'Users\HomeController@index')->name('indexs');
	Route::get('/home/{cnum}/{type?}', 'Users\HomeController@home')->name('home');

	
	Route::get('/manage', 'Users\ManageController@index')->name('manage');
	Route::get('/active/{id}', 'Users\ManageController@activeForm')->name('active');

	Route::get('/cog', 'Users\CogController@showCogForm')->name('cog');
	Route::get('/agent', 'Users\AgentController@index')->name('agent');
	Route::get('/companyadd', 'Users\CompanyController@showCreateForm')->name('companyadd');
	
});


/**
 * 后台管理路由
 */
Route::group([
	'prefix' => 'admin',
	'as'	 => 'admin'
], function () {
	



	Route::get('/login', 'Admin\LoginController@showLoginForm')->name('login');//登录页面
	Route::post('/login', 'Admin\LoginController@login')->name('logincheck');//登录检查
	Route::get('/loginout', 'Admin\LoginController@loginout')->name('loginout');//退出登录
	

	Route::get('/', 'Admin\HomeController@index')->name('home'); //主页

	Route::get('/company', 'Admin\CompanyController@index')->name('company');//公司列表
	Route::get('/companyedit/{id?}', 'Admin\CompanyController@getForm')->name('companyedit');//公司编辑页
	
	Route::get('/usera', 'Admin\UseraController@index')->name('usera');
	Route::get('/users', 'Admin\UsersController@index')->name('users');
	
	Route::get('/usersedit/{id?}', 'Admin\UsersController@getForm')->name('usersedit');
	Route::get('/dept', 'Admin\DeptController@index')->name('dept');//部门列表
	
	
	//应用管理
	Route::get('/agent', 'Admin\AgentController@index')->name('agent');
	Route::get('/agentedit/{id?}', 'Admin\AgentController@getForm')->name('agentedit');
	Route::get('/agentfields/{agentid}', 'Admin\AgentController@fieldsList')->name('agentfields');
	Route::get('/agentfieldsedit/{agentid}', 'Admin\AgentController@getFieldsForm')->name('agentfieldsedit');
	Route::get('/agentbuju/{agentid}', 'Admin\AgentController@getBujuForm')->name('agentbuju');
	
	//应用列表菜单
	Route::get('/agentmenu/{agentid}', 'Admin\AgentController@menuList')->name('agentmenu');
	Route::get('/agentmenuedit/{agentid}', 'Admin\AgentController@getMenuForm')->name('agentmenuedit');
	
	Route::get('/agentoptm/{agentid}', 'Admin\AgentController@optmenuList')->name('agentoptm');
	Route::get('/agentoptmedit/{agentid}', 'Admin\AgentController@getoptMenuForm')->name('agentoptmedit');
	
	//安装第三方应用
	//基本没什么卵用的
	Route::get('/anstall', 'Admin\AnstallController@getaList')->name('anstall');
	
	Route::get('/agenttodo/{agentid}', 'Admin\AgentController@todoList')->name('agenttodo');
	Route::get('/agenttodoedit/{agentid}', 'Admin\AgentController@getTodoForm')->name('agenttodoedit');
	

	//平台管理
	Route::get('/manage/{act}', 'Admin\ManageController@index')->name('manage');
});



/**
*	单位管理后台
*/
Route::get('/manage/{cnum}/{act?}', 'Manage\HomeController@getForm')->name('manage');