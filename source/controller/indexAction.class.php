<?php

/**
 * File: indexAction.class.php
 * Created on : 2013-12-27, 1:35:16
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class indexAction extends controller {

	public function index(){
		$this->title = $GLOBALS['website']['seotitle'];
		$this->keyword = $GLOBALS['website']['keyword'];
		$this->description = $GLOBALS['website']['description'];
		$GLOBALS['block'] = 'aaaa';

		$this->display('index');
	}
}