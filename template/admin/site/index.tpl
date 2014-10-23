<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<th>站点名</th>
			<th>默认站点</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.wsid}></td>
				<td><a href="/admin/site/domain?wsid=<{$row.wsid}>" title="查看域名列表"><{$row.sitename}></a></td>
				<td><{if $row.isdefault eq 1}>是<{else}>否<{/if}></td>
				<td>
					<a href="/admin/site/edit/?wsid=<{$row.wsid}>">编辑</a>
					<a href="/admin/site/delete/?wsid=<{$row.wsid}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>