<?php

/**
 * File: indexAction
 * Created on : 2013-12-8, 19:56:55
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 后台首页
 */
class indexController extends baseController {
	public function index(){
		$this->display('index');
	}


	public function logout(){
		$_SESSION = null;

		$this->showmessage('退出成功！', 1, '/admin/login');
	}
}