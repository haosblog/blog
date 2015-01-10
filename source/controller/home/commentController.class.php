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
			'title' => array('explain' => '留言标题', 'rule' => 'null,max:30'),
			'portrait' => array('explain' => '头像'),
			'sex' => array('explain' => '性别', 'rule' => 'eq:m|f'),
			'content' => array('explain' => '留言内容'),
			'email' => array('explain' => '邮箱地址', 'rule' => 'null,email'),
		);
		
		$maps = $this->getParam($rule);
		
		$maps['type'] = I('type');
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