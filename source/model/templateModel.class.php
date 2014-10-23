<?php
/**
 * File: $(name)
 * Created on : 2013-12-16, 1:09:08
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 模板模型
 */
class templateModel extends model {
	public function loadList(){
		return $this->select();
	}

	public function getPath($tpid){
		$tpid = intval($tpid);
		$field = array('path');
		$where = array('tpid' => $tpid);

		$tpdata = $this->selectOne($field, $where);

		if($tpdata) {
			return $tpdata['path'];
		} else {
			return false;
		}
	}
}