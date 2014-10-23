<?php
/**
 * File: linkAction
 * Created on : 2014-1-2, 22:20:55
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class linkAction extends adminBase {

	public function index(){
		$this->buffer['list'] = M('friend_link')->loadList(true);

		$this->display();
	}


	public function pass(){
		$id = intval($_GET['id']);

		M('friend_link')->pass($id);
		$this->showmessage('修改成功！', 1, '/admin/link');
	}

	public function delete(){
		$id = intval($_GET['id']);

		M('friend_link')->delete($id);
		$this->showmessage('删除成功！', 1, '/admin/link');
	}
}