<?php
/**
*	应用.出入库详情
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-07-25
*/

namespace App\Model\Agent;


class Rockagent_goodx  extends Rockagent
{
	protected $table = 'goodx';
	
	protected $appends = [
		'name',
		'classname',
		'num',
		'xinghao',
		'guige',
		'price',
		'unit',
    ];
	
	/**
	 * 跟物品表关联1对1
	 */
	public function goodsinfo()
	{
		return $this->hasOne(Rockagent_goods::class, 'id', 'goodsid')->select('name','xinghao','guige','price','unit','num','id','classname');
	}
	
	public function getNameAttribute($val)
    {
		return $this->getDefaultval($val, 'name');
    }
	
	public function getClassnameAttribute($val)
    {
		return $this->getDefaultval($val, 'classname');
    }
	
	public function getNumAttribute($val)
    {
		return $this->getDefaultval($val, 'num');
    }
	
	public function getXinghaoAttribute($val)
    {
		return $this->getDefaultval($val, 'xinghao');
    }
	
	public function getGuigeAttribute($val)
    {
		return $this->getDefaultval($val, 'guige');
    }
	
	public function getUnitAttribute($val)
    {
		return $this->getDefaultval($val, 'unit');
    }
	
	public function getPriceAttribute($val)
    {
		return $this->getDefaultval($val, 'price');
    }
	
	//得到默认值
	private function getDefaultval($val, $fields)
	{
		if(isempt($val) && $this->goodsinfo){
			$val = $this->goodsinfo->$fields;
		}
		return $val;
	}
}