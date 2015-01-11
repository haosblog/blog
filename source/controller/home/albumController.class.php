<?php
/**
 * File: albumController.class.php
 * Created on : 2015-1-11, 17:45:43
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class albumController extends baseController {

	public function index(){
		$page = $this->getPage();

		$this->buffer['list'] = M('album')->page($page, 9) ->select();
	}
}
