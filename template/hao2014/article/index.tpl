<{include file="common/header.tpl" title=$title|default:'日志列表'}>
<aside id="arc_class" class="fl box">
	<ul>
		<{foreach from=$category item=classRs}>
			<li><a href="/article?cid=<{$classRs.cid}>"><{$classRs.catname}></a>(<{$classRs.count}>)</li>
		<{foreachelse}>
			<li>暂无日志类型</li>
		<{/foreach}>
	</ul>
</aside>
<article id="arc_list" class="fr box">
	<header>
		<b class="count fr">阅/评</b>
		<b class="timer fr">时间</b>
		<b class="title">标题</b>
	</header>
	<ul>
		<{foreach from=$article item=row}>
		<li class="cl">
			<i class="fr"><{$row.viewcount}>/<{$row.repostcount}></i>
			<span class="fr"><{date('Y-m-d H:i:s', $row.wrtime)}></span>
			<span>
				[<a href="/article?cid=<{$row.cid}>"><{$row.catname}></a>]
				<em class="nobr"><a href="/article/read?aid=<{$row.aid}>" title="<{$row.title}>"><{$row.title}></a></em>
			</span>
		</li>
		<{foreachelse}>
			<li>暂无文章</li>
		<{/foreach}>
	</ul>
	<div id="article_bottom" class="fr">
		共<{$pageCount}>页&nbsp;
		当前第<{$page}>页&nbsp;
		<a href="<{$values}>&page=1">首页</a>&nbsp;&nbsp;
		<{if $pagePrev > 0}>
			<a href="<{$values}>&page=<{$pagePrev}>">上一页</a>&nbsp;&nbsp;
		<{/if}>
		<{if $pageNext > 0}>
			<a href="<{$values}>&page=<{$pageNext}>">下一页</a>&nbsp;&nbsp;
		<{/if}>
		<a href="<{$values}>&page=<{$pageCount}>">末页</a>&nbsp;&nbsp;
	</div>
</article>
<{include file="common/footer.tpl"}>