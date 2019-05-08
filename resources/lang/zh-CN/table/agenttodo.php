<?php

return [
	
	'pagetitle'		=> '通知设置',
	
	'id'		=> '字段ID',
	'uid'		=> '创建用户ID',
	
	'name'		=> '名称标题',
	'name_msg'	=> '可以用主表{}变量,如{title}，不写默认应用的模块名',
	
	'num'		=> '编号',
	'num_msg'	=> '可不填',
	
	'todofields'		=> '主表上字段',
	'todofields_msg'	=> '主表上字段必须存储用户Id的',
	
	'changelx'	=> '触发类型',
	'changelx_boturn'	=> '提交时',
	'changelx_boedit'	=> '编辑时',
	'changelx_bodel'	=> '删除时',
	'changelx_bozuofei'	=> '作废时',
	'changelx_boping'	=> '评论时',
	'changelx_botong'	=> '步骤处理通过时',
	'changelx_bobutong'	=> '步骤处理不通过时',
	'changelx_bofinish'	=> '处理完成时',
	'changelx_botask'	=> '定时执行',
	
	'tasktype'	=> '定时频率',
	'tasktype_h'	=> '每小时',
	'tasktype_d'	=> '每天',
	'tasktype_m'	=> '每月',
	'tasktime'	=> '定时时间',
	'tasktime_msg'	=> '定时运行的时间',
	
	'todolx'	=> '通知给',
	'todolx_toturn'	=> '提交人',
	'todolx_toapply'	=> '申请人',
	'todolx_tocourse'	=> '流程所有参与人',
	'todolx_tosuper'	=> '直属上级',
	'todolx_tosuperall'	=> '全部上级',
	
	'wherestr'	=> '通知条件',
	'wherestr_msg'	=> '条件成立才会触发',
	
	'summary'	=> '通知内容',
	'summary_msg'	=> '通知内容摘要，可以用主表{}变量',
	
	'explain'		=> '说明',
	'explain_msg'	=> '简单说明这个是条做啥的',
	
	'status' 		=> '状态',
    'status1' 	=> '启用',
    'status0' 	=> '停用',
	
	'addtext'	=> '新增通知设置',
	'edittext'	=> '编辑通知设置',
	
];
