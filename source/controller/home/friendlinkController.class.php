<?php
/**
 * File: friendController.class.php
 * Created on : 2015-1-12, 22:43:25
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * 友情链接控制器
 */
class friendlinkController extends baseController{
	public function index(){
		$this->display();
	}

	public function action(){
		$rule = array(
			'name' => array('explain' => '网站名', 'rule' => ''),
			'url' => array('explain' => '网站地址', 'rule' => ''),
			'content' => array('explain' => '网站描述', 'rule' => 'null'),
		);

		$maps = $this->getParam($rule);

		if($this->error){
			$this->showmessage($this->errormsg);
		}

		$maps['wsid'] = $this->wsid;

		M('friend_link')->insert($maps);

		$this->showmessage('申请成功，请等待管理员审核', 1);
	}
}
