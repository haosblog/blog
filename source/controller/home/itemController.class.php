<?php
/**
 * File: itemController.class.php
 * Created on : 2015-1-18, 16:06:03
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class itemController extends baseController {

	public function index(){
		$this->buffer['list'] = M('item')->select();

		$this->display();
	}
}
