c.onselectdata=function(fid,sda){
	if(fid=='meetitle'){
		form('receid').value=sda.receid;
		form('recename').value=sda.recename;
	}
}