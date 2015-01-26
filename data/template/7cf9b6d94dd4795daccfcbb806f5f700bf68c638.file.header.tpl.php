<?php /* Smarty version Smarty-3.1.15, created on 2015-01-08 12:58:14
         compiled from "D:\www\blog\template\admin\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16475448e23d815482-84867524%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7cf9b6d94dd4795daccfcbb806f5f700bf68c638' => 
    array (
      0 => 'D:\\www\\blog\\template\\admin\\header.tpl',
      1 => 1420682167,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16475448e23d815482-84867524',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_5448e23d8d8981_73023034',
  'variables' => 
  array (
    'title' => 0,
    'loadcss' => 0,
    'loadjs' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5448e23d8d8981_73023034')) {function content_5448e23d8d8981_73023034($_smarty_tpl) {?><!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="/static/common/css/bootstrap.css" rel="stylesheet">
		<link href="/static/common/css/bootstrap-theme.min.css" rel="stylesheet">
		<link href="/static/common/css/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"  href="/static/admin/css/admin.css" />
		<?php echo loadCSS($_smarty_tpl->tpl_vars['loadcss']->value);?>

		<script type="text/javascript" src="/static/common/js/jquery.js"></script>
		<script type="text/javascript" src="/static/common/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/static/common/js/cookie.js"></script>
		<script type="text/javascript" src="/static/admin/js/common.js"></script>
		<?php echo loadJS($_smarty_tpl->tpl_vars['loadjs']->value);?>

	</head>
	<body>
		<header>
			<h1>hao博客后台管理</h1>
			<div class="headernav">
				<ul>
					<li><a href="/admin/"><span class="glyphicon glyphicon-home"></span> 后台首页</a></li>
					<li><a href="/admin/logout"><span class="glyphicon glyphicon-share"></span> 退出登录</a></li>
				</ul>
			</div>
		</header>
		<div id="main">
			<?php echo $_smarty_tpl->getSubTemplate ("nav.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>

			<div id="body"><?php }} ?>
