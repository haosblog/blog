<?php
/**
 * File: $(name)
 * Created on : 2013-12-15, 23:03:21
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class photoModel extends model {
	public function loadList($aid = 0, $page = 1, $pagecout = 30, $wsid = false){
		$wsid = $this->getWsid($wsid);
		$limit = $this->getLimit($page, $pagecount);
		$field = array(
			'p' => array('pid', 'aid', 'title', 'path', 'time', 'sourcetext', 'sourceurl'),
			'a' => array('aid', 'name')
		);
		$where = array();

		if($aid != 0){
			$where['p.`aid`'] = intval($aid);
		}

		$tables = "`@_@album` AS a RIGHT JOIN `@_@photo` AS p ON a.`aid` = p.`aid`";

		return $this->selectJoin($tables, $field, $where, '', $limit);
	}
}