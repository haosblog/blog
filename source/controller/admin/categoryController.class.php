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


	public function action(){
		print_r($_POST);die;
		$rule = array(
			'catname' => array('explain' => '栏目名', 'rule' => ''),
			'title' => array('explain' => '栏目SEO标题', 'rule' => 'null'),
			'keyword' => array('explain' => '栏目SEO关键词', 'rule' => 'null'),
			'description' => array('explain' => '栏目SEO描述', 'rule' => 'null'),
			'tpid' => array('explain' => '站点模板', 'rule' => ''),
			'isdefault' => array('explain' => '默认站点', 'rule' => 'null')
		);

		$param = $this->getParam($rule);

		print_r($param);
	}

	private function _update(){

	}
}