<?php

/**
 * File: forum.class.php
 * Created on : 2014-3-12, 23:39:28
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ���������
 */
class forumStore extends store {

	public function getForumByFup($fup){
		return $this->M('forum_forum')->getForumByFup($fup);
	}
}
