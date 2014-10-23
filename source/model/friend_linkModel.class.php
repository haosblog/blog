<?php

/**
 * File: friend_linkModel
 * Created on : 2014-1-3, 1:16:08
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class friend_linkModel extends model {

	public function loadList($selectall = false, $wsid = false){
		$wsid = $this->getWsid($wsid);

		if(!$selectall){
			$where['pass'] = 1;
		}

		if($wsid != 0){
			$where['wsid'] = $wsid;
		}

		return $this->select('', $where);
	}


	public function pass($id){
		$maps = array('pass' => 1);
		$where = array('id' => $id);

		$this->update($maps, $where);
	}
}