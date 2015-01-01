<?php
/**
 * File: articleController.class.php
 * Created on : 2014-12-3, 9:13:03
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class articleController extends baseController {

	private $c = '';

	public function index(){
		//$this->display();
		$c = 'adsfadsf';
		$this->a($c);
		echo($c);
	}

	function a(&$c){
		$this->c = &$c;

		$this->c = '广告狗';
	}
}
