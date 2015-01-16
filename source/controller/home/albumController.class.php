<?php
/**
 * File: albumController.class.php
 * Created on : 2015-1-11, 17:45:43
 * copyright 小皓 (C)2013-2099 版权所有
 * www.haosblog.com
 *
 * text
 */

class albumController extends baseController {

	public function index(){
		$page = $this->getPage();

		$this->buffer['list'] = M('album')->where(array('wsid' => $this->wsid))->page($page, 9) ->select();

		$this->display();
	}

	public function view(){
		$this->buffer['aid'] = $aid = I('get.aid', 0, 'intval');
		$no = I('get.no', 0, 'intval');
		$password = I('post.password');
		$pass = true;

		if($password != ''){
			$password = md5($password);
		}

		$where = array('aid' => $aid, 'wsid' => $this->wsid);

		$this->buffer['albumInfo'] = $albumInfo = M('album')->field('name', 'intro', 'password', 'clew')
				->where($where)->selectOne();

		if(!empty($albumInfo['password'])){
			if($password != $albumInfo['password']){
				$pass = false;
				$this->buffer['deny'] = true;
			}
		}

		if($pass){
			$m_photo = M('photo');
			$this->buffer['photoList'] = $m_photo->field('pid', 'title', 'path')->where($where)->select();
			$this->buffer['photoInfo'] = $m_photo->field('pid', 'title', 'path', 'summary')
					->where($where)->limit($no, 1)->selectOne();
		}

		if($albumInfo['password'] == 1){
			$this->title = '请输入密码访问';
		} else {
			$this->title = $this->buffer['photoInfo']['title'];
		}

		$this->display();
	}
}
