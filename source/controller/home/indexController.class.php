<?php

/**
 * File: indexAction.class.php
 * Created on : 2013-12-27, 1:35:16
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class indexController extends baseController {

	public function index(){
		$this->title = $_SESSION['website']['seotitle'];
		$this->keyword = $_SESSION['website']['keyword'];
		$this->description = $_SESSION['website']['description'];
		$GLOBALS['block'] = 'aaaa';

		$this->display();
	}
}