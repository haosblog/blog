<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>域名</th>
			<th>所属站点</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.did}></td>
				<td><a href="<{$row.domain}>" target="_blank"><{$row.domain}></a></td>
				<td><{$row.sitename}></td>
				<td>
					<a href="/admin/site/deleteDomain/?did=<{$row.did}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>