<{include file="header.tpl"}>
<form action="/admin/category/addAction" method="post" class="form-horizontal edit" role="form">
	<div class="form-group">
		 <label for="catname" class="col-sm-2 control-label">栏目名：</label>
		<div class="col-sm-10">
			<input type="text" name="catname" id="catname" class="form-control" required="required" />
		</div>
	</div>
	<div class="form-group">
		<label for="title" class="col-sm-2 control-label">栏目标题：</label>
		<div class="col-sm-4">
			<input type="text" name="title" id="title" class="form-control" required="required" />
		</div>
		<label for="arc_title" class="col-sm-2 control-label">文章标题：</label>
		<div class="col-sm-4">
			<input type="text" name="arc_title" id="arc_title" class="form-control" required="required" />
		</div>
	</div>
	<div class="form-group">
		<label for="keyword" class="col-sm-2 control-label">栏目关键词：</label>
		<div class="col-sm-4">
			<input type="text" name="keyword" id="keyword" class="form-control" required="required" />
		</div>
		<label for="arc_keyword" class="col-sm-2 control-label">文章关键词：</label>
		<div class="col-sm-4">
			<input type="text" name="arc_keyword" id="arc_keyword" class="form-control" required="required" />
		</div>
	</div>
	<div class="form-group">
		<label for="description" class="col-sm-2 control-label">栏目简介：</label>
		<div class="col-sm-4">
			<input type="text" name="description" id="description" class="form-control" required="required" />
		</div>
		<label for="arc_description" class="col-sm-2 control-label">文章简介：</label>
		<div class="col-sm-4">
			<input type="text" name="arc_description" id="arc_description" class="form-control" required="required" />
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-2">
			<button class="btn btn-primary">添加</button>
		</div>
	</div>
	<input type="hidden" name="mid" id="mid" value="<{$mid}>" />
</form>
<fieldset>
	<legend>编辑栏目</legend>
	<{foreach from=$list item=row}>
		<form action="/admin/category/editAction" method="post" class="form-horizontal edit" role="form">
			<div class="form-group">
				<div class="col-sm-9">
					<input type="text" name="sitename" id="sitename" class="form-control" required="required" value="<{$row.catname}>" />
				</div>
				<div class="col-sm-1">
					<button class="btn btn-primary">修改</button>
				</div>
			</div>
			<input type="hidden" name="mid" id="mid" value="<{$mid}>" />
			<input type="hidden" name="cid" id="cid" value="<{$row.cid}>" />
		</form>
	<{foreachelse}>
		<div>暂无栏目</div>
	<{/foreach}>
</fieldset>
<{include file="footer.tpl"}>