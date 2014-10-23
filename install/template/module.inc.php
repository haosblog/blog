<h2>选择需要安装的系统模型</h2>
<form action="index.php?state=5" method="post">
<?php
//读取module文件夹，列出所有文件
$dir = './module/';
$handle = @opendir($dir) or die('模型安装文件被损坏，你可以直接<a href="index.php?state=5">跳过</a>此步骤，以后手工导入系统模型');

// 用 readdir 读出文件列表
while(false !== ($file = readdir($handle))){
    // 将 "." 及 ".." 排除不显示
    if($file != '.' && $file != '..' && !is_dir('./module/'. $file) && strpos($file, '.json') > 0){
    	$json = file_get_contents('./module/'. $file);
    	$moduleInfo = json_decode($json, true);
    	$moduleName = $moduleInfo['info']['modname'];
?>
	<input type="checkbox" checked="checked" name="module[]" id="<?php echo($file); ?>" value="<?php echo($file); ?>" />
	<label for="<?php echo($file); ?>"><?php echo($moduleName); ?></label>&nbsp;&nbsp;
<?php
    }
}
// 关闭目录读取
closedir($handle);
?><br />
<button type="submit" name="submit" valign="1">安装</button>&nbsp;&nbsp;<a href="index.php?state=5">跳过</a>以后手工安装
</form>