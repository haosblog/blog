<!DOCTYPE html>
<html>
	<head>
		<title><{$title}></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="/static/common/css/bootstrap.css" rel="stylesheet">
		<link href="/static/common/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="/static/common/css/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"  href="/static/admin/css/admin.css" />
		<{$loadcss|loadCSS}>
		<script type="text/javascript" src="/static/common/js/jquery.js"></script>
		<script type="text/javascript" src="/static/common/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/static/common/js/cookie.js"></script>
		<script type="text/javascript" src="/static/admin/js/common.js"></script>
		<{$loadjs|loadJS}>
	</head>
	<body>
		<header>
			<h1>hao博客后台管理</h1>
			<div class="headernav">
				<ul>
					<li><a href="/admin/"><span class="glyphicon glyphicon-home"></span> 后台首页</a></li>
					<li><a href="/admin/index/logout"><span class="glyphicon glyphicon-share"></span> 退出登录</a></li>
				</ul>
			</div>
		</header>
		<div id="main">
			<{include file="nav.tpl"}>
			<div id="body">