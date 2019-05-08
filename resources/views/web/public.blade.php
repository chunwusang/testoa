<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ $agenhinfo->name }}</title>
<link rel="shortcut icon" href="{{ $agenhinfo->face }}" />
<link href="{{ $userinfo->bootstyle }}" rel="stylesheet">
<script src="/js/jquery.1.9.1.min.js"></script>
<script src="/js/js.js"></script>
<script src="/js/jsmanage.js"></script>
<script src="/js/base64-min.js"></script>
<script>
var cnum='{{ $companyinfo->num }}';
</script>
<style>
.input_date{background:url(/images/date.png) no-repeat right;cursor:pointer}
</style>
</head>
<body>
<div style="padding:10px">
@yield('content')
</div>

<script src="/bootstrap/js/bootstrap.min.js"></script>
<script src="/res/plugin/jquery-rockmodel.js"></script>

<!--rockmenu-->
<link href="/res/plugin/jquery-rockmenu.css" rel="stylesheet">
<script src="/res/plugin/jquery-rockmenu.js"></script>
<script src="/res/js/jquery-imgview.js"></script>

@yield('script')

</body>
</html>
