<?php

/**
 * File: forum_attachment.class.php
 * Created on : 2014-3-9, 13:52:09
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * text
 */
class forum_attachment_nModel  extends model {

	public $tableObj = null;
	public $originalTable = null;		//ԭʼ�ı���
	private $tid;				//����ID���ɲ�ָ��

	public function __construct($tid = 0){
		parent::__construct();
		die('aaa');

		if($tid > 0){
			$this->getTableID();
		} else {
			$this->tid = $tid;
		}

	}

	public function getTableID($tid = 0){
		$this->tid = substr($tid, -1, 1);
		$this->tableAlias = $this->tablename .'_'. $this->tid;
		return $this->tid;
	}

	public function getListByTid($tid){
		print_r($this->tableObj->get_tableids());
	}
}
