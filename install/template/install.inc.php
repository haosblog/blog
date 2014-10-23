<?php
//根据配置，生成config.php文件

$db_host = $_POST['tbhost'] ? $_POST['tbhost'] : 'localhost';
$db_user = $_POST['tbuser'];
$db_pswd = $_POST['tbpswd'];
$db_name = $_POST['tbname'];
$tb_pre = $_POST['tbpre'] ? $_POST['tbpre'] : 'hao';
$webroot = $_POST['root'] ? $_POST['root'] : '/';
if($db_user == '' || $db_pswd == '' || $db_name == ''){
	die('请输入数据库用户名/密码/数据库名'. $db_name);
}
$config = array(
	'dbhost' => $db_host,
	'dbuser' => $db_user,
	'dbpswd' => $db_pswd,
	'dbname' => $db_name,
	'tbpre' => $tb_pre,
	'webroot' => $webroot
);

$configCode = var_export($config, true);
$configFileContent = <<<EOF
<?php
session_start();
header("Content-type: text/html; charset=utf-8");

\$config = $configCode;
?>
EOF;

$username = htmlspecialchars($_POST['username']);
$password = $_POST['password'];
if($username == '' || $password == ''){
	die('账号密码不能为空');
}


$configFile = @fopen(dirname(dirname(dirname(__FILE__))) .'/config/config.php', 'w+');
@fwrite($configFile, $configFileContent);
@fclose($configFile);
require_once (dirname(dirname(dirname(__FILE__))) .'/source/core/db.class.php');
$db = new db($config);
@mysql_query("CREATE DATABASE IF NOT EXISTS `$db_name`");
echo('数据库创建成功！<br />');

$sqlStr = @file_get_contents('install.sql');
$sqlStr = str_replace('hao_', $tb_pre, $sqlStr);
$sqlArr = explode(';', $sqlStr);
foreach($sqlArr as $sql){
	$db->query($sql);
	echo(mysql_error() .'执行成功<br />');
}

$password = md5($password);
$userInfo = array('username' => $username, 'password' => $password, 'type' => '0');
$db->insert($db->table('user'), $userInfo);
echo('管理员账号创建成功！<br />');
?>
数据库安装完成<br />
<a href="index.php?state=4">下一步</a>