<?php
$script_name = $_SERVER['SCRIPT_NAME'];
$rootPath = substr($script_name, 0, strpos($script_name, 'install'));
?>
请输入以下信息，以便安装程序完成
<form method="post" action="index.php?state=3">
	<p><input name="root" type="text" id="root" value="<?php echo($rootPath); ?>" />网站根目录</p>
	<p>&nbsp;</p>
	<p><input name="tbhost" type="text" id="tbhost" value="localhost" />数据库服务器</p>
	<p><input name="tbuser" type="text" id="tbuser" />数据库账号</p>
	<p><input name="tbpswd" type="text" id="tbpswd" value="" />数据库密码</p>
	<p><input name="tbname" type="text" id="tbname" />数据库名（已存在则覆盖）</p>
	<p><input name="tbpre" type="text" id="tbpre" value="hao_" />表名前缀</p>
	<p>&nbsp;</p>
	<p><input name="username" type="text" id="username" />管理员账号</p>
	<p><input name="password" type="text" id="password" />管理员密码</p>
	<p><input name="submit" type="submit" id="submit" value="提交" /></p>
</form>