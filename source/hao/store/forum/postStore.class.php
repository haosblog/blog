<?php

/**
 * File: postStore.class.php
 * Created on : 2014-3-12, 23:42:13
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ����������
 */
class postStore extends store {

	public function __construct($group = null, $groupType = 0) {
		parent::__construct($group, $groupType);
	}

	public function getPostList($tid){

	}

	/**
	 * ��������
	 *
	 * @param type $tid
	 */
	public function addPost($data, $tid){
		//ʹ��Discuz!���õ���̳ģ���н�����
		$dm = core::m('forum_post', $tid);
		//return insertpost($data);
	}

}
