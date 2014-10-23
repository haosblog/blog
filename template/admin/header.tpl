<!DOCTYPE html>
<html>
	<head>
		<title><{$title}></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="/static/css/bootstrap.css" rel="stylesheet">
		<link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"  href="/template/admin/static/css/admin.css" />
		<{$loadcss|loadCSS}>
		<script type="text/javascript" src="/static/js/jquery.js"></script>
		<script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
		<{$loadjs|loadJS}>
	</head>
	<body>
		<header>
			<h1>hao博客后台管理</h1>
			<div class="headernav">
				<ul>
					<li><a href="/admin/"><span class="glyphicon glyphicon-home"></span> 后台首页</a></li>
					<li><a href="/admin/index/logout">退出登录</a></li>
				</ul>
			</div>
		</header>
		<div id="main">
			<nav>
				<a class="list-group-item menu-first collapsed" href="/admin/">后台首页</a>
				<a href="#siteMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">站点管理</a>
				<ul id="siteMenu" class="nav nav-list collapse menu-second">
					<li><a href="/admin/site/add">添加站点</a></li>
					<li><a href="/admin/site">站点列表</a></li>
					<li><a href="/admin/site/domain">域名列表</a></li>
				</ul>
				<a href="#modelMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">系统模型管理</a>
				<ul id="modelMenu" class="nav nav-list collapse menu-second">
					<li><a href="/admin/model/">模型列表</a></li>
					<li><a href="/admin/model/import/">导入模型</a></li>
				</ul>
				<a href="#photoMenu" class="list-group-item menu-first collapsed" data-toggle="collapse">相片管理</a>
				<ul id="photoMenu" class="nav nav-list collapse menu-second">
					<li><a href="/admin/photo/album">相册管理</a></li>
					<li><a href="/admin/photo/">图片管理</a></li>
					<li><a href="/admin/photo/upload">上传图片</a></li>
				</ul>
				<{foreach from=$model item=modelRow}>
					<a href="#<{$modelRow.tablename}>" class="list-group-item menu-first collapsed" data-toggle="collapse"><{$modelRow.modname}>管理</a>
					<ul id="<{$modelRow.tablename}>" class="nav nav-list collapse menu-second">
						<li><a href="/admin/model/post?mid=<{$modelRow.mid}>">发表<{$modelRow.modname}></a></li>
						<{if $modelRow.classable eq 1}>
							<li><a href="/admin/category?mid=<{$modelRow.mid}>"><{$modelRow.modname}>分类</a></li>
						<{/if}>
						<li><a href="/admin/model/contentlist?mid=<{$modelRow.mid}>"><{$modelRow.modname}>列表</a></li>
					</ul>
				<{/foreach}>
			</nav>
			<div id="body">