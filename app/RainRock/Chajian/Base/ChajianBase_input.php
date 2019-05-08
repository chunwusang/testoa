<?php
/**
*	插件-字段元素
*	主页：http://www.rockoa.com/
*	软件：信呼OA云平台
*	作者：雨中磐石(rainrock)
*	时间：2018-05-13 09:52:34
*/

namespace App\RainRock\Chajian\Base;


class ChajianBase_input extends ChajianBase
{
	
	/**
	*	显示
	*	$item 字段信息，$data 单据数据， $kzf子表序号：1_0 ,$iszb的几个子表
	*/
	public function show($item, $data, $store=array(), $kzf='', $iszb=0)
	{
		$type 	= $item->fieldstype;
		$act 	= 'fields_'.$type.'';
		if(!method_exists($this, $act))$act 	= 'fields_text';
		$fields = $item->fields;
		$val 	= objvalue($data, $fields);
		$this->data 	= $data;
		$this->zibiao	= $kzf;
		$this->iszb		= $iszb;
		$item->fields 	= $fields.$kzf;
		return $this->$act($item,$val, arrvalue($store, $fields, array()));
	}
	
	
	//文本框
	private function fields_text($item, $val)
	{
		$str = '<input class="inputs" name="'.$item->fields.'" type="text" value="'.$val.'" placeholder="'.$item->placeholder.'" maxlength="'.$item->lengs.'" '.$item->attr.'>';
		return $str;
	}
	
	//创建编号
	private function fields_num($item, $val)
	{
		if(isempt($val) && !isempt($item->data)){
			$def = $item->data;
			$val = $this->getNei('mysql')->sericnum($def, $this->nowflow->mtable, $item->fields, 3);
		}
		$str = '<input class="inputs" name="'.$item->fields.'" type="text" value="'.$val.'" placeholder="'.$item->placeholder.'" maxlength="30" '.$item->attr.'>';
		return $str;
	}

	private function fields_auto($item, $val)
	{
		$str = $item->autoinput;
		return $str;
	}
	
	private function fields_hidden($item, $val)
	{
		$str = '<input class="inputs" name="'.$item->fields.'" type="hidden" value="'.$val.'">';
		return $str;
	}
	
	private function fields_date($item, $val)
	{
		$str = '<input class="inputs input_date" readonly name="'.$item->fields.'" type="text" value="'.$val.'" placeholder="'.$item->placeholder.'" onclick="js.datechange(this,\''.$item->fieldstype.'\')" '.$item->attr.'>';
		return $str;
	}
	private function fields_datetime($item, $val)
	{
		$str = $this->fields_date($item, $val);
		return $str;
	}
	private function fields_month($item, $val)
	{
		$str = $this->fields_date($item, $val);
		return $str;
	}
	private function fields_time($item, $val)
	{
		$str = $this->fields_date($item, $val);
		return $str;
	}
	
	private function fields_rockcombo($item, $val, $store)
	{
		$str = '<select onchange="c.changeselect(this,\''.$item->data.'\')" class="inputs inputsel" name="'.$item->fields.'">';
		$str.= '<option value="">-请选择-</option>';
		foreach($store as $k1=>$rs1){
			$sel = '';
			if($val==$rs1['value'])$sel='selected';
			$str.= '<option value="'.$rs1['value'].'" '.$sel.'>'.$rs1['name'].'</option>';
		}
		$str.= '</select>';	
		return $str;
	}
	private function fields_select($item, $val, $store)
	{
		$str = '<select onchange="c.changeselect(this)" class="inputs inputsel" name="'.$item->fields.'">';
		$str.= '<option value="">-请选择-</option>';
		foreach($store as $k1=>$rs1){
			$sel = '';
			if($val==$rs1['value'])$sel='selected';
			$str.= '<option value="'.$rs1['value'].'" '.$sel.'>'.$rs1['name'].'</option>';
		}
		$str.= '</select>';	
		return $str;
	}
	
	private function fields_changeuser($item, $val)
	{
		$str = '<table width="100%"><tr>';
		$str.='<td width="100%">';
		$str.=' <input class="inputs" style="border-radius:5px 0px 0px 5px;" name="'.$item->fields.'" type="text" id="change_'.$item->fields.'" readonly value="'.$val.'" placeholder="'.$item->placeholder.'" />';
		if(!isempt($item->data)){
			$val1 = objvalue($this->data, $item->data);
			$str.='<input name="'.$item->data.''.$this->zibiao.'" value="'.$val1.'" type="hidden" id="change_'.$item->fields.'_id" />';
		}
			
		$str.='</td>';
		$str.='<td><button style="width:100%" class="input_btn" onclick="c.changeclear(\''.$item->fields.'\')" type="button">×</button></td>';
		$str.='<td><button class="input_btn" style="border-radius:0px 5px 5px 0px" onclick="c.changeuser(\''.$item->fields.'\',\''.$item->fieldstype.'\',\''.$item->name.'\',\''.objvalue($item, 'changerange').'\');" type="button">选..</button></td>';
		$str.='</tr></table>';
		return $str;
	}
	
