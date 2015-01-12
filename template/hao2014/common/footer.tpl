<{if !$smarty.const.IS_AJAX}>
			</div>
			<div id="search">
				<form method="get" action="/article" id="cse-search-box" target="_blank">
			        <input type="text" name="q" id="q" size="20" />
					<select class="form">
						<option value="1">日志</option>
						<{*<option value="2">相册</option>*}>
					</select>
			        <input type="submit" name="sa" class="button" value="&#x641c;&#x7d22;" />
				</form>
			</div>
			<!--div id="music">
				<audio controls="controls" loop="loop">
				<source src="skin/v3.0/music/01.ogg" type="audio/ogg">
					<source src="skin/v3.0/music/01.mp3" type="audio/mpeg">
				</audio>
			</div-->
		</div>
		<footer>
			<div>
				<a href="http://www.alexa.com/siteinfo/www.haosblog.com" target="_blank"><script type='text/javascript' src='http://xslt.alexa.com/site_stats/js/t/c?url=www.haosblog.com'></script></a>
			</div>
			<span><a href="/contact">联系站长</a>&nbsp;|&nbsp;<a href="/friendlink">友情链接</a>&nbsp;|&nbsp;切换风格&nbsp;|&nbsp;<a href="/rss">订阅</a></span><br />
			<small>Copyright&nbsp;&copy;&nbsp;2008-2013&nbsp;<a href="http://www.haosblog.com">小皓</a>&nbsp;版权所有</small>
			<div>
				<script id="bdcount" type="text/javascript" src="http://hm.baidu.com/h.js?d9cdd5261037c4d0730fc27b40159740"></script>
				<script language="javascript" type="text/javascript" src="http://js.users.51.la/1920424.js"></script>
				<noscript><a href="http://www.51.la/?1920424" target="_blank"><img alt="&#x6211;&#x8981;&#x5566;&#x514D;&#x8D39;&#x7EDF;&#x8BA1;" src="http://img.users.51.la/1920424.asp" id="51count" /></a></noscript>
			</div>
			<div id="ad">
			</div>
		</footer>
	</div>
</div>
<div id="scroll">
	<div id="scrollBox"> </div>
</div>
<script type="text/javascript" language="javascript" src="/static/common/js/jquery.js"></script>
<script type="text/javascript" language="javascript" src="/static/hao2014/js/common.js"></script>
<script type="text/javascript" id="bdshare_js" data="type=button&amp;uid=368517" ></script>
<script type="text/javascript" id="bdshell_js"></script>
<script type="text/javascript">
document.getElementById("bdshell_js").src = "http://bdimg.share.baidu.com/static/js/shell_v2.js?cdnversion=" + Math.ceil(new Date()/3600000);	//此段代码放置在链接点击的部分
</script>
<!--div style="position:  fixed; top: 0; right: 0; width: 100px;">top</div-->
</body>
</html>
<{else}>
		]]></body>
	</root>
<{/if}>