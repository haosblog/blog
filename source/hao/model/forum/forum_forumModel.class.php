<?php

/**
 * File: forum_forumModel
 * Created on : 2014-2-3, 0:47:36
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class forum_forumModel extends model {

	public $tableObj = null;

	public function loadAll(){
		return $this->tableObj->fetch_all_by_status(1);
	}

	public function getForumByFup($fup = 0){
		$type = empty($fup) ? 'group' : '';
		return $this->tableObj->fetch_all_fids(0, $type, $fup);
	}

	/**
	 * 生成可索引的板块名列表
	 */
	public function getListIndex(){
		$field = array('fid', 'name');
		$sql = $this->parseSQL($field);
		$query = $this->query($sql);
		$return = array();
		while($row = $this->fetch($query)){
			$return[$row['fid']] = $row['name'];
		}

		return $return;
	}
}
