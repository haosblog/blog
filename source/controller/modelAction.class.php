<?php
/**
 * File: modelAction
 * Created on : 2013-12-28, 1:14:54
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class modelAction extends controller {
	private $_router;
	private $m_model = null;


	public function init($router) {
		$this->_router = $router;
		$this->m_model = SM($router[1]);

		switch ($router[2]){
			case 'view':
				$this->_view();
				break;
			case 'list' :
			default :
				$this->_showlist();
				break;

		}

		parent::init();
	}


	/**
	 * 列表页面
	 */
	private function _showlist(){
		echo('aaa');
	}


	/**
	 * 展示页
	 */
	private function _view(){

	}
}