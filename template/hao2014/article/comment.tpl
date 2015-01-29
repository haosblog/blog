<{foreach from=$comment item=reRs}>
	<article class="cl box">
		<header class="fl">
			<div class="msg_head"><img src="/static/common/image/portrait/<{$reRs.portrait}>.jpg" /></div>
			<h4 class="center"><{$reRs.username}></h4>
		</header>
		<section>
			<header class="cl">
				<h5><{$reRs.title}></h5>
				<small class="fr"><{date('Y-m-d H:i:s', $reRs.time)}></small>
			</header>
			<p><{$reRs.content}></p>
		</section>
	</article>
<{/foreach}>