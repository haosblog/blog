<?php

/**
 * File: categoryStore.class.php
 * Created on : 2014-3-12, 23:44:10
 * copyright С� (C)2013-2099 ��Ȩ����
 * www.haosblog.com
 *
 * ������Ŀ������
 */
class categoryStore extends store {

	public function getList($upid){
		return $this->M('portal_category')->loadList($upid);
	}

	public function getInfo($catid){

	}

	public function getNameByID($catid){
		return $this->M('portal_category')->getNameByID($catid);
	}
}