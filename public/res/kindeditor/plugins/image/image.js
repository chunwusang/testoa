KindEditor.plugin('image', function(K) {
	var editor = this, name = 'image';
	editor.clickToolbar(name, function() {
		var dialog = K.dialog({
			width : 250,title : '上传图片',
			body : '<div style="margin:20px;" align="center"><div><img src="/images/noimg.jpg" height="110" id="kindeditorupfile_imgview"></div><div><input type="button" value="选择..." onclick="c.uploadimg(\'kindeditorupfile\', this)"><input type="hidden" id="kindeditorupfile_input"></div></div>',
			closeBtn : {name : '关闭',click : function(e) {dialog.remove();}},
			yesBtn : {name : '确定',
				click:function(e) {
					var src = get('kindeditorupfile_input').value;
					if(src){
						editor.insertHtml('<img src="'+get('kindeditorupfile_imgview').src+'">');
					}
					dialog.remove();
				}
			},
			noBtn : {name : '取消',click : function(e) {dialog.remove();}}
		});
	});
});