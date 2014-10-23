<?php
/**
 * File: categoryModel
 * Created on : 2013-12-31, 20:49:52
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 分类模型
 */

class categoryModel extends model {

	public function loadList($mid = 0, $wsid = false){
		$wsid = $this->getWsid($wsid);

		$field = array('cid', 'catname', 'count');
		if($mid != 0){
			$where['mid'] = $mid;
		}

		if($wsid != 0){
			$where['wsid'] = $wsid;
		}

		return $this->select($field, $where);
	}
}