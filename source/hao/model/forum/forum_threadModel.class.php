<?php
/**
 * File: forum_threadModel.class.php
 * Created on : 2014-2-24, 23:45:09
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 帖子主题模型
 */
class forum_threadModel extends model{

	public $tableObj = null;

	/**
	 *
	 * @param type $starttime
	 * @param type $endtime
	 * @return type
	 */
	public function getListBetweenTime($starttime, $endtime){
		$field = array(
			'f' => array('name'),
			't' => array('fid', 'tid', 'author', 'authorid', 'subject', 'dateline', 'lastpost', 'views', 'replies', 'closed')
		);
		$sqlfield = $this->parseFieldJoin($field);
		$sql = "SELECT $sqlfield FROM {$this->tablename} AS t, ". DB::table('forum_forum') ." AS f"
				. " WHERE t.`fid`=f.`fid` AND t.`lastpost` BETWEEN '$endtime' AND '$starttime' "
				. "ORDER BY t.`lastpost` DESC";
		$query = $this->query($sql);
		$return = array();
		while ($row = $this->fetch($query)) {
			$row['url'] = 'forum.php?mod=viewthread&tid='. $row['tid'];
			$row['lastpost'] = dgmdate($row['lastpost']);
			$row['time'] = dgmdate($row['dateline'], 'u');
			$return[$row['tid']] = $row;
		}
		return $return;
	}

	public function getQueryByFid($fid, $orderBy, $limit){
		$where = array('fid' => $fid);
		return $this->queryParse('', $where, $orderBy, $limit);
	}

	/**
	 * 获取主题的首贴内容
	 *
	 * @param type $tid
	 */
	public function getThreadInfo($tid){
		$where = array(
			'p.tid' => 't.tid',
			'p.first' => 1,
			'p.tid' => $tid
		);
		$tables = array(
			'p' => 'forum_post',
			't' => 'forum_thread'
		);

		$sql = $this->parseSQLJoin($tables, '', $where);
		return $this->fetchOne($sql);
	}


	public function updateView($tid){
		$data = array(
			'views' => '+1'
		);
		return $this->_updateByTid($data, $tid);
	}

	public function updateReply($tid){
		$data = array(
			'replies' => '+1'
		);
		return $this->_updateByTid($data, $tid);
	}

	/**
	 * 根据tid更新数据
	 *
	 * @param type $data
	 * @param type $tid
	 * @return type
	 */
	private function _updateByTid($data, $tid){
		$where = array(
			'tid' => $tid
		);
		return $this->update($data, $where);
	}
}
