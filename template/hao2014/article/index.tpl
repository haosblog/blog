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
		<div class="fr page"><{$pagenav}></div>
	</article>
</div>
<{include file="common/footer.tpl"}>
