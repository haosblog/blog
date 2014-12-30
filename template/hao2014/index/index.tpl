<{include file="common/header.tpl"}>
<article id="arc_list" class="indexbox box">
	<header>
		<b class="count fr">阅/评</b>
		<b class="timer fr">时间</b>
		<b class="title">标题</b>
	</header>
	<ul>
		<{model model="article" orderby="wrtime" count="8"}>
			<li class="cl">
				<i class="fr">{%$articleRs.read%}/{%$articleRs.review%}</i>
				<span class="fr"><{$row.wrtime}></span>
				<span>
					[<a href="?mod=article_list&cid={%$articleRs.cid%}"><{$row.catname}></a>]
					<em class="nobr"><a href="?mod=article_read&id={%$articleRs.id%}" title="{%$articleRs.title%}"><{$row.title}></a></em>
				</span>
			</li>
		<{/model}>
	</ul>
	<a href="?mod=article_list" class="more fr">更多...</a>
</article>

<article id="mood">
	<div class="dialog">
		<{model model="mood" orderby="wrtime" count="1"}>
			<p><{$row['content']}></p>
			<div><img src="<{$smarty.const.TPL_PATH}>/static/image/mood_dialog_arr.png" alt="" /></div>
		<{/model}>
	</div>
	<aside class="fr"><img src="<{$smarty.const.TPL_PATH}>/static/image/duola.png" /></aside>
</article>

<article id="album" class="indexbox box loop" _stop="2000" _speed="4000">
	<ul class="loopbox">
		<{album count="0"}>
			<li class="fl">
				<a href="?mod=album_view&aid={%$albumRs.id%}">
					<img src="<{$row.cover}>" alt="<{$row.name}>" />
				</a>
				<p class="nobr"><a href="?mod=album_view&aid=<{$row.aid}>"><{$row.name}></a></p>
			</li>
		<{/album}>
	</ul>
</article>
<article id="msgbox" class="indexbox box">
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
</article>
<article id="friendlink" class="indexbox box">
	<header class="fl">友情链接：</header>
	<div class="fr loop" _stop="1500" _speed="1500">
		<ul class="loopbox">
			<{friendlink count="0"}>
				<li class="fl"><a href="http://<{$row.url}>" title="<{$row.content}>" target="_blank"><{$row.name}></a></li>
			<{/friendlink}>
		</ul>
	</div>
</article>
<style type="text/css">
#ad_top { display: none; }
#body { height: 985px; }
#search{ top: 149px; left: 360px; }
</style>
<{include file="common/footer.tpl"}>