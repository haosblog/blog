<?php
require_once (dirname(dirname(dirname(__FILE__))) .'/config/config.php');
require_once (dirname(dirname(dirname(__FILE__))) .'/source/core/db.class.php');
$db = new db($config);

$fileNameList = $_POST['module'];
if(is_array($fileNameList)){
	foreach($fileNameList as $fileName){
		$json = file_get_contents('./module/'. $fileName);
		$module = json_decode($json, true);
		$moduleInfo = $module['info'];
		$moduleField = $module['field'];
		$tableName = $config['tbpre'] .'mod_'. $moduleInfo['tablename'];

		$moduleId = $db->insert($db->table('module'), $moduleInfo, true);

		$tableSql = "DROP TABLE IF EXISTS `{$tableName}`";
		$tableSql = "CREATE TABLE `{$tableName}`(".
					"id MEDIUMINT(8) NOT NULL AUTO_INCREMENT,";	//生成表格的SQL代码
		if($moduleInfo['classable']){
			$tableSql .= "cid MEDIUMINT(5) NOT NULL AUTO_INCREMENT,";
		}
		foreach($moduleField as $fieldKey => $fieldValue){
			$fieldData = $fieldValue;
			$fieldData['fieldname'] = $fieldKey;
			$fieldData['mid'] = $moduleId;

			$db->insert($db->table('module_field'), $fieldData);

			$tableSql .= "`$fieldKey` $fieldValue[fieldtype]";
			if(intval($fieldValue['length']) > 0){ $tableSql .= "($fieldValue[length])"; }
			if(isset($fieldValue['default'])){ $tableSql .= " DEFAULT '$fieldValue[default]'"; }
			$tableSql .= ",";
		}

		$tableSql .= 'PRIMARY KEY(`id`)) ENGINE=MYISAM DEFAULT CHARSET=utf8';
		$db->query($tableSql);
		echo($moduleInfo['modname'] .'系统模型安装成功<br />');
	}

	echo('安装完成，请删除install文件夹');
}
?>