<{include file="header.tpl"}>
<form action="/admin/article/action" method="post" class="form-horizontal edit" role="form">
	<input type="hidden" name="aid" value="<{$info.aid}>" />
	<!--table class="table table-hover table-form">
		<tr>
			<th></th>
			<td></td>
		</tr>
	</table-->
	<fieldset>
		<div class="form-group">
			<label for="title" class="col-sm-2 control-label">文章标题：</label>
			<div class="col-sm-4">
				<input type="text" name="title" id="title" class="form-control" required="required" value="<{$info.title}>" />
			</div>
		</div>
		<div class="form-group">
			<label for="cid" class="col-sm-2 control-label">文章分类：</label>
			<div class="col-sm-2">
				<select name="cid" id="cid" class="form-control">
					<{foreach from=$category item=item}>
						<option value="<{$item.cid}>" <{if $info.cid eq $item.cid}> selected="selected"<{/if}>><{$item.catname}></option>
					<{/foreach}>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="content" class="col-sm-2 control-label">文章内容：</label>
			<div class="col-sm-10">
				<{include file="field/wysiwyg.tpl" name="content" content=$info.content_ori}>
			</div>
		</div>
		<div class="form-group">
			<label for="status" class="col-sm-2 control-label">文章属性：</label>
			<div class="col-sm-2">
				<select name="status" id="status" class="form-control">
					<option value="1"<{if $info.status eq 1}> selected="selected"<{/if}>>正常</option>
					<option value="0"<{if $info.status === 0}> selected="selected"<{/if}>>草稿</option>
				</select>
			</div>
			<div class="col-sm-2">
				<label for="original">
					<input type="checkbox" value="1" name="original" id="original" <{if $info.original !== 0}> checked="checked" <{/if}> /> 原创
				</label>
			</div>
		</div>
		<div class="form-group" style="display: none;" id="fromurl_box">
			<label for="fromurl" class="col-sm-2 control-label">来源地址：</label>
			<div class="col-sm-4">
				<input type="text" name="fromurl" id="fromurl" class="form-control" value="<{$info.fromurl}>" />
			</div>
		</div>
		<div class="form-group">
			<label for="submit" class="col-sm-2 control-label"></label>
			<div class="col-sm-2">
				<button type="submit" name="submit" class="btn">提交</button>
			</div>
		</div>
	</fieldset>
</form>
<script type="text/javascript">
	$("#original").change(function(){
		if($(this).is(":checked")){
			$("#fromurl_box").hide(200);
		} else {
			$("#fromurl_box").show(200);
		}
	});
</script>
<{include file="footer.tpl"}>
