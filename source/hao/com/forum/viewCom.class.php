<?php

/**
 * File: viewCom.class.php
 * Created on : 2014-7-6, 11:23:44
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class viewCom extends component {

	public function threadList($fid = 0, $order = 'newreply', $page = 1, $pageCount = 20, $options = array()){
		$m_thread = M('forum/thread');
		$where = array();
		$limit = $this->getLimit($page, $pageCount);
		if($fid != 0){
			$where['tid'] = $tid;
		}
		$list = $m_thread->field('tid', 'fid', 'posttableid', 'typeid', 'sortid', 'author', 'authorid', 'subject', 'dateline', 'lastpost',
				'lastposter', 'views', 'replies', 'highlight', 'digest', 'attachment', 'recommends', 'recommend_add',
				'recommend_sub', 'heats', 'favtimes', 'sharetimes', 'stamp', 'icon', 'cover')
			->where(array($where))->scope($order)->limit($limit)->select();
		return $list;
	}
}
