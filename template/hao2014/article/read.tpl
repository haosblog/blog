<{include file="common/header.tpl"}>
<div id="arc_read">
	<article>
		<header>
			<h1><{$title}></h1>
			<p><{date('Y-m-d H:i:s', $article.wrtime)}>&nbsp;&nbsp;<a href="#re" id="article_re">回复（<span><{$article.repostcount}></span>）</a>&nbsp;&nbsp;<a href="#article_read_bottom">分享</a></p>
		</header>
		<div class="copyright">
			<{if $article.original == 1}>
				本文为小皓原创<br />
				转载请注明出处：<a href="http://www.haosblog.com/article/read&aid=<{$article.aid}>" target="_blank">http://www.haosblog.com/article/read&aid=<{$article.aid}></a>
			<{else}>
				本文为转载，出处：<a href="<{$article.fromurl}>" target="_blank"><{$article.fromurl}></a>
			<{/if}>
		</div>
		<article class="content">
			<div class="ad_right fr"><iframe src="ad/gg_right.html"></iframe></div>
			<{$article.content}>
		</article>
	</article>
	<div class="bottom">
		<!-- 百度分享 -->
		<div id="bdshare" class="bdshare_b fl" style="line-height: 12px;">
			<img src="http://bdimg.share.baidu.com/static/images/type-button-1.jpg?cdnversion=20120831" />
			<a class="shareCount"></a>
		</div>
	</div>
	<div class="ad_banner"><iframe src="ad/gg_buttom.html"></iframe></div>
	<article id="comment">
		<h2>评论</h2>
		<{include file="./comment.tpl"}>
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
						<td valign="top">*评论内容：</td>
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
							<input type="hidden" value="1" name="type" />
							<input type="hidden" value="<{$article.aid}>" name="fid" />
						</td>
					</tr>
				</table>
			</form>
		</article>
	</article>
</div>
<{if $t != 1}>
<script type="text/javascript">
	var v5_url = "http://www.haosblog.com/article/read&aid=<{$article.aid}>";
	var v5_title = "<{$article.title}>";
</script>
<{/if}>
<{include file="common/footer.tpl"}>