<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<{foreach from=$field item=fieldRow}>
				<th><{$fieldRow}></th>
			<{/foreach}>
			<th>操作</th>
		</tr>
	</thead>
		<{foreach from=$list item=row}>
			<tr>
				<{foreach from=$row item=span key=key}>
					<{if is_numeric($key)}>
						<td>
							<{if $fieldtype.$key eq 11}>
								<{date('Y-m-d H:i:s', $span)}>
							<{elseif $fieldtype.$key eq 12 }>
								<{if $span neq 0}>是<{else}>否<{/if}>
							<{else}>
								<{$span}>
							<{/if}>
						</td>
					<{/if}>
				<{/foreach}>
				<td>
					<a href="">编辑</a>
					<a href="">删除</a>
				</td>
			</tr>
		<{/foreach}>
	<tbody>
	</tbody>
</table>
<{include file="footer.tpl"}>