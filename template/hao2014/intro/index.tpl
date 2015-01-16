<{include file="common/header.tpl" title="简介"}>
	<article id="intro">
		<small class="fr">发表时间：<{$info.time }></small>
		<{if $info.img}>
			<div class="center"><img src="<{$info.img}>" id="photo" /></div>
		<{/if}>
		<p><{$info.content}></p>
	</article>
<{include file="common/footer.tpl"}>