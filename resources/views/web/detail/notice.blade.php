<div style="font-size:24px;padding:20px 0px;" align="center">{{ $data->title }}</div>
<div style="font-size:12px;color:#888888;">[{{ $data->typename }}] {{ $data->optdt }}</div>
<div>{!! $data->content !!}</div>
@if($data->sysupfile)
<div><b>相关文件</b></div>
<div>{!! $data->sysupfile !!}</div>
@endif

{!! $data->toupianitem !!}

<div align="right">{{ $data->zuozhe }}<br />{{ $data->indate }}</div>