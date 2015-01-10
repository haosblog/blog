<{if !$smarty.const.IS_AJAX}>
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="<{$keywords|default:'小皓|独立博客|博客|技术博客|'}>" />
<meta name="Description" content="小皓的独立博客，记录技术，时事，还有生活|<{$kwcontent}>" />
<title><{$title}> - 小皓的blog</title>
<link rel="stylesheet" type="text/css" href="/static/hao2014/css/common.css" />
<link rel="stylesheet" media="screen and (min-width: 800px)" href="/static/hao2014/css/media_large.css" />
<link rel="stylesheet" media="screen and (max-width: 1000px)" href="/static/hao2014/css/media_1000px.css" />
<link rel="stylesheet" media="screen and (max-width: 799px)" href="/static/hao2014/css/media_800px.css" />
<link rel="stylesheet" media="screen and (max-width: 480px)" href="/static/hao2014/css/media_480px.css" />
</head>
<!--[if lt IE 8]><script type="text/javascript" language="javascript">document.execCommand("Stop");document.write('<iframe src="fuckie.html" width="100%" height="650" frameborder="0"></iframe>');</script><![endif]-->
<body>
<!--[if lt IE 9]>
<script>
alert("这个网站真难看");
alert("看，背景这么花，字却是白色的，看不清");
alert("有的地方还错位了");
alert("哎，为什么我看的不是这样呢");
alert("我这里看得很好看哦");
alert("哦，你用的是IE8");
alert("IE8不支持HTML5，不兼容CSS3");
alert("这么好看的效果你都看不到");
alert("看在你不是用IE6或者IE7的份上");
alert("奉劝你，跟上时代，换上一个现代化的浏览器");
alert("就看你不是原始人才这么说的");
alert("用IE6的远古人我是直接踢出去的");
alert("好啦，废话完了，看网站吧");
</script>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<div id="mainBox">
	<div id="main">
		<header>
			<div id="pagetitle">
				<button>导航</button>
				<h1><{$title}></h1>
			</div>
			<nav>
				<ul>
					<li><a href="/"><img _src="index" alt="首页" /><span>首页</span></a></li>
					<li><a href="/article"><img _src="article" alt="日志" /><span>日志</span></a></li>
					<li><a href="/album"><img _src="photo" alt="相册" /><span>相册</span></a></li>
					<li><a href="/mood"><img _src="mood" alt="微博" /><span>微博</span></a></li>
					<li><a href="/comment"><img _src="msg" alt="留言本" /><span>留言</span></a></li>
					<li><a href="/item"><img _src="item" alt="作品" /><span>作品</span></a></li>
					<li><a href="/intro"><img _src="intro" alt="简介" /><span>简介</span></a></li>
				</ul>
			</nav>
		</header>
        <div id="clock"> </div>
		<div id="body" class="box">
			<div id="ad_top">
				<!-- nuffnang -->
				<script type="text/javascript">
				nuffnang_bid = "747c9497e9b26a106d0486adf9c8d75e";
				</script>
				<script type="text/javascript" src="http://synad2.nuffnang.com.cn/k.js"></script>
				<!-- nuffnang-->
			</div>
			<div id="content">
<{else}>
<?xml version="1.0" encoding="gbk"?>
<root>
	<title><{$title}></title>
	<body><![CDATA[
<{/if}>