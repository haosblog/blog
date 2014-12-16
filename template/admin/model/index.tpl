<{include file="header.tpl"}>
<table class="table table-hover">
	<thead>
		<tr>
			<th width="80">ID</th>
			<th>模型名</th>
			<th>表名</th>
			<th>使用分类</th>
			<th>允许评论</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<{foreach from=$list item=item}>
			<tr>
				<td><{$item.mid}></td>
				<td><{$item.modname}></td>
				<td><{$item.tablename}></td>
				<td><{if $item.classable eq 1}><i class="icon-ok text-success"></i><{else}><i class="icon-remove text-danger"></i><{/if}></td>
				<td><{if $item.classable eq 1}><i class="icon-ok text-success"></i><{else}><i class="icon-remove text-danger"></i><{/if}></td>
				<td>
					<a href="/admin/model/edit?mid=<{$item.mid}>">编辑</a>&nbsp;&nbsp;
					<a href="/admin/model/del?mid=<{$item.mid}>">删除</a>
				</td>
			</tr>
		<{/foreach}>
	</tbody>
</table>
<{include file="footer.tpl"}>