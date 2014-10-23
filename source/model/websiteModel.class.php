<?php
/**
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class websiteModel extends model {

	/**
	 * 加载默认站点信息
	 *
	 * @return type
	 */
	public function loadDefault(){
		$sql = "SELECT w.* FROM @_@website AS w, @_@domain AS d
			WHERE w.wsid=d.wsid AND d.domain='{$GLOBALS['host']}' OR w.isdefault='1' ORDER BY w.isdefault";
		$website = $this->fetchOne($sql);
		if(!$website){
			showerror('错误，没有默认站点，请检查网站配置');
		}

		return $website;
	}

	public function updateData($param){
		$wsid = $param['wsid'];

		if($wsid == 0){
			$wsid = $this->insert($param, true);
		} else {
			$where = array('wsid' => $wsid);
			$this->update($data, $where);
		}

		if($param['default'] == 1){
			$update = array('default' => '0');
			$where = " `wsid`<>'$wsid'";
			$this->update($update, $where);
		}

		return $wsid;
	}

	public function get($id){
		if(is_numeric($id)){
			$where = array('wsid' => $id);
			$website = $this->selectOne('*', $where);
			$domainList = M('domain')->getlist();
			$website['domain'] = $domainList;

			return $website;
		}

		return false;
	}

	public function loadList($page, $pagecount){
		$field = array('wsid', 'sitename', 'isdefault', 'type', 'tpid');
		$limit = $this->getLimit($page, $pagecount);
		return $this->select($field, '', '', $limit);
	}
}