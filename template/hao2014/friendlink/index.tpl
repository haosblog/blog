<{include file="common/header.tpl" title="友情链接"}>
<article id="friendlink">
	<header>友情链接：</header>
	<div >
		<ul class="cl">
			<{friendlink}>
				<li class="fl"><a href="http://<{$row.url}>" title="<{$row.content}>" target="_blank"><{$row.name}></a></li>
			<{/friendlink}>
		</ul>
	</div>
</article>
<article id="apply">
	<header><h3>申请友情链接：</h3></header>
	<form action="/friendlink/action" method="post">
		<table>
			<tr>
				<td width="150"><label for="name">*网站名：</label></td>
				<td><input type="text" name="name" id="name" /></td>
			</tr>
			<tr>
				<td valign="top"><label for="url">*网站网址：</label></td>
				<td><input type="text" name="url" id="url" size="" /></td>
			</tr>
			<tr>
				<td valign="top"><label for="content">网站描述：</label></td>
				<td><textarea name="content" cols="50" rows="5" class="form"></textarea></td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="submit" value="提交申请" />&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" value="重新填写" />
				</td>
			</tr>
		</table>
	</form>
</article>
<{include file="common/footer.tpl"}>