<{include file="header.tpl"}>
<form action="/admin/model/importAction" method="post" enctype="multipart/form-data">
	<label for="modelJson">选择要导入的JSON文件</label>
	<input type="file" name="modelJson" id="modelJson" />
	<input type="submit" name="submit" value="上传" />
</form>
<{include file="footer.tpl"}>