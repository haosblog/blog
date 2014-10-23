<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>站点名</th>
			<th>链接</th>
			<th>显示</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.id}></td>
				<td><{$row.name}></td>
				<td><a href="<{$row.url}>" title="<{$row.content}>"><{$row.url}></a></td>
				<td><{if $row.pass eq 1}>是<{else}>否<{/if}></td>
				<td>
					<{if $row.pass neq 1}><a href="/admin/link/pass/?id=<{$row.id}>">通过</a><{/if}>
					<a href="/admin/link/delete/?id=<{$row.id}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>