	//弹出下拉框(单选,多选)
	private function fields_selectdatafalse($item, $val)
	{
		$str = '<table width="100%"><tr>';
		$str.='<td width="100%">';
		$str.=' <input class="inputs" style="border-radius:5px 0px 0px 5px" readonly name="'.$item->fields.'" type="text" id="change_'.$item->fields.'" value="'.$val.'" placeholder="'.$item->placeholder.'" />';
		$fid1= '';
		if(!isempt($item->data)){
			$dats = explode(',', $item->data);
			if(count($dats)>1){
				$fid1 = arrvalue($dats, count($dats)-1);
				$val1 = objvalue($this->data, $fid1);
				$str.='<input name="'.$fid1.''.$this->zibiao.'" value="'.$val1.'" type="hidden" id="change_'.$item->fields.'_id" />';
			}
		}
			
		$str.='</td>';
		$str.='<td><button style="width:100%" class="input_btn" onclick="c.changeclear(\''.$item->fields.'\')" type="button">×</button></td>';
		$str.='<td><button class="input_btn" style="border-radius:0px 5px 5px 0px" onclick="c.selectdata(\''.$item->fields.'\', '.substr($item->fieldstype, 10).', '.$item->id.',\''.$item->name.'\',\''.$fid1.''.$this->zibiao.'\');" type="button">选..</button></td>';
		$str.='</tr></table>';
		return $str;
	}
	private function fields_selectdatatrue($item, $val)
	{
		return $this->fields_selectdatafalse($item, $val);
	}
	
	private function fields_changeusercheck($item, $val)
	{
		return $this->fields_changeuser($item, $val);
	}
	private function fields_changedept($item, $val)
	{
		return $this->fields_changeuser($item, $val);
	}
	private function fields_changedeptcheck($item, $val)
	{
		return $this->fields_changeuser($item, $val);
	}
	private function fields_changedeptusercheck($item, $val)
	{
		return $this->fields_changeuser($item, $val);
	}
	
	
	private function fields_number($item, $val)
	{
		$str = '<input class="inputs" name="'.$item->fields.'" type="number" onfocus="js.focusval=this.value" onblur="js.number(this);c.inputblur(this,'.$item->iszb.')" value="'.$val.'" placeholder="'.$item->placeholder.'" '.$item->attr.'>';
		return $str;
	}
	
	private function fields_checkbox($item, $val)
	{
		$sel = $val==1 ? 'checked' : '';
		$str = '<label><input name="'.$item->fields.'" '.$sel.' type="checkbox" value="1" />'.$item->placeholder.'</label>';
		return $str;
	}
	
	private function fields_textarea($item, $val)
	{
		$height = objvalue($item,'height','80px');
		$str = '<textarea style="height:'.$height.'" class="inputs" name="'.$item->fields.'" placeholder="'.$item->placeholder.'">'. $val.'</textarea>';
		return $str;
	}
	private function fields_htmlediter($item, $val)
	{
		$str = '<textarea class="inputs" temp="htmlediter" name="'.$item->fields.'" placeholder="'.$item->placeholder.'">'.$val.'</textarea>';
		return $str;
	}
	
	private function fields_radio($item, $val, $store)
	{
		$str = '';
		foreach($store as $k1=>$rs1){
			$sel = '';
			if($val==$rs1['value'])$sel='checked';
			$str.='<label><input name="'.$item->fields.'" type="radio" value="'.$rs1['value'].'" '.$sel.'>'.$rs1['name'].'</label>&nbsp;';
		}
		return $str;
	}
	
	private function fields_checkboxall($item, $val, $store)
	{
		$str = '';
		foreach($store as $k1=>$rs1){
			$sel = '';
			if(contain(','.$val.',', ','.$rs1['value'].','))$sel='checked';
			$str.='<label><input name="'.$item->fields.'[]" type="checkbox" value="'.$rs1['value'].'" '.$sel.'>'.$rs1['name'].'</label>&nbsp;';
		}
		return $str;
	}
	
	private function fields_uploadimg($item, $val)
	{
		$str = '<input name="'.$item->fields.'" type="hidden" value="'.$val.'" >';
		$src = \Rock::replaceurl(emptytodev($val,'/images/noimg.jpg'));
		$str.= '<div style="height:110px;overflow:hidden"><img id="'.$item->fields.'_imgview" height="110" onclick="c.imgviews(this)" src="'.$src.'"></div>';
		$str.= '<div><table><tr><td><button style="border-radius:5px 0px 0px 5px;width:100%" class="input_btn" onclick="c.uploadimg(\''.$item->fields.'\', this)" type="button">选..</button></td><td><button class="input_btn" style="background:#aaaaaa;border-radius:0px 5px 5px 0px" onclick="c.uploadimgclear(\''.$item->fields.'\')" type="button">×</button></td></tr></table></div>';
		return $str;
	}
	
