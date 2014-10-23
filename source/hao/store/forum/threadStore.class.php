<?php

/**
 * File: threadStore.class.php
 * Created on : 2014-3-12, 23:41:18
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * text
 */
class threadStore extends store {


	public function getThreadList($fid, $order = 'reply', $page = 1, $pageCount = 20){
		$limit = $this->getLimit($page, $pageCount);
		//����˽�з���_threadOrder������������
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

		//���������Ķ���
		if($update){
			$m_thread->updateView($tid);
		}

		return $threadInfo;
	}

	/**
	 * �������򷽷���������һ��
	 *
	 * @param type $order	���򷽷�
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
