<?php
/**
 * File: albumModel
 * Created on : 2013-12-18, 23:53:58
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 相册模型
 */

class albumModel extends model {

	public function loadList($page = 1, $pagecout = 10, $wsid = false){
		$wsid = $this->getWsid($wsid);

		$limit = $this->getLimit($page, $pagecount);
		$field = array('aid', 'name', 'password', 'clew');
		$where = $wsid == 0 ? '' : array('wsid' => $wsid);
		$orderby = ' `time` DESC';

		return $this->select($field, $where, $orderby, $limit);
	}

	public function loadSelectList($wsid = false){
		$wsid = $this->getWsid($wsid);
		$field = array('aid', 'name');
		$where = $wsid == 0 ? '' : array('wsid' => $wsid);
		$orderby = ' `time` DESC';

		$sql = $this->parseSQL($field, $where, $orderby);

		$return = array();

		$query = $this->query($sql);
		while ($row = $this->fetch($query)){
			$return[$row['aid']] = $row['name'];
		}

		return $return;
	}

	public function getNameById($id){
		$field = array('name');
		$where = array('aid' => $id);

		$data = $this->selectOne($field, $where);

		if($data){
			return $data['name'];
		} else {
			return false;
		}
	}
}