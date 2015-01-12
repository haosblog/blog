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

		$this->buffer['list'] = M('album')->page($page, 9) ->select();

		$this->display();
	}

	public function view(){
		$aid = I('get.aid', 0, 'intval');
		$no = I('get.no', 0, 'intval');
		$password = I('post.password');

		if($password != ''){
			$password = md5($password);
		}

		$albumInfo = M('album')->field(name, intro, path, password, clew)->where(array('aid' => $aid, 'wsid' => $this->wsid))->selectOne();
		$albumInfo = $hp->album_photo_list($aid, $master, $password);
		$this->buffer['photoInfo'] = $hp->album_view($aid, $master, $no);
		$this->buffer['aid'] = $aid;

		if($albumInfo['password'] == 1){
			$title = '请输入密码访问';
		} else {
			$title = $photoInfo['title'];
		}
		$tpl->assign('master', $master);
		$tpl->assign('deny', $albumInfo['password']);
		$tpl->assign('aid', $aid);
		$tpl->assign('photoList', $albumInfo['photo']);
		$tpl->assign('albumInfo', $albumInfo['album']);
		$tpl->assign('photoInfo', $photoInfo);
		$tpl->assign('title', $title);
	}
}
