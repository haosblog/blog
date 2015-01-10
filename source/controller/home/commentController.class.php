<?php
/**
 * Created by NetBeans.
 * Author: hao
 * Date: 2015-1-6 18:42:44
 *
 * 请在此处输入文件注释
 */


class commentController extends baseController {

	public function index(){
		$page = $this->getPage();
		$where = array(
			'wsid' => $this->wsid,
			'type' => 0
		);

		$this->buffer['list'] = M('comment')->where($where)->select();

		$this->display();
	}
	
	public function action(){
		$t = I('get.t');
		
		$rule = array(
			'username' => array('explain' => '用户名', 'rule' => ''),
			'title' => array('explain' => '留言标题', 'rule' => 'null'),
			'portrait' => array('explain' => '头像'),
			'sex' => array('explain' => '栏目SEO描述', 'rule' => 'null'),
			'arc_title' => array('explain' => '文章页SEO标题', 'rule' => 'null'),
			'arc_keyword' => array('explain' => '文章页SEO关键词', 'rule' => 'null'),
			'arc_description' => array('explain' => '文章页SEO描述', 'rule' => 'null'),
		);
		
		$this->getParam($rule);
		$maps = array(
			'username' => I('username'),
			'title' => I('title'),
			'portrait' => I('portrait'),
			'sex' => I('sex'),
			'content' => I('content'),
			'type' => I('type'),
			'mid' => I('mid'),
			'fid' => I('fid'),
			'reply' => I('reply'),
			'email' => I('email'),
			'ip' => get_ip(),
			'time' => time(),
		);
		
		if(!$maps['username']){
			$errormsg;
		}
	}
}