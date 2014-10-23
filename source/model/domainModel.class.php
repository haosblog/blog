<?php
/**
 * File: $(name)
 * Created on : 2013-12-16, 23:30:07
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 域名列表模型
 */

class domainModel extends model {
	public function loadList($wsid = 0, $page = 1, $pagecount = 20){
		$limit = $this->getLimit($page, $pagecount);

		$field = array(
			'd' => array('did', 'domain'),
			's' => array('wsid', 'sitename')
		);

		$tables = array('d' => 'domain', 's' => 'website');

		$where = array(
			'd.`wsid`' => 's.`wsid`'
		);
		if($wsid > 0){
			$where['s.`wsid`'] = $wsid;
		}

		return $this->selectJoin($tables, $field, $where, '', $limit);
	}

	public function addList($wsid, $domainArr){
		$data = array(
			'field' => array('wsid', 'domain'),
			'data' => array()
		);

		foreach ($domainArr as $domain){
			if(empty($domain)){
				continue;
			}

			$domain = addslashes($domain);
			$data['data'][] = array(
				$wsid,
				$domain
			);
		}

		$this->insertList($data);
	}
}