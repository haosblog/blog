<{include file="common/header.tpl"}>
<div id="album_view_top">
	<div id="album_left">
		<{if $deny == 1}>
			<form action="index.php?mod=album_view&aid=<{$aid}>" method="post">
				请输入访问密码：密码提示（<strong><{$albumInfo.clew}></strong>）
				<input type="text" name="password" id="password" />
				<input type="submit" value="进入" />
			</form>
		<{else}>
			<div id="album_info" class="box"><strong><{$albumInfo.name}></strong>：<{$albumInfo.intro}></div>
			<div id="album_photo">
				<div><img src="img/<{$master}>/photo/<{$albumInfo.path}>/<{$photoInfo.path}>" onload="ImgAutoSize(this, 500, 300);" /></div>
			</div>
			<h1><{$photoInfo.title}></h1>
			<div id="album_photo_info" class="box"><{$photoInfo.intro}></div>
		<{/if}>
	</div>
	<div id="album_right">
		<div id="album_pl_bg" class="box">
			<{foreach from=$photoList item=photoRs}>
				<div class="album_pl_li">
					<a href="?mod=album_view&aid=<{$aid}>&no=<{$photoRs.no}>">
						<img src="<{$photoRs.url}>" width="150" height="100" alt="" onload="ImgAutoSize(this, 150, 100);" />
					</a>
				</div>
			<{foreachelse}>
				暂无
			<{/foreach}>
		</div>
	</div>
</div>
<{include file="common/footer.tpl"}>