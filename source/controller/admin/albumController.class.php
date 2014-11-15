<?php
/**
 * File: albumController.class.php
 * Created on : 2014-11-15, 21:15:26
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class albumController extends baseController {

	public function __construct() {
		parent::__construct();
		$this->buffer['nav'] = 'photo';
	}

	public function index(){
		$limit = $this->getLimit(20);
		$this->buffer['list'] = M('album')->limit($limit)->where($this->where)->select();

		$this->display();
	}

	public function add(){

	}

	public function action(){

	}

	public function delete(){

	}
}
