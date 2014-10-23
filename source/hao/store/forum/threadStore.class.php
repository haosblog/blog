<?php

/**
 * File: threadStore.class.php
 * Created on : 2014-3-12, 23:41:18
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class threadStore extends store {


	public function getThreadList($fid, $order = 'reply', $page = 1, $pageCount = 20){
		$limit = $this->getLimit($page, $pageCount);
		//调用私有方法_threadOrder生成排序依据
		$orderBy = $this->_threadOrder($order);

		$query = $this->M('forum_thread')->getQueryByFid($fid, $orderBy, $limit);
		$return = array();
		while($row = DB::fetch($query)){
			$row['time'] = dgmdate($row['dateline']);
			$row['lastRply'] = dgmdate($row['lastpost']);
			$return[] = $row;
		}

		return $return;
	}

	public function getThreadInfoByTid($tid, $update = true){
		$m_thread = $this->M('forum_thread');
		$threadInfo = $m_thread->getThreadInfo($tid);
		$threadInfo['time'] = dgmdate($threadInfo['dateline']);

		//更新主题阅读量
		if($update){
			$m_thread->updateView($tid);
		}

		return $threadInfo;
	}

	/**
	 * 根据排序方法生成排序一句
	 *
	 * @param type $order	排序方法
	 * @return string
	 */
	private function _threadOrder($order){
		switch($order){
			case 'new';
				$orderBy = 'dateline';
				break;
			case 'hot':
				$orderBy = 'replies';
				break;
			default :
				$orderBy = 'lastpost';
				break;
		}

		return $orderBy;
	}
}
