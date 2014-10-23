<{include file="header.tpl" title="相片列表"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>相片名</th>
			<th>所属相册</th>
			<th>来源</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.pid}></td>
				<td>
					<a class="img" data-content="<img src='<{$row.path}>' class='img-responsive' />">
						<{$row.title}>
					</a>
				</td>
				<td><{if $row.aid}><a href="/admin/photo/?aid=<{$row.aid}>" title="查看相册图片"><{$row.name}></a><{/if}></td>
				<td><a href="<{$row.sourceurl}>"><{$row.sourcetext}></a></td>
				<td>
					<a href="/admin/photo/edit/?pid=<{$row.pid}>">编辑</a>
					<a href="/admin/photo/delete/?pid=<{$row.pid}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<script>
	$('a.img').popover({
		"container" : "body",
		"html" : true,
		"placement" : "bottom"
	})
</script>
<{include file="footer.tpl"}>