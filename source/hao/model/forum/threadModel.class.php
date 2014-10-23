<?php
/**
 * File: threadModel.class.php
 * Created on : 2014-2-24, 23:45:09
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 帖子主题模型
 */
class threadModel extends model{

	public $tableObj = null;
	protected $_scope = array(
		'newpost' => array(
			'order' => '`dateline` DESC'
		),
		'newreply' => array(
			'order' => '`lastpost` DESC'
		)
	);

	public function __construct() {
		$tableName = 'forum_thread';

		parent::__construct($tableName);
	}

}
