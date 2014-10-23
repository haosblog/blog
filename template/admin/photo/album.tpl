<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>相册名</th>
			<th>是否有密码</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.aid}></td>
				<td><{$row.name}></td>
				<td><{if empty($row.password)}>否<{else}>是，提示：<{$row.clew}><{/if}></td>
				<td>
					<a href="/admin/site/edit/?wsid=<{$row.wsid}>">编辑</a>
					<a href="/admin/site/delete/?wsid=<{$row.wsid}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>