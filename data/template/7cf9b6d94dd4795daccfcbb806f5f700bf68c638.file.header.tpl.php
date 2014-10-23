<?php /* Smarty version Smarty-3.1.15, created on 2014-10-23 13:10:53
         compiled from "D:\www\blog\template\admin\header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16475448e23d815482-84867524%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7cf9b6d94dd4795daccfcbb806f5f700bf68c638' => 
    array (
      0 => 'D:\\www\\blog\\template\\admin\\header.tpl',
      1 => 1388499668,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16475448e23d815482-84867524',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'title' => 0,
    'loadcss' => 0,
    'loadjs' => 0,
    'model' => 0,
    'modelRow' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_5448e23d8d8981_73023034',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5448e23d8d8981_73023034')) {function content_5448e23d8d8981_73023034($_smarty_tpl) {?><!DOCTYPE html>
<html>
	<head>
		<title><?php echo $_smarty_tpl->tpl_vars['title']->value;?>
</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link href="/static/css/bootstrap.css" rel="stylesheet">
		<link href="/static/css/bootstrap-theme.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css"  href="/template/admin/static/css/admin.css" />
		<?php echo loadCSS($_smarty_tpl->tpl_vars['loadcss']->value);?>

		<script type="text/javascript" src="/static/js/jquery.js"></script>
		<script type="text/javascript" src="/static/js/bootstrap.min.js"></script>
		<?php echo loadJS($_smarty_tpl->tpl_vars['loadjs']->value);?>

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
				<?php  $_smarty_tpl->tpl_vars['modelRow'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['modelRow']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['model']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['modelRow']->key => $_smarty_tpl->tpl_vars['modelRow']->value) {
$_smarty_tpl->tpl_vars['modelRow']->_loop = true;
?>
					<a href="#<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['tablename'];?>
" class="list-group-item menu-first collapsed" data-toggle="collapse"><?php echo $_smarty_tpl->tpl_vars['modelRow']->value['modname'];?>
管理</a>
					<ul id="<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['tablename'];?>
" class="nav nav-list collapse menu-second">
						<li><a href="/admin/model/post?mid=<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['mid'];?>
">发表<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['modname'];?>
</a></li>
						<?php if ($_smarty_tpl->tpl_vars['modelRow']->value['classable']==1) {?>
							<li><a href="/admin/category?mid=<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['mid'];?>
"><?php echo $_smarty_tpl->tpl_vars['modelRow']->value['modname'];?>
分类</a></li>
						<?php }?>
						<li><a href="/admin/model/contentlist?mid=<?php echo $_smarty_tpl->tpl_vars['modelRow']->value['mid'];?>
"><?php echo $_smarty_tpl->tpl_vars['modelRow']->value['modname'];?>
列表</a></li>
					</ul>
				<?php } ?>
			</nav>
			<div id="body"><?php }} ?>
