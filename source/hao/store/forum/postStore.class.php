<?php

/**
 * File: postStore.class.php
 * Created on : 2014-3-12, 23:42:13
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 回帖数据类
 */
class postStore extends store {

	public function __construct($group = null, $groupType = 0) {
		parent::__construct($group, $groupType);
	}

	public function getPostList($tid){

	}

	/**
	 * 发布回帖
	 *
	 * @param type $tid
	 */
	public function addPost($data, $tid){
		//使用Discuz!内置的论坛模型行进操作
		$dm = core::m('forum_post', $tid);
		//return insertpost($data);
	}

}
