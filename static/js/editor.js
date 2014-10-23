
KindEditor.plugin('haoupload', function(K) {
        var e = this, name = 'haoupload';
        // 点击图标时执行
        e.clickToolbar(name, function() {
		var dialog = K.dialog({
			width : 700,
			height: 500,
			title : '上传图片',
			body : '<div style="margin:10px;"><strong>请选择要上传的图片或者将图片拖拽到本窗口</strong></div>',
			closeBtn : {
				name : '关闭',
				click : function(e) {
					dialog.remove();
				}
			},
			yesBtn : {
				name : '确定上传',
				click : function(e) {
					alert(this.value);
				}
			},
			noBtn : {
				name : '取消',
				click : function(e) {
					dialog.remove();
				}
			}
		});
        });
});

KindEditor.lang({
        haoupload : '上传图片'
});

var editor;
KindEditor.ready(function(K) {
	$(".kindeditor").each(function(){
		var editor;
		var name = $(this).attr("name");
		editor = K.create(this, {
			height : "400px",
			items : [
				'source', '|', 'undo', 'redo', '|', 'preview', 'print', 'template', 'cut', 'copy', 'paste',
				'plainpaste', 'wordpaste', '|', 'justifyleft', 'justifycenter', 'justifyright',
				'justifyfull', 'insertorderedlist', 'insertunorderedlist', 'indent', 'outdent', 'subscript',
				'superscript', 'clearhtml', 'quickformat', 'selectall', '|', 'fullscreen', '/',
				'formatblock', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold',
				'italic', 'underline', 'strikethrough', 'lineheight', 'removeformat', '|', 'haoupload',
				'flash', 'media', 'insertfile', 'table', 'hr', 'emoticons', 'map', 'code', 'pagebreak',
				'link', 'unlink', '|', 'about'
			]

		});
	});
});