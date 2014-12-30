<{include file="header.tpl" title="发表$modname" loadjs="kindeditor/kindeditor, kindeditor/lang/zh_CN,editor" loadcss="editorthemes/default/default"}>
<form action="" method="post" class="form-horizontal edit" role="form">
	<{*<div class="form-group">
		<label for="cid" class="col-sm-2 control-label">请选择栏目：</label>
		<div class="col-sm-10">
			<select name="cid">
				<option value="1">栏目</option>
			</select>
		</div>
	</div>*}>
	<{foreach from=$list item=row}>
		<div class="form-group">
			<label for="<{$row.fieldname}>" class="col-sm-2 control-label"><{$row.viewname}>：</label>
			<div class="col-sm-10">
				<{$row|parseModelForm}>
			</div>
		</div>
	<{foreachelse}>
		<article id=""><p>糟糕，貌似这个模型被损坏了</p></article>
	<{/foreach}>
	<div class="form-group">
		<label for="submit" class="col-sm-2 control-label"></label>
		<div class="col-sm-10">
			<button class="btn btn-primary" type="submit" name="submit">提交</button>
		</div>
	</div>
</form>
<{include file="footer.tpl"}>