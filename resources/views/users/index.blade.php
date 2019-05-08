<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <!-- 加载样式及META信息 -->
        <meta charset="utf-8">
<title>主页</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/images/favicon.ico" />
<!-- Loading Bootstrap -->
<link href="/css/backend.min.css?v=1.0.1" rel="stylesheet">

<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="./assets/js/html5shiv.js"></script>
  <script src="./assets/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  {
            "site":
                  {
                    "name":"FastAdmin",
                    "cdnurl":"",
                    "version":"1.0.1",
                    "timezone":"Asia\/Shanghai",
                    "languages":{"backend":"zh-cn","frontend":"zh-cn"}},
                    "upload":
                            {
                                "cdnurl":"",
                                "uploadurl":"ajax\/upload",
                                "bucket":"local",
                                "maxsize":"10mb",
                                "mimetype":"jpg,png,jpeg,gif",
                                "multipart":[],
                                "multiple":false
                            },
                    "modulename":"admin",
                    "controllername":"index",
                    "actionname":"index",
                    "jsname":"backend\/index",
                    "moduleurl":"\/admin",
                    "language":"zh-cn",
                    "fastadmin":
                            {
                                "usercenter":true,
                                "login_captcha":false,
                                "login_failure_retry":true,
                                "login_unique":false,
                                "login_background":".\/assets\/img\/loginbg.jpg",
                                "multiplenav":false,
                                "checkupdate":false,
                                "version":"1.0.0.20181210_beta",
                                "api_url":"https:\/\/api.fastadmin.net"
                            },
                    "referer":null,
                    "__PUBLIC__":"\/",
                    "__ROOT__":"\/",
                    "__CDN__":"http:\/\/test.api.com"
                }
            };
</script>
    </head>
    <body class="hold-transition skin-green sidebar-mini fixed " id="tabs">
        <div class="wrapper">

            <!-- 头部区域 -->
            <header id="header" class="main-header">
                <!-- Logo -->
<a href="javascript:;" class="logo">
    <!-- 迷你模式下Logo的大小为50X50 -->
    <span class="logo-mini">FAST</span>
    <!-- 普通模式下Logo -->
    <span class="logo-lg"><b>Fast</b>Admin</span>
</a>

<!-- 顶部通栏样式 -->
<nav class="navbar navbar-static-top">

    <!--第一级菜单-->
    <div id="firstnav">
        <!-- 边栏切换按钮-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <!--如果不想在顶部显示角标,则给ul加上disable-top-badge类即可-->
        <ul class="nav nav-tabs nav-addtabs disable-top-badge hidden-xs" role="tablist">
            <li role="presentation" id="tab_1" class="active"><a href="#con_1" node-id="1" aria-controls="1" role="tab" data-toggle="tab"><i class="fa fa-dashboard fa-fw fa-fw"></i> <span>控制台</span> </a></li>        
        </ul>

        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <li>
                    <a href="/" target="_blank"><i class="fa fa-home" style="font-size:14px;"></i></a>
                </li>

                <li class="dropdown notifications-menu hidden-xs">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-bell-o"></i>
                        <span class="label label-warning"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="header">最新消息</li>
                        <li>
                            <!-- FastAdmin最新更新信息,你可以替换成你自己站点的信息,请注意修改public/assets/js/backend/index.js文件 -->
                            <ul class="menu">

                            </ul>
                        </li>
                        <li class="footer"><a href="#" target="_blank">查看更多</a></li>
                    </ul>
                </li>

                <!-- 账号信息下拉框 -->
                <li class="hidden-xs">
                    <a href="javascript:;" data-toggle="checkupdate" title="检测更新">
                        <i class="fa fa-refresh"></i>
                    </a>
                </li>

                <!-- 清除缓存 -->
                <li>
                    <a href="javascript:;" data-toggle="dropdown" title="清空缓存">
                        <i class="fa fa-trash"></i>
                    </a>
                    <ul class="dropdown-menu wipecache">
                        <li><a href="javascript:;" data-type="all"><i class="fa fa-trash"></i> 一键清除缓存</a></li>
                        <li class="divider"></li>
                        <li><a href="javascript:;" data-type="content"><i class="fa fa-file-text"></i> 清空内容缓存</a></li>
                        <li><a href="javascript:;" data-type="template"><i class="fa fa-file-image-o"></i> 清除模板缓存</a></li>
                        <li><a href="javascript:;" data-type="addons"><i class="fa fa-rocket"></i> 清除插件缓存</a></li>
                    </ul>
                </li>

                <!-- 多语言列表 -->
                                <li class="hidden-xs">
                    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-language"></i></a>
                    <ul class="dropdown-menu">
                        <li class="active">
                            <a href="?ref=addtabs&lang=zh-cn">简体中文</a>
                        </li>
                        <li class="">
                            <a href="?ref=addtabs&lang=en">English</a>
                        </li>
                    </ul>
                </li>
                
                <!-- 全屏按钮 -->
                <li class="hidden-xs">
                    <a href="#" data-toggle="fullscreen"><i class="fa fa-arrows-alt"></i></a>
                </li>

                <!-- 账号信息下拉框 -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="/images/avatar.png" class="user-image" alt="Admin">
                        <span class="hidden-xs">Admin</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="/images/avatar.png" class="img-circle" alt="">

                            <p>
                                Admin                                <small>2019-04-24 13:38:47</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-4 text-center">
                                    <a href="https://www.fastadmin.net" target="_blank">FastAdmin</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="https://forum.fastadmin.net" target="_blank">交流社区</a>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="https://doc.fastadmin.net" target="_blank">官方文档</a>
                                </div>
                            </div>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="general/profile" class="btn btn-primary addtabsit"><i class="fa fa-user"></i>
                                    个人配置</a>
                            </div>
                            <div class="pull-right">
                                <a href="/admin/index/logout" class="btn btn-danger"><i class="fa fa-sign-out"></i>
                                    注销</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- 控制栏切换按钮 -->
                <li class="hidden-xs">
                    <a href="javascript:;" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </div>

    </nav>
            </header>

            <!-- 左侧菜单栏 -->
            <aside class="main-sidebar">
                <!-- 左侧菜单栏 -->
