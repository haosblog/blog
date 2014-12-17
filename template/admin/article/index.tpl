<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>标题</th>
			<th>原创</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.aid}></td>
				<td>
					[<a href="/admin/article?cid=<{$row.cid}>" title="<{$row.catname}>"><{$row.catname}></a>]
					<{$row.title}>
				</td>
				<td><{if $row.original eq 1}>
					<span class="glyphicon glyphicon-ok text-success"></span>
					<{else}>
						<span class="glyphicon glyphicon-remove text-danger"></span>
					<{/if}>
				</td>
				<td>
					<a href="/admin/article/status/?aid=<{$row.wsid}>">
						<{if $row.status eq 0}>
							<span class="text-success" title="发布文章">发布</span>
						<{else}>
							<span class="text-danger" title="设为草稿">草稿</span>
						<{/if}>
					</a>
					<a href="/admin/article/edit/?aid=<{$row.wsid}>">编辑</a>
					<a href="/admin/article/delete/?aid=<{$row.wsid}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>