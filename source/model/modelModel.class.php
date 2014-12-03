<?php

/**
 * File: $(name)
 * Created on : 2013-12-14, 11:38:31
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 系统模型模型
 */
class modelModel extends model {
	var $m_model_field;

	function __construct() {
		$this->m_model_field = M('model_field');
		parent::__construct();
	}



	/**
	 * 由JSON导入一个新模型
	 *
	 * @param type $modelArr	由导入的JSON数据生成数组
	 * @return type
	 */
	public function import($modelArr){
		$modelData = $modelArr['info'];

		$mid = $this->insert($modelData, true);

		$fieldArr = $this->makeTable($mid, $modelArr);
		$this->m_model_field->insertList($fieldArr);

		return $mid;;
	}


	public function makeTable($mid, $modelArr){
		$modelData = $modelArr['info'];
		$fieldArr = $modelArr['field'];
		$tableFieldArr = array();

		$fieldData = $this->parseFieldData($mid, $fieldArr, $tableFieldArr);
		$tableSql = "CREATE TABLE `{$this->dbpre}mod_{$modelData['tablename']}` (".
				"`id` MEDIUMINT(8) NOT NULL AUTO_INCREMENT,".
				($modelData['classable'] == 1 ? "`cid` MEDIUMINT(5) NOT NULL DEFAULT '0'," : '').
				implode(',', $tableFieldArr) .",".
				"`wsid` SMALLINT(3) NOT NULL,".
				"PRIMARY KEY (`id`)".
				") ENGINE=MYISAM DEFAULT CHARSET=utf8;";

		$this->query($tableSql);

		return $fieldData;
	}

	private function parseFieldData($mid, $fieldArr, &$tableFieldArr){
		$fieldData = array();
		$typeArr = array('VARCHAR', 'CHAR', 'TEXT', 'MEDIUMTEXT', 'LOGNTEXT', 'TINYINT', 'SMALLINT', 'INT',
			'BIGINT', 'FLOAT', 'DOUBLE', 'INT(10)', 'TINYINT(1)');

		foreach($fieldArr as $key => $item){
			$tmparr = array(
				'mid' => $mid,
				'fieldname' => $key,
				'viewname' => $item['viewname'],
				'fieldtype' => $item['fieldtype'],
				'length' => $item['length'],
				'formtype' => $item['formtype'],
				'allownull' => $item['allownull'],
				'default' => $item['default'],
				'listview' => $item['listview'],
				'allowhtml' => $item['allowhtml'],
			);
			$fieldData[] = $tmparr;

			$tmpsql = $key .' '. $typeArr[$item['fieldtype']];
			$tmpsql .= isset($item['length']) && $item['length'] > 0 ? '('. $item['length'] .')' : '';
			$tmpsql .= !$item['allownull'] ? ' NOT NULL' : '';
			$tableFieldArr[] = $tmpsql;
		}

		return $fieldData;
	}

}