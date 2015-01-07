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

		$this->buffer['list'] = M('category')->where(array('mid' => $mid, 'wsid' => $this->wsid))->select();
		$this->buffer['mid'] = $mid;

		$this->display();
	}


	public function action(){
		$rule = array(
			'catname' => array('explain' => '栏目名', 'rule' => ''),
			'title' => array('explain' => '栏目SEO标题', 'rule' => 'null'),
			'keyword' => array('explain' => '栏目SEO关键词', 'rule' => 'null'),
			'description' => array('explain' => '栏目SEO描述', 'rule' => 'null'),
			'arc_title' => array('explain' => '文章页SEO标题', 'rule' => 'null'),
			'arc_keyword' => array('explain' => '文章页SEO关键词', 'rule' => 'null'),
			'arc_description' => array('explain' => '文章页SEO描述', 'rule' => 'null'),
		);

		$param = $this->getParam($rule);
		$param['wsid'] = $this->wsid;
		$cid = intval($_POST['cid']);

		if($cid){
			M('category')->where(array('cid' => $cid))->update($param);
			$msg = '修改栏目成功！';
		} else {
			$param['wsid'] = $this->wsid;
			M('category')->insert($param);
			$msg = '新增栏目成功！';
		}

		$this->showmessage($msg, 1);
	}

	private function _update(){

	}
}