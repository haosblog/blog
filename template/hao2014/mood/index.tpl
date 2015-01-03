<{include file="common/header.tpl" title="微博"}>
<article id="moodlist">
	<header>新浪微博：<a href="http://weibo.com/haosblog" target="_blank">http://weibo.com/haosblog</a></header>
	<{model model="mood" count="10" page="$smarty.get.page"}>
		<article class="cl">
			<div class="mood_dialog">
				<p><{$row.content}></p>
				<div><img src="/static/hao2014/image/mood_dialog_arr.png" alt="" /></div>
			</div>
			<aside class="fr"><img src="/static/hao2014/image/h.jpg" alt="" /></aside>
			<small class="fl">发表时间：<{date('Y-m-d H:i:s', $row.dateline)}></small>
		</article>
	<{/model}>
	<div class="fr"><{$row.content}>
		共<{$pageCount}>页&nbsp;
		当前第<{$page}>页&nbsp;
		<a href="?mod=mood&page=1">首页</a>&nbsp;&nbsp;
		<{if $pagePrev > 0}>
			<a href="?mod=mood&page=<{$pagePrev}>">上一页</a>&nbsp;&nbsp;
		<{/if}>
		<{if $pageNext > 0}>
			<a href="?mod=mood&page=<{$pageNext}>">下一页</a>&nbsp;&nbsp;
		<{/if}>
		<a href="?mod=mood&page=<{$pageCount}>">末页</a>&nbsp;&nbsp;
	</div>
</article>
<{include file="common/footer.tpl"}>