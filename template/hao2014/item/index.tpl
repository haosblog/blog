<{include file="common/header.tpl"}>
	<article id="item" class="box">
		<table width="780" border="0" cellspacing="0" cellpadding="0" id="article_table" align="center">
			<tr id="article_mark">
				<td width="100" id="article_title_mark">产品名</td>
				<td align="center" width="150">产品类型</td>
				<td align="center" width="480">地址</td>
				<td align="center" width="150">运营状态</td>
			</tr>
			<{foreach from=$list item=itemRs}>
				<tr>
					<td class="article_title"><{$itemRs.name}></td>
					<td class="article_time" align="center"><{$itemRs.type}></td>
					<td class="article_read"><a href="<{$itemRs.url}>" target="_blank"><{$itemRs.url}></a></td>
					<td class="article_read" align="center"><{$itemRs.state}></td>
				</tr>
			<{/foreach}>
		</table>
	</article>
<{include file="common/footer.tpl"}>