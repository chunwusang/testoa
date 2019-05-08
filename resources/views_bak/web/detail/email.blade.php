<div style="font-size:24px;padding:20px 0px;" align="center">{{ $data->title }}</div>
<div style="background:#F1F1F1;padding:5px 10px;border:1px solid #dddddd;line-height:20px;border-radius:5px">
主题：{{ $data->title }}<br />
发送人：{{ $data->applyname }}<br />
收件人：{{ $data->recename }}<br />
发送时间：{{ $data->senddt }}
</div>
<div align="left" style="padding:10px">
{!! $data->content !!}
</div>
@if($data->sysupfile)
<div style="background:#F1F1F1;padding:5px 10px;border:1px solid #dddddd;line-height:20px;border-radius:5px">	
<div><b>附件</b></div>
<div>{!! $data->sysupfile !!}</div>
</div>
@endif

@if($data->emailid>0)
<div><a href="/detail/{{ $cnum }}/email/{{ $data->emailid }}">查看原邮件&gt;&gt;</a></div>	
@endif	