<?php
function checkPath($file_path)
{
	$file = @fopen($file_path . '/text.txt', 'w');
	if(!$file){
		$result = false;
		@fclose($file);
	} else {
		@fclose($file);
		@unlink($file_path . '/text.txt');
		$result = true;
	}
	
	
	return $result;
}
$rootPath = dirname(dirname(dirname(__FILE__)));
$configAble = checkPath($rootPath .'/config');
$dataAble = checkPath($rootPath .'/data');
$success = $configAble && $dataAble;
?>
为了安装程序能够成功进行，请将以下两个文件夹权限设为可读写<br />
<font color="<?php echo($configAble ? 'green' : 'red'); ?>">config</font><br />
<font color="<?php echo($dataAble ? 'green' : 'red'); ?>">data</font><br />
<?php
if($success){
	echo('恭喜你，权限设置正确，安装程序可以<a href="index.php?state=2">继续</a>运行');
} else {
	echo('权限设置不正确，请检查红色标示目录是否成功设置，设置完成请<a href="index.php?state=1">刷新本页</a>');
}
?>
<br />安全起见，可在完成安装后将config文件夹的权限设置为不可写
<a href="index.php?state=2">下一步</a>