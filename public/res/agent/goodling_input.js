
//触发子表对应行
c.onselectdata=function(fid,fda){
	if(fid.indexOf('goodsname')==0){
		this.setsubvalue(fid, 'unit', fda.unit);
	}
}