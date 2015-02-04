<{include file="common/header.tpl" title="微博"}>
<article id="moodlist">
	<header>新浪微博：<a href="http://weibo.com/haosblog" target="_blank">http://weibo.com/haosblog</a></header>
	<{model model="mood" count="10" page=$smarty.get.page}>
		<article class="cl">
			<div class="mood_dialog">
				<p><{$row.content}></p>
				<div><img src="/static/hao2014/image/mood_dialog_arr.png" alt="" /></div>
			</div>
			<aside class="fr"><img src="/static/hao2014/image/h.jpg" alt="" /></aside>
			<small class="fl">发表时间：<{date('Y-m-d H:i:s', $row.dateline)}></small>
		</article>
	<{/model}>
	<div class="fr">
		<{if $smarty.get.page > 0}>
			<a href="?mod=mood&page=<{$smarty.get.page - 1}>">上一页</a>&nbsp;&nbsp;
		<{/if}>
		<a href="?mod=mood&page=<{$smarty.get.page + 1}>">下一页</a>&nbsp;&nbsp;
	</div>
</article>
<{include file="common/footer.tpl"}>