<section class="sidebar">
    <!-- 管理员信息 -->
    <div class="user-panel hidden-xs">
        <div class="pull-left image">
            <a href="general/profile" class="addtabsit"><img src="/images/avatar.png" class="img-circle" /></a>
        </div>
        <div class="pull-left info">
            <p>Admin</p>
            <i class="fa fa-circle text-success"></i> 在线 
        </div>
    </div>

    <!-- 菜单搜索 -->
    <form action="" method="get" class="sidebar-form" onsubmit="return false;">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="搜索菜单">
            <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
            </span>
            <div class="menuresult list-group sidebar-form hide">
            </div>
        </div>
    </form>

    <!-- 移动端一级菜单 -->
    <div class="mobilenav visible-xs">

    </div>

    <!--如果想始终显示子菜单,则给ul加上show-submenu类即可,当multiplenav开启的情况下默认为展开-->
    <ul class="sidebar-menu ">

        <!-- 菜单可以在 后台管理->权限管理->菜单规则 中进行增删改排序 -->
        <li class=" active"><a href="/users/common/auth?type=view" addtabs="1" url="/admin/dashboard" py="kzt" pinyin="kongzhitai"><i class="fa fa-dashboard fa-fw"></i> <span>控制台</span> <span class="pull-right-container"> <small class="label pull-right bg-blue">hot</small></span></a> </li>
        <li class=" treeview"><a href="javascript:;" addtabs="2" url="javascript:;" py="cggl" pinyin="changguiguanli"><i class="fa fa-cogs fa-fw"></i> <span>常规管理</span> <span class="pull-right-container"> <small class="label pull-right bg-purple">new</small></span></a> <ul class="treeview-menu"><li class=""><a href="/admin/general/config?ref=addtabs" addtabs="6" url="/admin/general/config" py="xtpz" pinyin="xitongpeizhi"><i class="fa fa-cog fa-fw"></i> <span>系统配置</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/general/attachment?ref=addtabs" addtabs="7" url="/admin/general/attachment" py="fjgl" pinyin="fujianguanli"><i class="fa fa-file-image-o fa-fw"></i> <span>附件管理</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/general/profile?ref=addtabs" addtabs="8" url="/admin/general/profile" py="grpz" pinyin="gerenpeizhi"><i class="fa fa-user fa-fw"></i> <span>个人配置</span> <span class="pull-right-container"> </span></a> </li></ul></li>
        <li class=""><a href="/users/showCogForm/category?type=view" addtabs="3" url="/admin/category" py="flgl" pinyin="fenleiguanli"><i class="fa fa-list fa-fw"></i> <span>分类管理</span> <span class="pull-right-container"> </span></a> </li>
        <li class=" treeview"><a href="javascript:;" addtabs="5" url="javascript:;" py="qxgl" pinyin="quanxianguanli"><i class="fa fa-group fa-fw"></i> <span>权限管理</span> <span class="pull-right-container"><i class="fa fa-angle-left"></i> </span></a> <ul class="treeview-menu"><li class=""><a href="/admin/auth/admin?ref=addtabs" addtabs="9" url="/admin/auth/admin" py="glygl" pinyin="guanliyuanguanli"><i class="fa fa-user fa-fw"></i> <span>管理员管理</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/auth/adminlog?ref=addtabs" addtabs="10" url="/admin/auth/adminlog" py="glyrz" pinyin="guanliyuanrizhi"><i class="fa fa-list-alt fa-fw"></i> <span>管理员日志</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/auth/group?ref=addtabs" addtabs="11" url="/admin/auth/group" py="jsz" pinyin="juesezu"><i class="fa fa-group fa-fw"></i> <span>角色组</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/auth/rule?ref=addtabs" addtabs="12" url="/admin/auth/rule" py="cdgz" pinyin="caidanguize"><i class="fa fa-bars fa-fw"></i> <span>菜单规则</span> <span class="pull-right-container"> <small class="label pull-right bg-purple">菜单</small></span></a> </li></ul></li>
        <li class=""><a href="/users/test?type=data" addtabs="4" url="/admin/addon" py="cjgl" pinyin="chajianguanli"><i class="fa fa-rocket fa-fw"></i> <span>插件管理</span> <span class="pull-right-container"> <small class="badge pull-right bg-red">new</small></span></a> </li>
        <li class=" treeview"><a href="javascript:;" addtabs="66" url="javascript:;" py="hygl" pinyin="huiyuanguanli"><i class="fa fa-list fa-fw"></i> <span>会员管理</span> <span class="pull-right-container"><i class="fa fa-angle-left"></i> </span></a> <ul class="treeview-menu"><li class=""><a href="/admin/user/user?ref=addtabs" addtabs="67" url="/admin/user/user" py="hygl" pinyin="huiyuanguanli"><i class="fa fa-user fa-fw"></i> <span>会员管理</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/user/group?ref=addtabs" addtabs="73" url="/admin/user/group" py="hyfz" pinyin="huiyuanfenzu"><i class="fa fa-users fa-fw"></i> <span>会员分组</span> <span class="pull-right-container"> </span></a> </li><li class=""><a href="/admin/user/rule?ref=addtabs" addtabs="79" url="/admin/user/rule" py="hygz" pinyin="huiyuanguize"><i class="fa fa-circle-o fa-fw"></i> <span>会员规则</span> <span class="pull-right-container"> </span></a> </li></ul></li>
        <!--以下4行可以删除或改成自己的链接,但建议你在你的网站上添加一个FastAdmin的链接-->
        <li class="header" data-rel="external">相关链接</li>
        <li data-rel="external"><a href="https://doc.fastadmin.net" target="_blank"><i class="fa fa-list text-red"></i> <span>官方文档</span></a></li>
        <li data-rel="external"><a href="https://forum.fastadmin.net" target="_blank"><i class="fa fa-comment text-yellow"></i> <span>交流社区</span></a></li>
        <li data-rel="external"><a href="https://jq.qq.com/?_wv=1027&k=487PNBb" target="_blank"><i class="fa fa-qq text-aqua"></i> <span>QQ交流群</span></a></li>
    </ul>
