<{include file="common/header.tpl" title='留言本'}>
<div id="comment" class="list">
	<{foreach from=$list item=msgRs}>
		<article class="cl box">
			<header class="fl">
				<div class="msg_head"><img src="/static/common/image/portrait/<{$msgRs.portrait}>.jpg" /></div>
				<h1 class="center"><{$msgRs.username}></h1>
			</header>
			<section class="fr">
				<header>
					<em><{$msgRs.title}></em>
					<small class="fr"><{date('Y-m-d H:i:s', $msgRs.time)}></small>
				</header>
				<p><{$msgRs.content}></p>
			</section>
		</article>
	<{/foreach}>
	<div class="article_bottom">
		共<{$pageCount}>页&nbsp;
		当前第<{$page}>页&nbsp;
		<a href="?mod=msg&page=1">首页</a>&nbsp;&nbsp;
		<{if $pagePrev > 0}>
			<a href="?mod=msg&page=<{$pagePrev}>">上一页</a>&nbsp;&nbsp;
		<{/if}>
		<{if $pageNext > 0}>
			<a href="?mod=msg&page=<{$pageNext}>">下一页</a>&nbsp;&nbsp;
		<{/if}>
		<a href="?mod=msg&page=<{$pageCount}>">末页</a>&nbsp;&nbsp;
	</div>
	<article class="send box">
		<h3>发表评论：</h3>
		<form action="/comment/action" method="post">
			<div class="hideinput">
				<input type="radio" name="portrait" value="1" id="portrait1" />
				<input type="radio" name="portrait" value="2" id="portrait2" />
			</div>
			<table>
				<tr>
					<td width="150">*您的昵称：</td>
					<td><input type="text" name="username" /></td>
				</tr>
				<tr>
					<td>*留言标题：</td>
					<td><input type="text" name="title" /></td>
				</tr>
				<tr>
					<td valign="top">*留言内容：</td>
					<td><textarea name="content" cols="30" rows="7" class="form"></textarea></td>
				</tr>
				<tr>
					<td>*您的性别：</td>
					<td>
						<label for="portrait1">男：<input type="radio" name="sex" value="m" /></label>&nbsp;&nbsp;
						<label for="portrait2">女：<input type="radio" name="sex" value="f" /></label>
					</td>
				</tr>
				<tr>
					<td>您的Email：</td>
					<td><input type="text" name="email" id="email" />(可选，您的留言被回复后用于提醒，不公开)</td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<input type="submit" value="发表评论" />&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" value="重新填写" />
						<input type="hidden" value="0" name="type" />
					</td>
				</tr>
			</table>
		</form>
	</article>
</div>
<{include file="common/footer.tpl"}>