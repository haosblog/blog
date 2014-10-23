<?php

/**
 * File: categoryStore.class.php
 * Created on : 2014-3-12, 23:44:10
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 文章栏目数据类
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