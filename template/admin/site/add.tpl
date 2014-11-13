<{include file="header.tpl"}>
<form action="/admin/site/addAction" method="post" class="form-horizontal edit" role="form">
	<table class="table table-hover table-form">
		<tr>
			<th><label for="sitename" class=" control-label">站点名：</label></th>
			<td><input type="text" name="sitename" id="sitename" class="form-control" required="required" /></td>
		</tr>
	</table>
	<div class="form-group">

		<div class="col-sm-9">

		</div>
	</div>
	<div class="form-group">
		<label for="seotitle" class="col-sm-3 control-label">首页标题：</label>
		<div class="col-sm-9">
			<input type="text" name="seotitle" id="seotitle" class="form-control" required="required" />
		</div>
	</div>
	<div class="form-group">
		<label for="keyword" class="col-sm-3 control-label">首页关键字：</label>
		<div class="col-sm-9">
			<input type="text" name="keyword" id="keyword" class="form-control" />
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-3 control-label">站点描述：</label>
		<div class="col-sm-9">
			<textarea name="description" id="description" class="form-control" ></textarea>
		</div>
	</div>
	<div class="form-group">
		<label for="domain" class="col-sm-3 control-label">站点域名：</label>
		<div class="col-sm-8">
			<input name="domain[]" class="form-control" />
		</div>
		<div class="col-sm-1" style="font-size: 20px; font-weight: bold;">
			<a href="javascript:void(0);" onclick="addDomain(this);">+</a>
		</div>
	</div>
	<div class="form-group">

	</div>
	<div class="form-group">
		<label for="tpid" class="col-sm-3 control-label">站点模板：</label>
		<div class="col-sm-9">
			<select  name="tpid" id="tpid" class="form-control">
				<{foreach from=$tplist item=tp}>
					<option value="<{$tp.tpid}>"><{$tp.templatename}></option>
				<{foreachelse}>
					<option value="0">没有模板数据</option>
				<{/foreach}>
			</select>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-3"></div>
		<div class="col-sm-9">
			<button type="submit" class="btn btn-default">提交</button>
			<input type="hidden" name="wsid" id="wsid" value="<{$wsid}>" />
			<label class="checkbox-inline"><input type="checkbox" name="isdefault" id="isdefault" value="1" />设置为默认站点</label>
		</div>
	</div>
</form>
<{include file="footer.tpl"}>
<script type="text/javascript">
function addDomain(obj){
	var formgroup = $(obj).parent().parent();
	formgroup.after(formgroup.clone());
	$(obj).remove();
}
</script>