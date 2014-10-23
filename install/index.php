<?php
error_reporting(0);
if(file_exists(dirname(__FILE__) .'/install.lock')){
	die('安装程序已被禁用，如果要重新安装，请删除intall文件夹下的install.lock文件');
}
$state = intval($_GET['state']);
$stateTempArr = array('copyright', 'check', 'input', 'install', 'module', 'success');
$titleArr = array('欢迎安装haosblog', '权限检查', '输入参数', '安装数据', '安装系统模型', '安装成功');

if($state < 0 && $state >= count($stateTempArr)){
	die('参数错误');
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title><?php echo($titleArr[$state]); ?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<?php
require 'template/'. $stateTempArr[$state] .'.inc.php';
?>
</body>
</html>