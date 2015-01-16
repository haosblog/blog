<{include file="head.html" title="简介"}>
	<article id="intro">
		<small class="fr">发表时间：<{$time }></small>
		<{if $img}>
			<div class="center"><img src="<{$img}>" id="photo" /></div>
		<{/if}>
		<p><{$content}></p>
	</article>
<{include file="foot.html"}>