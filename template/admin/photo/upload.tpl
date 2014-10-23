<{include file="header.tpl" loadjs="upload"}>
<label class="info">
	选择文件或者将文件拖拽到本窗口内
	<input type="file" name="upimg" id="upimg" multiple="" />
</label>
<form id="uploadform">
	<input type="hidden" name="uptype" id="uptype" value="0" />
	<label>
		请选择上传的相册
		<{html_options name="aid" options=$albumList selected=$aidSelect}>
	</label>
	<button id="uploadbutton" type="button" class="btn btn-info">上传</button>
	<div class="panel panel-default">
		<div class="panel-heading">图片预览</div>
		<div class="panel-body">
			<ul id="uploadview"> </ul>
		</div>
	</div>
</form>
<ul id="upview" class="hide">
	<li class="media">
		<input type="hidden" name="upid" value="" />
		<span class="pull-left col-md-2"><img class="media-object img-responsive" src="" alt=""></span>
		<a href="javascript:void(0);" _type="del">删除</a>
		<div class="media-body">
			<input placeholder="请输入相册标题" class="form-control" name="title" />
			<div><textarea name="summary" placeholder="请输入相册简介" class="form-control" rows="5"></textarea></div>
		</div>
	</li>
</ul>
<div class="alertBg">
	上传中...
</div>
<script>
	function uploadCallback(){
		location.href = "/admin/photo";
	}
</script>
<{include file="footer.tpl"}>