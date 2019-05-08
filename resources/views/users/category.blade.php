<!DOCTYPE html>
<html lang="zh-cn">
    <head>
        <meta charset="utf-8">
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<meta name="renderer" content="webkit">

<link rel="shortcut icon" href="/img/favicon.ico" />

<!-- Loading Bootstrap -->
<link href="/css/backend.min.css?v=1.0.1" rel="stylesheet">


<!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
<!--[if lt IE 9]>
  <script src="/js/html5shiv.js"></script>
  <script src="/js/respond.min.js"></script>
<![endif]-->
<script type="text/javascript">
    var require = {
        config:  {"site":{"name":"FastAdmin","cdnurl":"","version":"1.0.1","timezone":"Asia\/Shanghai","languages":{"backend":"zh-cn","frontend":"zh-cn"}},"upload":{"cdnurl":"","uploadurl":"ajax\/upload","bucket":"local","maxsize":"10mb","mimetype":"jpg,png,jpeg,gif","multipart":[],"multiple":false},"modulename":"admin","controllername":"category","actionname":"index","jsname":"backend\/category","moduleurl":"\/admin","language":"zh-cn","fastadmin":{"usercenter":true,"login_captcha":false,"login_failure_retry":true,"login_unique":false,"login_background":"\\/img\/loginbg.jpg","multiplenav":false,"checkupdate":false,"version":"1.0.0.20181210_beta","api_url":"https:\/\/api.fastadmin.net"},"referer":null,"__PUBLIC__":"\/","__ROOT__":"\/","__CDN__":"https:\/\/cdn.pure.fastadmin.net"}    };
</script>
    </head>

    <body class="inside-header inside-aside ">
        <div id="main" role="main">
            <div class="tab-content tab-addtabs">
                <div id="content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                            <section class="content-header hide">
                                <h1>
                                    控制台                                    <small>Control panel</small>
                                </h1>
                            </section>
                                                        <!-- RIBBON -->
                            <div id="ribbon">
                                <ol class="breadcrumb pull-left">
                                    <li><a href="dashboard" class="addtabsit"><i class="fa fa-dashboard"></i> 控制台</a></li>
                                </ol>
                                <ol class="breadcrumb pull-right">
                                                                        <li><a href="javascript:;" data-url="/admin/category/category">分类管理</a></li>
                                                                    </ol>
                            </div>
                            <!-- END RIBBON -->
                                                        <div class="content">
                                <div class="panel panel-default panel-intro">
    <div class="panel-heading">
        <div class="panel-lead"><em>分类管理</em>用于统一管理网站的所有分类,分类可进行无限级分类</div>        <ul class="nav nav-tabs">
            <li class="active"><a href="#all" data-toggle="tab">全部</a></li>
                            <li><a href="#default" data-toggle="tab">默认</a></li>
                            <li><a href="#page" data-toggle="tab">单页</a></li>
                            <li><a href="#article" data-toggle="tab">文章</a></li>
                            <li><a href="#test" data-toggle="tab">Test</a></li>
                    </ul>

    </div>
    <div class="panel-body">
        <div id="myTabContent" class="tab-content">
            <div class="tab-pane fade active in" id="one">
                <div class="widget-body no-padding">
                    <div id="toolbar" class="toolbar">
                        <a href="javascript:;" class="btn btn-primary btn-refresh" title="刷新" ><i class="fa fa-refresh"></i> </a> <a href="javascript:;" class="btn btn-success btn-add" title="添加" ><i class="fa fa-plus"></i> 添加</a> <a href="javascript:;" class="btn btn-success btn-edit btn-disabled disabled" title="编辑" ><i class="fa fa-pencil"></i> 编辑</a> <a href="javascript:;" class="btn btn-danger btn-del btn-disabled disabled" title="删除" ><i class="fa fa-trash"></i> 删除</a>                        <div class="dropdown btn-group ">
                            <a class="btn btn-primary btn-more dropdown-toggle btn-disabled disabled" data-toggle="dropdown"><i class="fa fa-cog"></i> 更多</a>
                            <ul class="dropdown-menu text-left" role="menu">
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;" data-params="status=normal"><i class="fa fa-eye"></i> 设为正常</a></li>
                                <li><a class="btn btn-link btn-multi btn-disabled disabled" href="javascript:;" data-params="status=hidden"><i class="fa fa-eye-slash"></i> 设为隐藏</a></li>
                            </ul>
                        </div>
                    </div>
                    <table id="table" class="table table-striped table-bordered table-hover" 
                           data-operate-edit="1" 
                           data-operate-del="1" 
                           width="100%">
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/js/require.min.js" data-main="/js/require-backend.min.js?v=1.0.1"></script>
<script>

var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?58347d769d009bcf6074e9a0ab7ba05e";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>
    </body>
</html>
