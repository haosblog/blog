<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th>ID</th>
			<{foreach from=$field item=fieldRow}>
				<th><{$fieldRow}></th>
			<{/foreach}>
			<th>操作</th>
		</tr>
	</thead>
		<{foreach from=$list item=row}>
			<tr>
				<td><{$row.id}></td>
				<{foreach from=$fieldtype item=span key=key}>
					<td>
						<{$row[$key]}>
						<{*<{if $fieldtype.$key eq 11}>
							<{date('Y-m-d H:i:s', $span)}>
						<{elseif $fieldtype.$key eq 12 }>
							<{if $span neq 0}>是<{else}>否<{/if}>
						<{else}>
							<{$span}>
						<{/if}>*}>
					</td>
				<{/foreach}>
				<td>
					<a href="/admin/dynamic/edit?mid=<{$mid}>&id=<{$row.id}>">编辑</a>
					<a href="/admin/dynamic/delete?mid=<{$mid}>&id=<{$row.id}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	<tbody>
	</tbody>
</table>
<{include file="footer.tpl"}>