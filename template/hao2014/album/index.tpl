<{include file="common/header.tpl" title="相册"}>
<article id="album" class="list cl">
	<ul>
		<{foreach from=$list item=albumRs}>
			<li class="fl box">
				<a href="/album/view?aid=<{$albumRs.aid}>">
					<img src="<{$albumRs.imgSmall}>" alt="<{$albumRs.name}>" />
				</a>
				<p class="nobr"><a href="?mod=album_view&aid=<{$albumRs.id}>"><{$albumRs.name}></a></p>
			</li>
		<{foreachelse}>
			<li class="fl">暂无相册</li>
		<{/foreach}>
	</ul>
</article>
<{include file="common/footer.tpl"}>