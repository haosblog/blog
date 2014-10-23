<?php

/**
 * File: categoryAction
 * Created on : 2013-12-31, 22:08:28
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */
class categoryController extends baseController {

	public function index(){
		$mid = intval($_GET['mid']);

		$this->buffer['list'] = M('category')->loadList($mid);
		$this->buffer['mid'] = $mid;

		$this->display();
	}


	public function addAction(){
		$rule = array(
			'wsid' => array('explain' => '站点ID', 'rule' => 'null'),
			'sitename' => array('explain' => '站点名', 'rule' => 'max:30'),
			'seotitle' => array('explain' => '站点标题', 'rule' => 'max:255'),
			'keyword' => array('explain' => '站点关键字', 'rule' => 'null'),
			'description' => array('explain' => '站点描述', 'rule' => 'null'),
			'tpid' => array('explain' => '站点模板', 'rule' => ''),
			'isdefault' => array('explain' => '默认站点', 'rule' => 'null')
		);
	}

	public function editAction(){
		$cid = intval($_POST['cid']);


	}

	private function _update(){

	}
}