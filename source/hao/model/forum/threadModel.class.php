<?php
/**
 * File: threadModel.class.php
 * Created on : 2014-2-24, 23:45:09
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ��������ģ��
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