</section>
            </aside>

            <!-- 主体内容区域 -->
            <div class="content-wrapper tab-content tab-addtabs">
                <div role="tabpanel" class="tab-pane active" id="con_1">
                    <iframe src="/admin/dashboard/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_3">
                    <iframe src="/admin/category/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_6">
                    <iframe src="/admin/general/config/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_7">
                    <iframe src="/admin/general/attachment/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_8">
                    <iframe src="/admin/general/profile/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_9">
                    <iframe src="/admin/auth/admin/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_10">
                    <iframe src="/admin/auth/adminlog/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_11">
                    <iframe src="/admin/auth/group/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_12">
                    <iframe src="/admin/auth/rule/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_4">
                    <iframe src="/admin/addon/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_67">
                    <iframe src="/admin/user/user/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_73">
                    <iframe src="/admin/user/group/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
                <div role="tabpanel" class="tab-pane active" id="con_79">
                    <iframe src="/admin/user/rule/index.html" width="100%" height="100%" frameborder="no" border="0" marginwidth="0" marginheight="0" scrolling-x="no" scrolling-y="auto" allowtransparency="yes"></iframe>
                </div>
            </div>

            <!-- 底部链接,默认隐藏 -->
            <footer class="main-footer hide">
                <div class="pull-right hidden-xs">
                </div>
                <strong>Copyright &copy; 2017-2018 <a href="https://www.fastadmin.net">Fastadmin</a>.</strong> All rights reserved.
            </footer>

            <!-- 右侧控制栏 -->
            <div class="control-sidebar-bg"></div>
            <style>
    .skin-list li{
        float:left; width: 33.33333%; padding: 5px;
    }
    .skin-list li a{
        display: block; box-shadow: 0 0 3px rgba(0,0,0,0.4);
    }
