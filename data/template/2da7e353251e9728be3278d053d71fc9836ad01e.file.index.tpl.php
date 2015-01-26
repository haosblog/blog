<?php /* Smarty version Smarty-3.1.15, created on 2014-11-24 11:59:07
         compiled from "D:\www\blog\template\admin\login\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:54545448e23cde1a71-31304471%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2da7e353251e9728be3278d053d71fc9836ad01e' => 
    array (
      0 => 'D:\\www\\blog\\template\\admin\\login\\index.tpl',
      1 => 1414147926,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '54545448e23cde1a71-31304471',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.15',
  'unifunc' => 'content_5448e23d667988_50317993',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5448e23d667988_50317993')) {function content_5448e23d667988_50317993($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("../header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array('title'=>"后台登陆"), 0);?>

<div class="container">
	<form class="form-signin" method="post" role="form" action="">
		<input type="hidden" value="1" name="loginmode" />
		<h2 class="form-signin-heading">博客后台登陆</h2>
		<input type="text" name="username" class="form-control" placeholder="请输入账号" required="required" autofocus="autofocus" />
		<input type="password" name="password" class="form-control" placeholder="请输入密码" required="required"  />
		<label class="checkbox">
			<input type="checkbox" value="remember-me"> 记住密码
		</label>
		<button class="btn btn-lg btn-primary btn-block" type="submit">登陆</button>
	</form>
</div>
<?php echo $_smarty_tpl->getSubTemplate ("../footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, null, array(), 0);?>
<?php }} ?>
