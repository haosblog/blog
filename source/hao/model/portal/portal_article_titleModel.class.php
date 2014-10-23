<?php
/**
 * File: portal_article_titleModel
 * Created on : 2014-1-30, 18:05:06
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class portal_article_titleModel extends model{

	public $tableObj = null;

	public function getList($orderby = '', $limit = '', $where = ''){
		$query = $this->getQuery($orderby, $limit, $where);
		while($row = $this->fetch($query)){
			$row['time'] = dgmdate($row['dateline']);
			$row['pic'] = !empty($row['pic']) ? $_G['siteurl']. 'data/attachment/portal/'. $row['pic'] : '';

			$list[] = $row;
		}
	}

	public function getListBetweenTime($starttime, $endtime){
	}

	public function getQuery($orderby = '', $limit = '', $where = ''){
		global $_G;
		$field = array(
			'a' => array('aid', 'title', 'summary', 'pic', 'dateline'),
			'ac' => array('viewnum', 'commentnum'),
			'c' => array('catname', 'catid')
		);
		$sqlwhere =  "a.`catid`=c.`catid` AND ac.`aid` =a.`aid`";
		if(!empty($where)){
			$sqlwhere .= ' AND '. $where;
		}
		$tables = array(
			'a' => $this->modelname,
			'ac' => 'portal_article_count',
			'c' => 'portal_category'
		);

		$sql = $this->parseSQLJoin($tables, $field, $sqlwhere, $orderby, $limit);
		return $this->query($sql);
	}

	public function getDataByID($aid){
		$where = array('aid' => $aid);
		return $this->selectOne('', $where);
	}


	public function category_get_wheresql($cat){
		$wheresql = '';
		if(is_array($cat)) {
			$catid = $cat['catid'];
			if(!empty($cat['subs'])) {
				include_once libfile('function/portalcp');
				$subcatids = category_get_childids('portal', $catid);
				$subcatids[] = $catid;

				$wheresql = "at.catid IN (".dimplode($subcatids).")";
			} else {
				$wheresql = "at.catid='$catid'";
			}
		}
		$wheresql .= " AND at.status='0'";
		return $wheresql;
	}

	public function categoryGetList($wheresql, $page, $pageCount){
		$start = max(0, ($page-1)*$pageCount);
		$list = array();
		$pricount = 0;
		$count = $this->tableObj->fetch_all_by_sql($wheresql, '', 0, 0, 1, 'at');
		if($count) {
			$query = $this->tableObj->fetch_all_by_sql($wheresql, 'ORDER BY at.dateline DESC', $start, $pageCount, 0, 'at');
			foreach($query as $value) {
				$value['catname'] = $value['catid'] == $cat['catid'] ? $cat['catname'] : $_G['cache']['portalcategory'][$value['catid']]['catname'];
				$value['onerror'] = '';
				if($value['pic']) {
					$value['pic'] = pic_get($value['pic'], '', $value['thumb'], $value['remote'], 1, 1);
				} else {
					$value['pic'] = 'none';
				}
				$value['dateline'] = dgmdate($value['dateline']);
				if($value['status'] == 0 || $value['uid'] == $_G['uid'] || $_G['adminid'] == 1) {
					unset($value['idType']);
					unset($value['id']);

					$list[] = $value;
				} else {
					$pricount++;
				}
			}
			if(strpos($cat['caturl'], 'portal.php') === false) {
				$cat['caturl'] .= 'index.php';
			}
		}
		return $return = array('list'=>$list, 'count'=>$count, 'pricount'=>$pricount);
	}

	public function getArticleByID($aid){
		return $this->tableObj->fetch($aid);
	}

	public function getPerNextArticle(&$article) {
		$data = array();
		$aids = array();
		if($article['preaid']) {
			$aids[$article['preaid']] = $article['preaid'];
		}
		if($article['nextaid']) {
			$aids[$article['nextaid']] = $article['nextaid'];
		}
		if($aids) {
			$data = $this->tableObj->fetch_all($aids);
			foreach ($data as $aid => &$value) {
				$value['url'] = fetch_article_url($value);
			}
		}
		if($data[$article['preaid']]) {
			$article['prearticle'] = $data[$article['preaid']];
		}
		if($data[$article['nextaid']]) {
			$article['nextarticle'] = $data[$article['nextaid']];
		}
	}
}
