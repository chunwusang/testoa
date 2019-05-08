<?php
/**
*	插件-后台样式数组
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*	使用方法 $obj = c('array');
*/

namespace App\RainRock\Chajian\Base;



class ChajianBase_bootstyle extends ChajianBase
{
	public function getStylearr()
	{
		$stylearr[] = array(
			'name' 	=> '默认default',
			'value'	=> ''
		);
		$stylearr[] = array(
			'name' 	=> 'cerulean',
			'value'	=> '/bootstrap/css/app_cerulean.css'
		);
		$stylearr[] = array(
			'name' 	=> 'readable',
			'value'	=> '/bootstrap/css/app_readable.css'
		);
		$stylearr[] = array(
			'name' 	=> 'cosmo',
			'value'	=> '/bootstrap/css/app_cosmo.css'
		);
		$stylearr[] = array(
			'name' 	=> 'darkly',
			'value'	=> '/bootstrap/css/app_darkly.css'
		);
		$stylearr[] = array(
			'name' 	=> 'journal',
			'value'	=> '/bootstrap/css/app_journal.css'
		);
		$stylearr[] = array(
			'name' 	=> 'spacelab',
			'value'	=> '/bootstrap/css/app_spacelab.css'
		);
		$stylearr[] = array(
			'name' 	=> 'simplex',
			'value'	=> '/bootstrap/css/app_simplex.css'
		);
		$stylearr[] = array(
			'name' 	=> 'united',
			'value'	=> '/bootstrap/css/app_united.css'
		);
		$stylearr[] = array(
			'name' 	=> 'cyborg',
			'value'	=> '/bootstrap/css/app_cyborg.css'
		);
		$stylearr[] = array(
			'name' 	=> 'flatly',
			'value'	=> '/bootstrap/css/app_flatly.css'
		);
		$stylearr[] = array(
			'name' 	=> 'lumen',
			'value'	=> '/bootstrap/css/app_lumen.css'
		);
		$stylearr[] = array(
			'name' 	=> 'paper',
			'value'	=> '/bootstrap/css/app_paper.css'
		);
		
		$stylearr[] = array(
			'name' 	=> 'sandstone',
			'value'	=> '/bootstrap/css/app_sandstone.css'
		);
		$stylearr[] = array(
			'name' 	=> 'yeti',
			'value'	=> '/bootstrap/css/app_yeti.css'
		);
		return $stylearr;
	}
}