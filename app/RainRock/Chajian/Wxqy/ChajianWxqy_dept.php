<?php
 namespace App\RainRock\Chajian\Wxqy; use App\Model\Base\WxqydeptModel; use App\Model\Base\DeptModel; class ChajianWxqy_dept extends ChajianWxqy { public function getdeptlist() { $token = $this->gettoken(); if(is_array($token))return $token; $url = ''.$this->gettourl('URL_deptlist').'?access_token='.$token.''; $arr = $this->runcurl($url); $barr = $this->setbackarr($arr->errcode, $arr->errmsg); if($arr->errcode==0){ $list = $arr->department; $idss = [0]; foreach($list as $k=>$rs){ $id = $rs->id; $frs = WxqydeptModel::where(['cid'=>$this->companyid,'id'=>$id])->first(); if(!$frs)$frs = new WxqydeptModel(); $frs->cid = $this->companyid; $frs->id = $id; $frs->name = $rs->name; $frs->parentid = $rs->parentid; $frs->order = $rs->order; $frs->save(); $idss[] = $id; } WxqydeptModel::where('cid',$this->companyid)->whereNotin('id', $idss)->delete(); } return $barr; } public function getDeptArr($cid) { $this->getDeptArrss = array(); $rows = WxqydeptModel::where('cid', $cid)->orderBy('order','desc')->get(); $this->getDeptArrs($rows, 0, 0); return $this->getDeptArrss; } private $getDeptArrss; private function getDeptArrs($rows, $pid, $level) { foreach($rows as $k=>$rs){ if($rs->parentid == $pid){ $rs->level = $level; $this->getDeptArrss[] = $rs; $this->getDeptArrs($rows, $rs->id, $level+1); } } } public function anaytosys() { $barr = $this->backarr; $cid = $this->companyid; $rows = WxqydeptModel::where('cid', $cid)->get(); if(!$rows)return $this->backerror('请先获取企业微信部门列表在同步'); $dbs = $this->getNei('dept'); $idss = [0]; $rootrs = $dbs->getroot($cid); $rootid = $rootrs->id; foreach($rows as $k=>$rs){ $name = $rs->name; $id = $rs->id; if($id==$this->deptrootid)$id = $rootid; $bars = $dbs->save($cid, $id, [ 'name' => $name, 'sort' => $rs->order, 'pid' => $rs->parentid, ]); $idss[] = $bars->id; } DeptModel::where('cid',$cid)->whereNotin('id', $idss); return $this->setbackarr(0,'同步成功'); } public function updatedept($id) { $wrs = WxqydeptModel::where(['cid'=>$this->companyid,'id'=>$id])->first(); $rs = DeptModel::where(['cid'=>$this->companyid,'id'=>$id])->first(); if(!$rs)return $this->backerror('部门不存在了'); if(!$wrs){ return $this->createdept($rs->id, $rs->name, $rs->pid, $rs->sort); }else{ return $this->updatedepts($rs->id, $rs->name, $rs->pid, $rs->sort); } } private function createdept($id, $name, $parentid, $order) { $bupi = c('dept')->getrootid($this->companyid); if($parentid==$bupi)$parentid = $this->deptrootid; $body = '{"name": "'.$name.'","parentid": '.$parentid.',"order": '.$order.',"id": '.$id.'}'; $token = $this->gettoken(); $url = ''.$this->gettourl('URL_deptcreate').'?access_token='.$token.''; $arr = $this->runcurl($url, $body); $barr = $this->setbackarr($arr->errcode, $arr->errmsg); if($arr->errcode==0){ $obj = new WxqydeptModel(); $obj->id = $id; $obj->name = $name; $obj->parentid = $parentid; $obj->order = $order; $obj->cid = $this->companyid; $obj->save(); } return $barr; } public function updatedepts($id, $name, $parentid, $order) { $body = '{"name": "'.$name.'","order": '.$order.''; $bupi = c('dept')->getrootid($this->companyid); if($parentid>0){ if($parentid==$bupi)$parentid = $this->deptrootid; $body.= ',"parentid": '.$parentid.''; }else{ $id = $this->deptrootid; } $body .= ',"id": '.$id.'}'; $token = $this->gettoken(); $url = ''.$this->gettourl('URL_deptupdate').'?access_token='.$token.''; $arr = $this->runcurl($url, $body); $barr = $this->setbackarr($arr->errcode, $arr->errmsg); if($arr->errcode==0){ $obj = WxqydeptModel::where(['cid'=>$this->companyid,'id'=>$id])->first(); $obj->id = $id; $obj->name = $name; $obj->parentid = $parentid; $obj->order = $order; $obj->cid = $this->companyid; $obj->save(); } return $barr; } public function deletedept($id) { $token = $this->gettoken(); if(is_array($token))return $token; $url = ''.$this->gettourl('URL_deptdelete').'?access_token='.$token.'&id='.$id.''; $arr = $this->runcurl($url); $barr = $this->setbackarr($arr->errcode, $arr->errmsg); if($arr->errcode==0){ WxqydeptModel::where(['cid'=>$this->companyid,'id'=>$id])->delete(); } return $barr; } }