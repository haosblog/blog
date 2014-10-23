<?php
/**
 * File: forum_postModel.class.php
 * Created on : 2014-2-23, 13:48:28
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 论坛帖子模型
 */
class forum_postModel extends model {

	public $tableObj = null;

	public function getListByTidsAndBetweenTime($starttime, $endtime, $tids){
		$field = array('pid', 'tid', 'first', 'author', 'authorid', 'subject', 'dateline', 'message');
		$where = " `dateline` BETWEEN '$endtime' AND '$starttime'  OR "
				. "(". DB::field('tid', $tids) ." AND `first`='1')";
		$query = $this->queryParse($field, $where);
		return $query;
	}

	/**
	 * 获取主题首贴的内容
	 */
	public function getThreadSummary($tid){
		$field = array('message', 'dateline');
		$where = array(
			'tid' => $tid,
			'first' => 1
		);
		$where = $this->selectOne($field, $where);
	}
}
