<{include file="header.tpl" title="编辑模型"}>
<form action="/admin/model/action" method="post" class="form-horizontal edit" role="form">
	<fieldset>
		<legend>基本信息</legend>
		<div class="form-group">
			<label for="modname" class="col-sm-1 control-label">模型名：</label>
			<div class="col-sm-4">
				<input type="text" name="modname" id="modname" class="form-control" required="required" value="<{$info.modname}>" />
			</div>
		</div>
		<div class="form-group">
			<label for="tablename" class="col-sm-1 control-label">表名：</label>
			<div class="col-sm-4">
				<input type="text" name="tablename" id="tablename" class="form-control" required="required" value="<{$info.tablename}>" />
			</div>
		</div>
		<div class="form-group">
			<label for="classable" class="col-sm-1 control-label">使用分类：</label>
			<div class="col-sm-4">
				<input type="checkbox" name="classable" id="classable"<{if $info.classable eq 1}> checked="checked"<{/if}> />
			</div>
		</div>
	</fieldset>
	<{*<fieldset>
		<legend>后台菜单信息</legend>
		<div class="form-group">
			<label for="modname" class="col-sm-1 control-label">模型名：</label>
			<div class="col-sm-4">
				<input type="text" name="modname" id="modname" class="form-control" required="required" />
			</div>
		</div>
		<div class="form-group">
			<label for="modname" class="col-sm-1 control-label">模型名：</label>
			<div class="col-sm-4">
				<input type="text" name="modname" id="modname" class="form-control" required="required" />
			</div>
		</div>
		<div class="form-group">
			<label for="modname" class="col-sm-1 control-label">模型名：</label>
			<div class="col-sm-4">
				<input type="text" name="modname" id="modname" class="form-control" required="required" />
			</div>
		</div>
	</fieldset>*}>
	<fieldset>
		<legend>字段信息</legend>
		<div class="form-group">
			<label for="modname" class="col-sm-1 control-label">模型名：</label>
			<div class="col-sm-4">
				<input type="text" name="modname" id="modname" class="form-control" required="required" value="<{$info.modname}>" />
			</div>
		</div>
	</fieldset>
	<div class="form-group">
		<label for="submit" class="col-sm-1 control-label"></label>
		<div class="col-sm-2">
			<button type="submit" name="submit" class="btn btn-primary">提交</button>
		</div>
	</div>
</form>
<{include file="footer.tpl"}>