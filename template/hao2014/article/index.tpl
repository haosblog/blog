<{include file="common/header.tpl" title=$title|default:'日志列表'}>
<div id="article_list" class="cl">
	<aside id="arc_class" class="fl box cl">
		<header class="hide_header">文章栏目</header>
		<ul>
			<{foreach from=$category item=classRs}>
				<li><a href="/article?cid=<{$classRs.cid}>"><{$classRs.catname}></a>(<{$classRs.count}>)</li>
			<{foreachelse}>
				<li>暂无日志类型</li>
			<{/foreach}>
		</ul>
	</aside>
	<article id="arc_list" class="box cl">
		<header class="hide_header">文章列表</header>
		<table>
			<thead>
				<tr>
					<th class="title">标题</th>
					<th class="timer">时间</th>
					<th class="count">阅/评</th>
				</tr>
			</thead>
			<tbody>
				<{foreach from=$article item=row}>
					<tr>
						<td class="title">
							<img src="/static/hao2014/image/m.gif" alt="" />
							[<a href="/article?cid=<{$row.cid}>"><{$row.catname}></a>]
							<em class="nobr"><a href="/article/read?aid=<{$row.aid}>" title="<{$row.title}>"><{$row.title}></a></em>
						</td>
						<td class="timer"><time><{date('Y-m-d H:i:s', $row.wrtime)}></time></td>
						<td class="count"><{$row.viewcount}>/<{$row.repostcount}></td>
					</tr>
				<{foreachelse}>
					<tr><td>暂无文章</td></tr>
				<{/foreach}>
			</tbody>
		</table>
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
</div>
<{include file="common/footer.tpl"}>