</style>
<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
        <li class="active"><a href="#control-sidebar-setting-tab" data-toggle="tab" aria-expanded="true"><i class="fa fa-wrench"></i></a></li>
        <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
        <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
        <div class="tab-pane active" id="control-sidebar-setting-tab">
            <h4 class="control-sidebar-heading">布局设定</h4>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="fixed" class="pull-right"> 固定布局</label><p>盒子模型和固定布局不能同时启作用</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="layout-boxed" class="pull-right"> 盒子布局</label><p>盒子布局最大宽度将被限定为1250px</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-layout="sidebar-collapse" class="pull-right"> 切换菜单栏</label><p>切换菜单栏的展示或收起</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-enable="expandOnHover" class="pull-right"> 菜单栏自动展开</label><p>鼠标移到菜单栏自动展开</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-menu="show-submenu" class="pull-right"> 显示菜单栏子菜单</label><p>菜单栏子菜单将始终显示</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-menu="disable-top-badge" class="pull-right"> 禁用顶部彩色小角标</label><p>左边菜单栏的彩色小角标不受影响</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-controlsidebar="control-sidebar-open" class="pull-right"> 切换右侧操作栏</label><p>切换右侧操作栏覆盖或独占</p></div>
            <div class="form-group"><label class="control-sidebar-subheading"><input type="checkbox" data-sidebarskin="toggle" class="pull-right"> 切换右侧操作栏背景</label><p>将右侧操作栏背景亮色或深色切换</p></div>
            <h4 class="control-sidebar-heading">皮肤</h4>
            <ul class="list-unstyled clearfix skin-list">
                <li><a href="javascript:;" data-skin="skin-blue" style="" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Blue</p></li>
                <li><a href="javascript:;" data-skin="skin-white" class="clearfix full-opacity-hover"><div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">White</p></li>
                <li><a href="javascript:;" data-skin="skin-purple" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Purple</p></li>
                <li><a href="javascript:;" data-skin="skin-green" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Green</p></li>
                <li><a href="javascript:;" data-skin="skin-red" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Red</p></li>
                <li><a href="javascript:;" data-skin="skin-yellow" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #222d32;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin">Yellow</p></li>
                <li><a href="javascript:;" data-skin="skin-blue-light" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px; background: #367fa9;"></span><span class="bg-light-blue" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Blue Light</p></li>
                <li><a href="javascript:;" data-skin="skin-white-light" class="clearfix full-opacity-hover"><div style="box-shadow: 0 0 2px rgba(0,0,0,0.1)" class="clearfix"><span style="display:block; width: 20%; float: left; height: 7px; background: #fefefe;"></span><span style="display:block; width: 80%; float: left; height: 7px; background: #fefefe;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">White Light</p></li>
                <li><a href="javascript:;" data-skin="skin-purple-light" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-purple-active"></span><span class="bg-purple" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Purple Light</p></li>
                <li><a href="javascript:;" data-skin="skin-green-light" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-green-active"></span><span class="bg-green" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Green Light</p></li>
                <li><a href="javascript:;" data-skin="skin-red-light" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-red-active"></span><span class="bg-red" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px">Red Light</p></li>
                <li><a href="javascript:;" data-skin="skin-yellow-light" class="clearfix full-opacity-hover"><div><span style="display:block; width: 20%; float: left; height: 7px;" class="bg-yellow-active"></span><span class="bg-yellow" style="display:block; width: 80%; float: left; height: 7px;"></span></div><div><span style="display:block; width: 20%; float: left; height: 20px; background: #f9fafc;"></span><span style="display:block; width: 80%; float: left; height: 20px; background: #f4f5f7;"></span></div></a><p class="text-center no-margin" style="font-size: 12px;">Yellow Light</p></li>
            </ul>
        </div>
        <!-- /.tab-pane -->
        <!-- Home tab content -->
        <div class="tab-pane" id="control-sidebar-home-tab">
            <h4 class="control-sidebar-heading">主页</h4>
        </div>
        <!-- /.tab-pane -->
        <!-- Settings tab content -->
        <div class="tab-pane" id="control-sidebar-settings-tab">
            <h4 class="control-sidebar-heading">配置</h4>
        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- /.control-sidebar -->
        </div>

        <!-- 加载JS脚本 -->
        <!-- <script src="/js/jquery.1.9.1.min.js"></script> -->
        <script src="/js/require-backend.min.js?v=1.0.1"></script>
    </body>
</html>