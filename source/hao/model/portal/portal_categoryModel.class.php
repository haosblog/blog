<?php

/**
 * File: portal_categoryModel
 * Created on : 2014-1-23, 0:29:30
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class portal_categoryModel extends model{

	public $tableObj = null;

	public function loadList($upid = 0){
		$where = array();
		if($upid !== false){
			$where = array('upid' => $upid);
		}

		return $this->select('', $where, 'displayorder DESC');
	}

	public function getNameByID($catID){
		$where = array('catid' => $catID);
		$field = array('catname');

		$data = $this->selectOne($field, $where);
		return $data['catname'];
	}
}
