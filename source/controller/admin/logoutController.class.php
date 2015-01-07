<?php

/**
 * Description of logout
 *
 * @author hao
 */
class logoutController extends controller {
	public function index(){
		session_destroy();

		$this->showmessage('退出成功！', 1, '/admin/');
	}

}
