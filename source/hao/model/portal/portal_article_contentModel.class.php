<?php

/**
 * File: portal_article_contentModel
 * Created on : 2014-2-1, 0:06:13
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class portal_article_contentModel extends model {

	public $tableObj = null;

	public function getDataByID($aid, $page = 1){
		$content = $this->tableObj->fetch_by_aid_page($aid, $page);
		//TODO 此处暂时注释掉，未来需要使用分页的时候重新启用
		//$content['maxPage'] = $this->tableObj->fetch_max_pageorder_by_aid($aid);

		return $content;
	}

	public function getContentByID($aid, $page = 1){
		$field = 'content';
		$where = array('aid' => $aid);
		$orderby  = 'pageorder';
		$limit = array($page - 1, 1);
		$data = $this->select($field, $where, $orderby, $limit);

		return $data ? $data[0]['content'] : false;
	}
}
