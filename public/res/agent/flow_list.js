//流程的
c.searchparams=function(){
	if(get('search-applydt')){
		var dt1 = get('search-applydt').value;
		var dt2 = get('search-enddt').value;
		return {'applydt':dt1,'enddt':dt2};
	}
}