	private function fields_uploadfile($item, $val)
	{
		$str = '<input name="'.$item->fields.'" type="hidden" value="'.$val.'" >';
		$str.= '<div style="display:inline-block" id="fileview_'.$item->fields.'">';
		$str.= '<div onclick="c.uploadfile(\''.$item->fields.'\',\''.$item->data.'\')" style="border:dashed 1px #cccccc" id="'.$item->fields.'_divadd" class="upload_items"><img class="imgs" src="/images/jia.png"></div>';
		$str.='</div>';
		return $str;
	}
	
	
	/**
	*	显示子表
	*/
	public function showsubtable($item, $fieldsarr, $subdata, $store)
	{
		$iszb = 1;
		if(!isempt($item->dev))$iszb = (int)$item->dev;
		if($iszb<0)$iszb = 1;//显示子表
		$lengs = $item->lengs;
		if($lengs<=0)$lengs = 1;
		$str = '';
		
		$deva 		= new \StdClass();
		$data 		= array();

		$str.= '<table id="tablesub'.$iszb.'" width="100%" class="subtable">';
		$str.= '<tr bgcolor="#f1f1f1"><td width="30" nowrap class="zbys1"></td>';
		foreach($fieldsarr as $k=>$rs){
			if($rs->iszb==$iszb){
				$fid1 	= $rs->fields;
				$deva->$fid1 = $rs->dev; //子表的默认值
				$isbt = '';
				$tdlx = '';
				if($rs->isbt==1)$isbt = '<font color=red>*</font>';
				if($rs->width)$tdlx=' width="'.$rs->width.'"';
				$str.='<td class="zbys1"'.$tdlx.' nowrap>'.$isbt.''.$rs->name.'</td>';
			}
		}
		$str.=' <td class="zbys1"></td>';
		$str.= '</tr>';
		$oi 		= 0;
		
		
		$subdata 	= arrvalue($subdata, $iszb, array());
		if($subdata)foreach($subdata as $i=>$data){
			$kzf = ''.$iszb.'_'.$i.'';
			$str.= '<tr><td class="zbys1"><input class="inputs" style="text-align:center;width:30px" temp="xuhao" value="'.($i+1).'"><input name="sid'.$kzf.'" value="'.$data->id.'" type="hidden"></td>';
			foreach($fieldsarr as $k=>$rs){
				if($rs->iszb==$iszb){
					$str.='<td class="zbys1">';
					$ors = clone $rs; 
					$str.= $this->show($ors, $data, $store, $kzf, $iszb);
					$str.='</td>';
				}
			}
			$str.=' <td class="zbys1"><a href="javascript:;" onclick="c.delrow(this,'.$iszb.')">删</a></td>';
			$str.= '</tr>';
			$oi++;
		}
		for($i=$oi;$i<$lengs; $i++){
			$kzf = ''.$iszb.'_'.$i.'';
			$str.= '<tr><td class="zbys1"><input class="inputs" style="text-align:center;width:30px" temp="xuhao" value="'.($i+1).'"><input name="sid'.$kzf.'" value="0" type="hidden"></td>';
			foreach($fieldsarr as $k=>$rs){
				if($rs->iszb==$iszb){
					$str.='<td class="zbys1">';
					$ors = clone $rs; 
					$str.= $this->show($ors, $deva, $store, $kzf, $iszb);
					$str.='</td>';
				}
			}
			$str.=' <td class="zbys1"><a href="javascript:;" onclick="c.delrow(this,'.$iszb.')">删</a></td>';
			$str.= '</tr>';
		}
		
		$str.= '</table>';
		$str.= '<div align="left"><a href="javascript:;" onclick=\'c.addrow(this,'.$iszb.', '.json_encode($deva).')\'>新增</a></div>';
		$str.= '<input name="sub_totals'.$iszb.'" type="hidden" value="'.$i.'">';
		$str.= '<input name="sub_minrow'.$iszb.'" type="hidden" value="'.$item->lengs.'">';
		$str.= '<input name="'.$item->fields.'" type="hidden">';
		
		return $str;
	}
	
	/**
	*	显示表单，一般用户在详情页需要表单时使用
	* 	如：echo $inputobj->showinput('explain','textarea');
	*	$fields 字段名(也可以数组), $type类型，$data当前记录的数据库,$store数据源
	*	返回表单string
	*/
	public function showinput($fields, $type, $data=null, $store=array())
	{
		$item = new \StdClass();
		$item->fieldstype 	= $type;
		$item->placeholder 	= '';
		$item->attr 		= '';
		$item->name 		= '';
		$item->data			= '';
		
		if(!is_array($fields)){
			$fida 	= explode(',',$fields);
			$fields = $fida[0];
			$item->fields 		= $fields;
			$item->data 		= arrvalue($fida, 1);
		}else{
			foreach($fields as $k1=>$v1)$item->$k1 = $v1;
		}
		
		$storea[$fields]	= $store;
		return $this->show($item, $data, $storea);
	}
}