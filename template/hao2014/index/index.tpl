<{include file="common/header.tpl"}>
<article id="arc_list" class="indexbox box cl">
	<table>
		<thead>
			<tr>
				<th class="title">标题</th>
				<th class="timer">时间</th>
				<th class="count">阅/评</th>
			</tr>
		</thead>
		<tbody>
			<{article count="8"}>
				<tr>
					<td class="title">
						<img src="/static/hao2014/image/m.gif" alt="" />
						[<a href="/article?cid=<{$row.cid}>"><{$row.catname}></a>]
						<em class="nobr"><a href="/article/read?aid=<{$row.aid}>" title="<{$row.title}>"><{$row.title}></a></em>
					</td>
					<td class="timer"><time><span><{date('Y-m-d H:i:s', $row.wrtime)}></time></td>
					<td class="count"><{$row.viewcount}>/<{$row.repostcount}></td>
				</tr>
			<{/article}>
		</tbody>
	</table>
	<a href="/article" class="more fr">更多...</a>
</article>

<article id="mood">
	<div class="dialog">
		<{model model="mood" orderby="dateline DESC" count="1"}>
			<p><{$row['content']}></p>
			<div><img src="/static/hao2014/image/mood_dialog_arr.png" alt="" /></div>
		<{/model}>
	</div>
	<aside class="fr"><img src="/static/hao2014/image/duola.png" /></aside>
</article>

<article id="album" class="indexbox box loop" _stop="2000" _speed="4000" data-min-screen="480">
	<ul class="loopbox">
		<{album count="0"}>
			<li class="fl">
				<a href="/album/view?aid=<{$row.aid}>">
					<img src="<{$row.cover}>" alt="<{$row.name}>" />
				</a>
				<p class="nobr"><a href="/album/view&aid=<{$row.aid}>"><{$row.name}></a></p>
			</li>
		<{/album}>
	</ul>
</article>
<{*<article id="msgbox" class="indexbox box">
	{%foreach from=$msg item=msgRs%}
		<article class="fl">
			<div class="msgface"><img src="pic/{%$msgRs.sex%}.jpg" alt="{%$msgRs.username%}" /></div>
			<div class="msgmain">
				<aside>
					<header>
						<span class="nobr">{%$msgRs.title%}</span>
						<small class="fr">{%$msgRs.time%}</small>
					</header>
					<p>{%$msgRs.content%}</p>
				</aside>
			</div>
		</article>
	{%/foreach%}
</article>*}>
<article id="friendlink" class="indexbox box cl">
	<header class="fl">友情链接：</header>
	<div class="fr loop" _stop="1500" _speed="1500" data-min-screen="800">
		<ul class="loopbox">
			<{friendlink count="0"}>
				<li class="fl"><a href="http://<{$row.url}>" title="<{$row.content}>" target="_blank"><{$row.name}></a></li>
			<{/friendlink}>
		</ul>
	</div>
</article>
<{include file="common/footer.tpl"}>