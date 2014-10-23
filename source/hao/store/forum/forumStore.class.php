<?php

/**
 * File: forum.class.php
 * Created on : 2014-3-12, 23:39:28
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 板块数据类
 */
class forumStore extends store {

	public function getForumByFup($fup){
		return $this->M('forum_forum')->getForumByFup($fup);
	}
}
